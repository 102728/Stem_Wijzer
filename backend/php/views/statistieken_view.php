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
    <?php include 'navbar.php'; ?>

    <div class="stats-container">
        <div class="stats-header">
            <h1>Statistieken</h1>
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
            <div class="stat-card">
                <div class="stat-number" data-target="<?php echo $actieveGemeentes; ?>">0</div>
                <div class="stat-label">Actieve Gemeentes</div>
            </div>
        </div>

        <div class="top-section">
            <div class="top-parties">
                <h2>Top 3 Partijen</h2>
                <?php foreach ($topPartijen as $index => $partij): ?>
                    <div class="top-party-item">
                        <div class="rank-badge">#<?= $index + 1 ?></div>
                        <div class="party-details">
                            <h3><?= htmlspecialchars($partij['partij_naam']) ?></h3>
                            <p><?= htmlspecialchars(substr($partij['ideologie'], 0, 70)) ?>...</p>
                        </div>
                        <div class="party-score">
                            <span class="score-number"><?= $partij['aantalStem'] ?></span>
                            <span class="score-label">stemmen</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="top-gemeente">
                <h2>Meest Actieve Gemeente</h2>
                <?php if ($topGemeente): ?>
                    <div class="gemeente-highlight">
                        <div class="gemeente-name"><?= htmlspecialchars($topGemeente['gemeente']) ?></div>
                        <div class="gemeente-stats">
                            <span class="gemeente-number"><?= $topGemeente['aantalStemmen'] ?></span>
                            <span class="gemeente-label">stemmen</span>
                        </div>
                        <div class="gemeente-percentage">
                            <?= round(($topGemeente['aantalStemmen'] / $totaalStemmen) * 100, 1) ?>% van totaal
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="chart-controls">
            <button class="chart-btn active" id="barBtn">Stemmen</button>
            <button class="chart-btn" id="pieBtn">Locatie</button>
        </div>

        <div class="chart-wrapper">
            <div class="chart-controls-inner" id="chartControlsInner">
                <button class="chart-btn-inner active" id="pieBtnInner">Pie Chart</button>
                <button class="chart-btn-inner" id="barBtnInner">Bar Chart</button>
            </div>
            <div id="pieChart" class="chart-container"></div>
            <div id="barChart" class="chart-container" style="display: none;"></div>
            <div id="locatieView" class="chart-container" style="display: none; min-height: 600px; position: relative; background: linear-gradient(135deg, #2e2e2e 0%, #3a3a3a 100%); padding: 25px; border-radius: 10px;">
                <h2 style="color: #2e8b57; margin: 0 0 20px 0; font-size: 24px; text-align: center;">Stemmen per Gemeente</h2>
                <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 8px;">
                    <button class="partij-filter-btn active" data-partij="alle" style="padding: 10px 20px; background: #2e8b57; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                        Alle Stemmen
                    </button>
                    <?php foreach ($partijen as $partij): ?>
                        <button class="partij-filter-btn" data-partij="<?= htmlspecialchars($partij['partij_naam']) ?>" style="padding: 10px 20px; background: #4a4a4a; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                            <?= htmlspecialchars($partij['partij_naam']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <div id="map" style="width: 100%; height: 500px; border-radius: 10px; background: #3a3a3a; box-shadow: 0 4px 15px rgba(0,0,0,0.3);"></div>
                <div id="mapTooltip" style="display: none; position: absolute; background: #2e2e2e; color: white; padding: 12px 18px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.4); pointer-events: none; z-index: 1000; font-size: 14px; border: 1px solid rgba(46, 139, 87, 0.5);">
                    <div id="tooltipGemeente" style="font-weight: bold; margin-bottom: 5px; font-size: 15px;"></div>
                    <div id="tooltipStemmen" style="color: #2e8b57; font-weight: 500;"></div>
                </div>
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
        
        // Gemeente stemmen data
        const gemeenteStemmen = <?php echo json_encode($gemeenteStemmen); ?>;
        const gemeenteStemmenMap = {};
        gemeenteStemmen.forEach(g => {
            gemeenteStemmenMap[g.gemeente.toLowerCase()] = parseInt(g.aantalStemmen);
        });
        
        // Partij gemeente stemmen data
        const partijGemeenteStemmen = <?php echo json_encode($partijGemeenteStemmen); ?>;
        const partijGemeenteMap = {};
        partijGemeenteStemmen.forEach(item => {
            if (!partijGemeenteMap[item.partij_naam]) {
                partijGemeenteMap[item.partij_naam] = {};
            }
            partijGemeenteMap[item.partij_naam][item.gemeente.toLowerCase()] = parseInt(item.aantalStemmen);
        });
        
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
                tickangle: -45,
                tickfont: {
                    size: 9
                }
            },
            yaxis: {
                title: 'Aantal Stemmen',
                gridcolor: 'rgba(255, 255, 255, 0.1)'
            },
            margin: { l: 60, r: 60, t: 40, b: 120 },
            autosize: true,
            width: null,
            height: null
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
                colors: colors.slice(0, partijen.length),
                line: {
                    color: 'white',
                    width: 0.5
                }
            },
            textinfo: 'none',
            hovertemplate: '<b>%{label}</b><br>Stemmen: %{value}<br>Percentage: %{percent}<extra></extra>',
            hoverlabel: {
                font: {
                    size: 12
                }
            }
        }];

        const pieLayout = {
            paper_bgcolor: 'rgb(50, 50, 50)',
            font: {
                color: '#fff',
                size: 14
            },
            margin: { l: 40, r: 40, t: 40, b: 40 },
            showlegend: true,
            legend: {
                bgcolor: 'rgba(0,0,0,0)',
                borderwidth: 0
            }
        };

        Plotly.newPlot('pieChart', pieData, pieLayout, {
            responsive: true,
            displayModeBar: false,
            staticPlot: true
        });

        // Chart switching - original buttons (Stemmen/Locatie)
        const barBtn = document.getElementById('barBtn');
        const pieBtn = document.getElementById('pieBtn');
        const barChart = document.getElementById('barChart');
        const pieChart = document.getElementById('pieChart');
        const locatieView = document.getElementById('locatieView');
        const chartControlsInner = document.getElementById('chartControlsInner');
        
        // Track which chart was last active
        let lastActiveChart = 'pie';

        barBtn.addEventListener('click', () => {
            // Stemmen button - restore last active chart
            locatieView.style.display = 'none';
            chartControlsInner.style.display = 'flex';
            barBtn.classList.add('active');
            pieBtn.classList.remove('active');
            
            if (lastActiveChart === 'bar') {
                pieChart.style.display = 'none';
                barChart.style.display = 'block';
                pieBtnInner.classList.remove('active');
                barBtnInner.classList.add('active');
            } else {
                pieChart.style.display = 'block';
                barChart.style.display = 'none';
                pieBtnInner.classList.add('active');
                barBtnInner.classList.remove('active');
            }
        });

        pieBtn.addEventListener('click', () => {
            // Locatie button
            barChart.style.display = 'none';
            pieChart.style.display = 'none';
            locatieView.style.display = 'block';
            chartControlsInner.style.display = 'none';
            pieBtn.classList.add('active');
            barBtn.classList.remove('active');
        });

        // Chart switching - inner buttons (Bar/Pie chart switchers)
        const barBtnInner = document.getElementById('barBtnInner');
        const pieBtnInner = document.getElementById('pieBtnInner');

        barBtnInner.addEventListener('click', () => {
            pieChart.style.display = 'none';
            barChart.style.display = 'block';
            pieBtnInner.classList.remove('active');
            barBtnInner.classList.add('active');
            lastActiveChart = 'bar'; // Update tracker
        });

        pieBtnInner.addEventListener('click', () => {
            barChart.style.display = 'none';
            pieChart.style.display = 'block';
            barBtnInner.classList.remove('active');
            pieBtnInner.classList.add('active');
            lastActiveChart = 'pie'; // Update tracker
        });

        // Initialize Leaflet map
        let map = null;
        let netherlandsLayer = null;
        let activePartijFilter = 'alle';
        const tooltip = document.getElementById('mapTooltip');
        const tooltipGemeente = document.getElementById('tooltipGemeente');
        const tooltipStemmen = document.getElementById('tooltipStemmen');
        
        function getGemeenteStemmen(gemeenteKey) {
            if (activePartijFilter === 'alle') {
                return gemeenteStemmenMap[gemeenteKey] || 0;
            } else {
                return (partijGemeenteMap[activePartijFilter] && partijGemeenteMap[activePartijFilter][gemeenteKey]) || 0;
            }
        }
        
        function updateMapColors() {
            if (!netherlandsLayer) return;
            
            netherlandsLayer.eachLayer(function(layer) {
                const gemeenteNaam = layer.feature.properties.statnaam || layer.feature.properties.name;
                const gemeenteKey = gemeenteNaam ? gemeenteNaam.toLowerCase() : '';
                const aantalStemmen = getGemeenteStemmen(gemeenteKey);
                const heeftStemmen = aantalStemmen > 0;
                
                layer.setStyle({
                    fillColor: heeftStemmen ? '#2e8b57' : '#4a4a4a',
                    fillOpacity: heeftStemmen ? 0.8 : 0.5,
                    color: heeftStemmen ? '#1a4d2e' : '#333',
                    weight: 1,
                    opacity: 1
                });
            });
        }
        
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
                    // Add each gemeente with conditional styling
                    netherlandsLayer = L.geoJSON(data, {
                        style: function(feature) {
                            const gemeenteNaam = feature.properties.statnaam || feature.properties.name;
                            const gemeenteKey = gemeenteNaam ? gemeenteNaam.toLowerCase() : '';
                            const aantalStemmen = getGemeenteStemmen(gemeenteKey);
                            const heeftStemmen = aantalStemmen > 0;
                            
                            return {
                                fillColor: heeftStemmen ? '#2e8b57' : '#4a4a4a',
                                fillOpacity: heeftStemmen ? 0.8 : 0.5,
                                color: heeftStemmen ? '#1a4d2e' : '#333',
                                weight: 1,
                                opacity: 1
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            const gemeenteNaam = feature.properties.statnaam || feature.properties.name;
                            const gemeenteKey = gemeenteNaam ? gemeenteNaam.toLowerCase() : '';
                            
                            layer.on('mouseover', function(e) {
                                const aantalStemmen = getGemeenteStemmen(gemeenteKey);
                                
                                if (aantalStemmen > 0) {
                                    const partijText = activePartijFilter === 'alle' ? 'stemmen' : activePartijFilter;
                                    tooltipGemeente.textContent = gemeenteNaam;
                                    tooltipStemmen.textContent = aantalStemmen + ' ' + (aantalStemmen === 1 ? 'stem' : 'stemmen');
                                    tooltip.style.display = 'block';
                                    
                                    // Darken on hover
                                    layer.setStyle({
                                        fillColor: '#1a5c3a',
                                        fillOpacity: 1,
                                        weight: 1
                                    });
                                }
                            });
                            
                            layer.on('mouseout', function() {
                                tooltip.style.display = 'none';
                                
                                const aantalStemmen = getGemeenteStemmen(gemeenteKey);
                                if (aantalStemmen > 0) {
                                    // Reset style
                                    layer.setStyle({
                                        fillColor: '#2e8b57',
                                        fillOpacity: 0.8,
                                        weight: 1
                                    });
                                }
                            });
                            
                            layer.on('mousemove', function(e) {
                                const containerPos = document.getElementById('locatieView').getBoundingClientRect();
                                const mouseX = e.originalEvent.clientX;
                                const mouseY = e.originalEvent.clientY;
                                tooltip.style.left = (mouseX - containerPos.left + 15) + 'px';
                                tooltip.style.top = (mouseY - containerPos.top + 15) + 'px';
                            });
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
        
        // Partij filter buttons
        document.querySelectorAll('.partij-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.partij-filter-btn').forEach(b => {
                    b.classList.remove('active');
                    b.style.background = '#4a4a4a';
                    b.style.transform = 'scale(1)';
                });
                this.classList.add('active');
                this.style.background = '#2e8b57';
                this.style.transform = 'scale(1.05)';
                
                // Update filter
                activePartijFilter = this.getAttribute('data-partij');
                
                // Update map colors
                updateMapColors();
            });
            
            // Hover effects
            btn.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
                }
            });
            
            btn.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
                }
            });
        });
    </script>
    <script src="./main.js"></script>
</body>
</html>