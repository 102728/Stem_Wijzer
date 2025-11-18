// bar chart
var bardata = [
    {
        x: ['giraffes', 'orangutans', 'monkeys'],
        y: [13, 9, 20],
        type: 'bar'
    }
];

Plotly.newPlot('barchart', bardata);


// pie chart
var piedata = [{
    values: [245, 234, 345],
    labels: ['Residential', 'Non-Residential', 'Utility'],
    type: 'pie'
}];

Plotly.newPlot('piechart', piedata);

// sunburst chart

var sunburstdata = [{
    type: "sunburst",
    // namen
    labels: ["Stemmen", "Links", "Rechts", "Midden", "Persoon1", "Persoon2", "Persoon3", "Persoon4", "Persoon5", "Persoon6"],
    // namen van de dingen waar ze in zitten
    parents: ["", "Stemmen", "Stemmen", "Stemmen", "Links", "Links", "Rechts", "Rechts", "Midden", "Midden"],
    // values van de labels
    values: [175, 40, 60, 75, 30, 10, 40, 20, 70, 5],

    outsidetextfont: { size: 20, color: "#377eb8" },
    leaf: { opacity: 0.5 },
    marker: { line: { width: 2 } },
    branchvalues: 'total',
    textposition: 'inside',
    insidetextorientation: 'auto'
}];

var layout = {
    margin: { l: 10, r: 10, b: 10, t: 10 },
    width: 500,
    height: 500
};


Plotly.newPlot('sunburstchart', sunburstdata, layout);