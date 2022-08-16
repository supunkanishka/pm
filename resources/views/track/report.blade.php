@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            <a class="btn btn-success" href="{{ url('/track') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                All Tasks
            </a>
            &nbsp;
            <a class="btn btn-success" href="{{ url('/tracktotal') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                Summary
            </a>
            &nbsp;
            <a class="btn btn-success" href="{{ url('/report') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                Report
            </a>
        </div>
    </div>
</div>
<br/>

<br/>
    <form action="{{ route('report') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th width="5%">USER</th>
                <!-- <th width="5%">PROJECT</th> -->
                <!-- <th width="5%">STATUS</th> -->
                <th width="5%">DATE</th>
                <!-- <th width="5%">END DATE</th> -->
                <th width="5%"></th>
                <th width="5%"></th>
            </tr>
            <tr>
                <td>
                    <select class="form-control" id="user_id" name="user_id" value="{{ old('user_id') }}">
                        <option value="0">Select User</option>
                        @foreach ($users as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $user_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td>
                <!-- <td>
                    <select class="form-control" name="project_id" value="{{ old('project_id') }}">
                        <option value="0">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $project_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td> -->
                <td>
                    <input  class="form-control datepicker" type="text" name="start_date" value="{{ $start_date }}" placeholder="Date" autocomplete="off">
                </td>
                <td>
                    <input  class="form-control datepicker" type="text" name="end_date" value="{{ $end_date }}" placeholder="Date" autocomplete="off">
                </td>
                <td>
                    <button type="submit" title="seaarch" class="btn btn-success">
                            Search
                    </button>
                    <div class="spinner-grow text-danger" id="loader" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                    <!-- <a class="btn btn-success" href="{{ url('/report') }}">
                        Reset
                    </a> -->
                </td>
            </tr>
        </table>
    </form>
    
<h2 class="text-center">Weekly Report</h2>

<!-- <div id="chartDiv" class="pie-chart"></div> -->


<div id="chart_div" style="width: 100%; height: 500px;"></div>


      

@endsection

@section('scripts')
<script type="text/javascript">

    $('#loader').hide();

    //chart 1
    //var subjectData = @json($subjectData);
    // window.onload = function() {
    //     google.load("visualization", "1.1", {
    //         packages: ["corechart"],
    //         callback: 'drawChart'
    //     });
    // };
  
    // function drawChart() {
    //     var data = new google.visualization.DataTable();
    //     data.addColumn('string', 'Pizza');
    //     data.addColumn('number', 'Populartiy');
    //     data.addRows(subjectData);
  
    //     var options = {
    //         pieHole: 0.4,
    //         title: 'Popularity of Types of Framework',
    //     };
  
    //     var chart = new google.visualization.PieChart(document.getElementById('chartDiv'));
    //     chart.draw(data, options);
    // }

    //chart2
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic() {

          var data = new google.visualization.DataTable();
          data.addColumn('timeofday', 'Time of Day');
          data.addColumn('number', 'Motivation Level');

        var data=[];
         var Header= ['Date', 'Hours', { role: 'style' }];
         data.push(Header);

        var developers = @json($developers);

        console.log(developers);

        for (i = 0; i < developers.length; i++) {
          var spent_hours = 10;

        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        var color = "rgb(" + r + "," + g + "," + b + ")";
          
          data.push([developers[i].date, parseFloat(developers[i].spent_hours), 'color: '+color+'']);
        } 

         
         // data.push(['Moday', 10, 'color: gray']);
         // data.push(['Tuesday', 14, 'color: #76A7FA']);
         // data.push(['Wednesday', 16, 'color: gray']);
         
         var chartdata = new google.visualization.arrayToDataTable(data);

          var options = {
            title: developers[0].user_name,
            hAxis: {
              title: 'Day',
            },
            vAxis: {
              title: 'Hours',
              minValue: 0,
              ticks: [0, 1, 2, 3, 4,5,6,7,8,9,10,11,12],
            }
          };

          var chart = new google.visualization.ColumnChart(
            document.getElementById('chart_div'));
            if(developers.length>0){
                chart.draw(chartdata, options);
            }
            
        }

        $('#user_id').on('change', function() {
            $('#loader').show();
            this.form.submit();
        });

</script>
@endsection