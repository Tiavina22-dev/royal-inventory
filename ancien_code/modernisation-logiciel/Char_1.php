<html>
<head>
  <script src="js/anychart-base.min.js"></script>
  <script src="js/anychart-ui.min.js"></script>
  <script src="js/anychart-exports.min.js"></script>
  <link href="css/anychart-ui.min.css" type="text/css" rel="stylesheet">
  <link href="css/anychart-font.min.css" type="text/css" rel="stylesheet">
  <style type="text/css">

    html,
    body,
    #container {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
    }
  
</style>
</head>
<body>
  
  <div id="container"></div>
  

  <script>

    anychart.onDocumentReady(function () {
      // create cartesian chart
      var chart = anychart.cartesian();

      // create data set on our data
      var dataSet = anychart.data.set([
        ['Jan', 11.5, 9.3],
        ['Feb', 12, 10.5],
        ['Mar', 11.7, 11.2],
        ['Apr', 12.4, 11.2],
        ['May', 13.5, 12.7],
        ['Jun', 11.9, 13.1],
        ['Jul', 14.6, 12.2],
        ['Aug', 17.2, 12.2],
        ['Sep', 16.9, 10.1],
        ['Oct', 15.4, 14.5],
        ['Nov', 16.9, 14.5],
        ['Dec', 17.2, 15.5]
      ]);

      // map data for the first series,take value from first column of data set
      var firstSeriesData = dataSet.mapAs({ x: 0, value: 1 });
      // map data for the second series,take value from second column of data set
      var secondSeriesData = dataSet.mapAs({ x: 0, value: 2 });

      // create column series with mapping data
      var column = chart.column(firstSeriesData);
      column.labels().enabled(true).format('{%Value} Ar');


      // turn on chart animation
      chart.animation(true);
      // set chart title text settings
      chart.title('Combination of Column and Jump Line Chart');
      // set scale minimum
      chart.yScale().minimum(0);
      // set union tooltip
      chart
        .tooltip()
        .displayMode('union')
        // tooltips position and interactivity settings
        .positionMode('point')
        .unionFormat(function () {
          return (
            'Plain: ' +
            this.points[1].value +
            ' Ar' +
            '\nFact: ' +
            this.points[0].value +
            ' Ar'
          );
        });
      chart.interactivity().hoverMode('by-x');
      // set yAxis labels formatter
      chart.yAxis().labels().format('{%Value} Ar');
      // axes titles
      chart.xAxis(true);
      // set container id for the chart
      chart.container('container');
      // initiate chart drawing
      chart.draw();
    });
  
</script>
</body>
</html>