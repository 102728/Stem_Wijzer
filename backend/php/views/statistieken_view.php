<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistieken - StemWijzer</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>
<body class="partijBody">
    <?php include 'views/navbar.php'; ?>

    <div class="stats-container">
        <div class="stats-header">
            <h1>Statistieken</h1>
            <p>Overzicht van stemmen per partij</p>
        </div>

        <div class="stats-summary">
            <div class="stat-card">
                <div class="stat-number" data-target="<?php echo count($partijen); ?>">0</div>
                <div class="stat-label">Totaal Partijen</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-target="<?php echo $totaalStemmen; ?>">0</div>
                <div class="stat-label">Totaal Stemmen</div>
            </div>
        </div>

        <div class="chart-controls">
            <button class="chart-btn active" id="barBtn">Stemmen</button>
            <button class="chart-btn" id="pieBtn">Locatie</button>
        </div>

        <div class="chart-wrapper">
            <div class="chart-controls-inner" id="chartControlsInner">
                <button class="chart-btn-inner active" id="barBtnInner">Bar Chart</button>
                <button class="chart-btn-inner" id="pieBtnInner">Pie Chart</button>
            </div>
            <div id="barChart" class="chart-container"></div>
            <div id="pieChart" class="chart-container" style="display: none;"></div>
            <div id="locatieView" class="chart-container" style="display: none; min-height: 500px; position: relative; background: #3a3a3a;">
                <div id="map" style="width: 100%; height: 500px; border-radius: 6px; background: #3a3a3a;"></div>
            </div>
        </div>
    </div>

    <script>
        // Animate stat numbers on page load - separate functions with speed based on value
        document.addEventListener('DOMContentLoaded', () => {
            const statNumbers = document.querySelectorAll('.stat-number');
            const sharedStartTime = performance.now();

            function animateCounter(element, startTime) {
                const target = parseInt(element.getAttribute('data-target'));
                const label = element.nextElementSibling.textContent;
                
                // Duration based on which counter it is
                let duration;
                if (label === 'Totaal Partijen') {
                    duration = target * 50; // 50x the value
                } else {
                    duration = target * 2; // 2x the value for Totaal Stemmen
                }
                
                function update(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Easing function for smooth animation
                    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                    
                    const current = Math.floor(easeOutQuart * target);
                    element.textContent = current;
                    
                    if (progress < 1) {
                        requestAnimationFrame(update);
                    } else {
                        element.textContent = target;
                    }
                }
                
                requestAnimationFrame(update);
            }

            // Start all counters at the same time but with different speeds
            statNumbers.forEach(element => {
                animateCounter(element, sharedStartTime);
            });
        });

        // Data van PHP naar JavaScript
        const partijen = <?php echo json_encode($partijen); ?>;
        const partijenNamen = partijen.map(p => p.partij_naam);
        const partijenStemmen = partijen.map(p => parseInt(p.aantalStem));
        
        // Kleuren palette
        const colors = ['#2e8b57', '#e74c3c', '#3498db', '#f39c12', '#9b59b6', '#1abc9c', '#e67e22', '#34495e'];

        // Bar Chart
        const barData = [{
            x: partijenNamen,
            y: partijenStemmen,
            type: 'bar',
            marker: {
                color: colors.slice(0, partijen.length)
            }
        }];

        const barLayout = {
            paper_bgcolor: 'rgb(50, 50, 50)',
            plot_bgcolor: 'rgb(50, 50, 50)',
            font: {
                color: '#fff',
                size: 14
            },
            xaxis: {
                gridcolor: 'rgba(255, 255, 255, 0.1)',
                tickangle: -45
            },
            yaxis: {
                title: 'Aantal Stemmen',
                gridcolor: 'rgba(255, 255, 255, 0.1)'
            },
            margin: { l: 60, r: 40, t: 40, b: 100 }
        };

        Plotly.newPlot('barChart', barData, barLayout, {
            responsive: true,
            displayModeBar: false,
            staticPlot: true
        });

        // Pie Chart
        const pieData = [{
            values: partijenStemmen,
            labels: partijenNamen,
            type: 'pie',
            marker: {
                colors: colors.slice(0, partijen.length)
            },
            textinfo: 'label+percent',
            textfont: {
                size: 14
            }
        }];

        const pieLayout = {
            paper_bgcolor: 'rgb(50, 50, 50)',
            font: {
                color: '#fff',
                size: 14
            },
            margin: { l: 40, r: 40, t: 40, b: 40 }
        };

        Plotly.newPlot('pieChart', pieData, pieLayout, {
            responsive: true,
            displayModeBar: false,
            staticPlot: true
        });

        // Chart switching - original buttons
        const barBtn = document.getElementById('barBtn');
        const pieBtn = document.getElementById('pieBtn');
        const barChart = document.getElementById('barChart');
        const pieChart = document.getElementById('pieChart');
        const locatieView = document.getElementById('locatieView');
        const chartControlsInner = document.getElementById('chartControlsInner');
        const chartControlsInner = document.getElementById('chartControlsInner');

        barBtn.addEventListener('click', () => {
            barChart.style.display = 'block';
            pieChart.style.display = 'none';
            locatieView.style.display = 'none';
            chartControlsInner.style.display = 'flex';
            barBtn.classList.add('active');
            pieBtn.classList.remove('active');
        });

        pieBtn.addEventListener('click', () => {
            barChart.style.display = 'none';
            pieChart.style.display = 'none';
            locatieView.style.display = 'block';
            chartControlsInner.style.display = 'none';
            pieBtn.classList.add('active');
            barBtn.classList.remove('active');
        });

        // Chart switching - inner buttons
        const barBtnInner = document.getElementById('barBtnInner');
        const pieBtnInner = document.getElementById('pieBtnInner');

        barBtnInner.addEventListener('click', () => {
            barChart.style.display = 'block';
            pieChart.style.display = 'none';
            barBtnInner.classList.add('active');
            pieBtnInner.classList.remove('active');
        });

        pieBtnInner.addEventListener('click', () => {
            pieChart.style.display = 'block';
            barChart.style.display = 'none';
            pieBtnInner.classList.add('active');
            barBtnInner.classList.remove('active');
        });

        // Initialize Leaflet map
        let map = null;
        
        function initMap() {
            if (map) return; // Already initialized
            
            // Center of Netherlands with precise bounds
            map = L.map('map', {
                zoomControl: false,
                dragging: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                keyboard: false,
                touchZoom: false,
                attributionControl: false
            }).setView([52.2, 5.5], 7);
            
            // Plain gray background - no tiles/maps
            map.getPane('tilePane').style.display = 'none';
            
            // Fetch gemeente GeoJSON
            fetch('https://cartomap.github.io/nl/wgs84/gemeente_2023.geojson')
                .then(response => response.json())
                .then(data => {
                    const totalGemeentes = data.features.length;
                    
                    // Add each gemeente with same green color
                    const netherlandsLayer = L.geoJSON(data, {
                        style: function(feature, index) {
                            return {
                                fillColor: '#2e8b57',
                                fillOpacity: 0.8,
                                color: '#1a4d2e',
                                weight: 1,
                                opacity: 1
                            };
                        }
                    }).addTo(map);
                    
                    // Fit map to Netherlands bounds perfectly
                    map.fitBounds(netherlandsLayer.getBounds(), {
                        padding: [20, 20]
                    });
                })
                .catch(err => {
                    console.error('Failed to load gemeente GeoJSON:', err);
                });
        }
        
        // Initialize map when Locatie button is clicked
        pieBtn.addEventListener('click', () => {
            setTimeout(() => {
                initMap();
                if (map) map.invalidateSize();
            }, 100);
        });
    </script>
</body>
</html>