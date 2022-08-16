@extends('layouts.app')

@section('content')
    
    <h2 class="text-center">Weekly Report</h2>
  
    <div id="chartDiv" class="pie-chart"></div>
      

@endsection

@section('scripts')
<script type="text/javascript">
    var subjectData = @json($subjectData);
    window.onload = function() {
        google.load("visualization", "1.1", {
            packages: ["corechart"],
            callback: 'drawChart'
        });
    };
  
    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Pizza');
        data.addColumn('number', 'Populartiy');
        data.addRows(subjectData);
  
        var options = {
            pieHole: 0.4,
            title: 'Popularity of Types of Framework',
        };
  
        var chart = new google.visualization.PieChart(document.getElementById('chartDiv'));
        chart.draw(data, options);
    }

</script>
@endsection