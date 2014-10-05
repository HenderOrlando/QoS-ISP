/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Chart.defaults.global = {
    // Boolean - Whether to animate the chart
    animation: true,

    // Number - Number of animation steps
    animationSteps: 60,

    // String - Animation easing effect
    animationEasing: "easeOutQuart",

    // Boolean - If we should show the scale at all
    showScale: true,

    // Boolean - If we want to override with a hard coded scale
    scaleOverride: false,

    // ** Required if scaleOverride is true **
    // Number - The number of steps in a hard coded scale
    scaleSteps: null,
    // Number - The value jump in the hard coded scale
    scaleStepWidth: null,
    // Number - The scale starting value
    scaleStartValue: null,

    // String - Colour of the scale line
    scaleLineColor: "rgba(0,0,0,.1)",

    // Number - Pixel width of the scale line
    scaleLineWidth: 1,

    // Boolean - Whether to show labels on the scale
    scaleShowLabels: true,

    // Interpolated JS string - can access value
    scaleLabel: "<%=value%>",

    // Boolean - Whether the scale should stick to integers, not floats even if drawing space is there
    scaleIntegersOnly: true,

    // Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero: false,

    // String - Scale label font declaration for the scale label
    scaleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Scale label font size in pixels
    scaleFontSize: 12,

    // String - Scale label font weight style
    scaleFontStyle: "normal",

    // String - Scale label font colour
    scaleFontColor: "#666",

    // Boolean - whether or not the chart should be responsive and resize when the browser does.
    responsive: true,

    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: true,

    // Boolean - Determines whether to draw tooltips on the canvas or not
    showTooltips: true,

    // Array - Array of string names to attach tooltip events
    tooltipEvents: ["mousemove", "touchstart", "touchmove"],

    // String - Tooltip background colour
    tooltipFillColor: "rgba(0,0,0,0.8)",

    // String - Tooltip label font declaration for the scale label
    tooltipFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Tooltip label font size in pixels
    tooltipFontSize: 14,

    // String - Tooltip font weight style
    tooltipFontStyle: "normal",

    // String - Tooltip label font colour
    tooltipFontColor: "#fff",

    // String - Tooltip title font declaration for the scale label
    tooltipTitleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Tooltip title font size in pixels
    tooltipTitleFontSize: 14,

    // String - Tooltip title font weight style
    tooltipTitleFontStyle: "bold",

    // String - Tooltip title font colour
    tooltipTitleFontColor: "#fff",

    // Number - pixel width of padding around tooltip text
    tooltipYPadding: 6,

    // Number - pixel width of padding around tooltip text
    tooltipXPadding: 6,

    // Number - Size of the caret on the tooltip
    tooltipCaretSize: 8,

    // Number - Pixel radius of the tooltip border
    tooltipCornerRadius: 6,

    // Number - Pixel offset from point x to tooltip edge
    tooltipXOffset: 10,

    // String - Template string for single tooltips
    tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",

    // String - Template string for single tooltips
    multiTooltipTemplate: "<%= value %>",

    // Function - Will fire on animation progression.
    onAnimationProgress: function(){},

    // Function - Will fire on animation completion.
    onAnimationComplete: function(){}
},
colors = {
    'normal':[],
    'highlight':[]
},
optsPie = $.extend({},Chart.defaults.Pie,{
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke : true,

    //String - The colour of each segment stroke
    segmentStrokeColor : "#fff",

    //Number - The width of each segment stroke
    segmentStrokeWidth : 3,

    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout : 1, // This is 0 for Pie charts

    //Number - Amount of animation steps
    animationSteps : 20,

    //String - Animation easing effect
    animationEasing : "easeOutQuart",

    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate : true,

    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale : true,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

}),
optsLine = $.extend({},Chart.defaults.Line,{
    ///Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether the line is curved between points
    bezierCurve : true,

    //Number - Tension of the bezier curve between points
    bezierCurveTension : 0.4,

    //Boolean - Whether to show a dot for each point
    pointDot : true,

    //Number - Radius of each point dot in pixels
    pointDotRadius : 4,

    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth : 1,

    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,

    //Boolean - Whether to show a stroke for datasets
    datasetStroke : true,

    //Number - Pixel width of dataset stroke
    datasetStrokeWidth : 2,

    //Boolean - Whether to fill the dataset with a colour
    datasetFill : true,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

}),
optsBar = $.extend({},Chart.defaults.Bar,{
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero : true,

    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - If there is a stroke on each bar
    barShowStroke : true,

    //Number - Pixel width of the bar stroke
    barStrokeWidth : 2,

    //Number - Spacing between each of the X value sets
    barValueSpacing : 5,

    //Number - Spacing between data sets within X values
    barDatasetSpacing : 1,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

})
;

