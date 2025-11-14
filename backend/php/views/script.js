
function main() {
    const partijStem1 = 34, partijStem2 = 50, partijstem3 = 60;
        // bar chart
    var bardata = [
        {
            x: ['partij1', 'partij2', 'partij3'],
            y: [partijStem1, partijStem2, partijstem3],
            type: 'bar',
            marker: {
                color: ['#2e8b57', '#e74c3c', '#3498db']
            }
        }
    ];

    Plotly.newPlot('barchart', bardata);


    // pie chart
    var piedata = [{
        values: [245, 234, 345],
        labels: [partijStem1, partijStem2, partijstem3],
        type: 'pie',
        marker: {
            colors: ['#f39c12', '#9b59b6', '#1abc9c']
        }
    }];

    Plotly.newPlot('piechart', piedata);


    const barCh = document.getElementById("bar").addEventListener("click", () => {
        const displayBar = document.getElementById("barchart").style.display = "block";
        const disablePie = document.getElementById("piechart").style.display = "none";
    })

    const pieCh = document.getElementById("pie").addEventListener("click", () => {
        const disablePie = document.getElementById("piechart").style.display = "block";
        const displayBar = document.getElementById("barchart").style.display = "none";
    })
}
main();