function legend(parent, data) {
    if(parent.className){
        parent.className += ' legend';
    }else{
        parent.className = 'legend';
    }
    var datas = data.hasOwnProperty('datasets') ? data.datasets : data;

    // remove possible children of the parent
    while(parent.hasChildNodes()) {
        parent.removeChild(parent.lastChild);
    }

    datas.forEach(function(d) {
        var title = document.createElement('span')
            text = d.title?d.title:d.label;
        title.className = 'title';
        title.style.borderColor = d.hasOwnProperty('strokeColor') ? d.strokeColor : d.color;
        title.style.borderStyle = 'solid';
        parent.appendChild(title);

        var text = document.createTextNode(text);
        title.appendChild(text);
    });
}
/*
* String type Tipos de gráficos ("line", "bar", "pie")
*/
function getDataGraph(data, type){
   if($.type(type) !== 'string'){
       return data;
   }
    var isDataPie = !data.hasOwnProperty('datasets'),
        datas = isDataPie ? data : data.datasets,
        dataGraph = {}, i=0;
    if(isDataPie && type === 'pie' ){
        dataGraph = data;
    }else{
        var data_ = []; 
        datas.forEach(function(d) {
             var title = d.title?d.title:d.label;
             if(isDataPie){
                 if(!dataGraph.datasets){
                     dataGraph.datasets = []
                 }
                 switch(type){
                     case 'line':
                         var col = colors.normal.length;
                         if(!dataGraph.labels){
                             dataGraph.labels = [];
                         }
                         dataGraph.labels[i] = title;
                         data_[i] = d.value;
                         dataGraph.datasets[0] = {
                             label: title,
                             fillColor: d.highlight,
                             strokeColor: getColor(col, true),
                             pointColor: getColor(col, true),
                             pointStrokeColor: getColor(col),
                             pointHighlightFill: getColor(col),
                             pointHighlightStroke: getColor(col, true),
                             data: data_
                         };
                         break;
                     case 'bar':
//                         var col = colors.normal.length;
//                         if(!dataGraph.labels){
//                             dataGraph.labels = [];
//                         }
//                         dataGraph.labels[i] = title;
//                         data_[i] = d.value;
//                         dataGraph.datasets[i] = {
//                             label: title,
//                             fillColor: getColor(i),
//                             strokeColor: getColor(i),
//                             highlightFill: getColor(i),
//                             highlightStroke: getColor(i),
//                             data: data_
//                         };
                         dataGraph.labels = ['Dato'];
                         dataGraph.datasets[i] = {
                             label: title,
                             fillColor: d.highlight,
                             strokeColor: d.color,
                             highlightFill: d.highlight,
                             highlightStroke: d.color,
                             data: [d.value]
                         };
                         break;
                 }
             }else{
             }
             i++;
        });
        if(type === 'line'){
            dataGraph.datasets[0].data = data_;
        }
    }
    return dataGraph;
}
function getDatasetEvent(e, chart){
    var active = null;
    if($.type(chart.getBarsAtEvent) == 'function'){
        active = chart.getBarsAtEvent(e);
    }else if($.type(chart.getSegmentsAtEvent) == 'function'){
        active = chart.getSegmentsAtEvent(e);
    }else if($.type(chart.getPointsAtEvent) == 'function'){
        active = chart.getPointsAtEvent(e);
    }
    return active;
}
function getRandColor(i, brightness, darkness){
    //6 levels of brightness from 0 to 5, 0 being the darkest
    brightness = brightness && brightness >= 3 && brightness < 6?brightness:4;
    darkness = darkness && darkness <= 3 && darkness >= 0?darkness:brightness - 2;
    var rgb = [Math.random() * 256, Math.random() * 256, Math.random() * 256];
    var mixNormal = [darkness*51, darkness*51, darkness*51]; //51 => 255/5
    var mixHighlight = [(brightness)*51, (brightness)*51, (brightness)*51]; //51 => 255/5
    var mixedrgbNormal = [rgb[0] + mixNormal[0], rgb[1] + mixNormal[1], rgb[2] + mixNormal[2]].map(function(x){ return Math.round(x/2.0)});
    var mixedrgbHighlight = [rgb[0] + mixHighlight[0], rgb[1] + mixHighlight[1], rgb[2] + mixHighlight[2]].map(function(x){ return Math.round(x/2.0)});
    colors.normal[i] = "rgb(" + mixedrgbNormal.join(",") + ")";
    colors.highlight[i] = "rgb(" + mixedrgbHighlight.join(",") + ")";
//    return "rgb(" + mixedrgb.join(",") + ")";
}
function getColor(i, normal){
    var ret = '#eee';
    if(!colors.normal[i]){
        getRandColor(i);
    }
    if(normal){
        ret = colors.normal[i];
    }else{
        ret = colors.highlight[i];
    }
    return ret;
}
function rainbow(numOfSteps, step) {
    // This function generates vibrant, "evenly spaced" colours (i.e. no clustering). This is ideal for creating easily distinguishable vibrant markers in Google Maps and other apps.
    // Adam Cole, 2011-Sept-14
    // HSV to RBG adapted from: http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript
    var r, g, b;
    var h = step / numOfSteps;
    var i = ~~(h * 6);
    var f = h * 6 - i;
    var q = 1 - f;
    switch(i % 6){
        case 0: r = 1, g = f, b = 0; break;
        case 1: r = q, g = 1, b = 0; break;
        case 2: r = 0, g = 1, b = f; break;
        case 3: r = 0, g = q, b = 1; break;
        case 4: r = f, g = 0, b = 1; break;
        case 5: r = 1, g = 0, b = q; break;
    }
    var c = "#" + ("00" + (~ ~(r * 255)).toString(16)).slice(-2) + ("00" + (~ ~(g * 255)).toString(16)).slice(-2) + ("00" + (~ ~(b * 255)).toString(16)).slice(-2);
    return (c);
}