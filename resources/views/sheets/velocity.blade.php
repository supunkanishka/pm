@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>TIME SHEET </h2>
            </div>
        </div>
    </div>

    <br/>
    <form action="{{ route('velocity') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th width="5%">USER</th>
                <th width="5%">PROJECT</th>
                <!-- <th width="5%">STATUS</th> -->
                <th width="5%">DATE</th>
                <th width="5%">END DATE</th>
                <th width="5%">ACTIONS</th>
            </tr>
            <tr>
                <td>
                    <select class="form-control" name="user_id" value="{{ old('user_id') }}">
                        <option value="0">Select User</option>
                        @foreach ($users as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $user_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td>
                <td>
                    <select class="form-control" name="project_id" value="{{ old('project_id') }}">
                        <option value="0">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $project_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td>
                <!-- <td>
                    <select class="form-control" name="status_id" value="{{ old('status_id') }}">
                        <option value="0">Select Status</option>
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $status_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td> -->
                <td>
                    <input  class="form-control datepicker" type="text" name="start_date" value="{{ $start_date }}" placeholder="Start Date">
                </td>
                <td>
                    <input  class="form-control datepicker" type="text" name="end_date" value="{{ $end_date }}" placeholder="End Date">
                </td>
                <td>
                    <button type="submit" title="seaarch" class="btn btn-success">
                            Search
                    </button>
                    <a class="btn btn-success" href="{{ url('/velocity?reset=true') }}">
                        Reset
                    </a>
                </td>
            </tr>
        </table>
    </form>
    

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">
        <tr>
            <td style="font-weight: bold;" colspan="3">VELOCITY : 
                @if($velocity < 0)
                <span style="color:red">{{ $velocity }}</span>
                @else
                <span style="color:green">+ {{ $velocity }}</span>
                @endif
            </td>
            <td style="font-weight: bold;" colspan="5">
                <span style="color:green">Good</span> = [-5 < x < +5] &nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red">Need to improve</span> = [ x < -5 ] &nbsp;&nbsp;<span style="color:red">Over Estimate</span> = [ x > +5 ]
            </td>
        </tr>
        <tr>
            <th width="7%" style="text-align: center;">Task Id</th>
            <th width="7%" style="text-align: center;">User</th>
            <th width="16%" style="text-align: center;">Project</th>
            <th width="30%" style="text-align: center;">Task</th>
            <th width="10%" style="text-align: center;">Created At</th>
            <th width="10%" style="text-align: center;">Estimated ({{ $estimated_hours }})</th>
            <th width="10%" style="text-align: center;">Spent ({{ $spent_hours }})</th>
            <th width="10%" style="text-align: center;">Punishment Hours ({{ $punishment_hours }})</th>
        </tr>
        @foreach ($tasks as $task)
            <tr style="font-size: 13px;">
                <td style="text-align: center;">{{ $task->task_id }}</td>
                <td style="text-align: center;">{{ $task->user_name }}</td>
                <td>{{ $task->project_name }}</td>
                <td>{{ $task->task_name }}</td>
                <td style="text-align: center;">{{ Carbon\Carbon::parse($task->created_at)->format('Y-m-d') }}</td>
                <td style="text-align: center;">{{ $task->estimated_hours }}</td>
                <!-- <td>{{ (float)$task->spent_hours }}</td> -->
                <td style="text-align: center;background-color: {{ ((float)$task->spent_hours > (float)$task->estimated_hours) ? '#ED2B33FF' : '#FFFFFF' }};color: {{ ((float)$task->spent_hours > (float)$task->estimated_hours) ? '#FFFFFF' : '#000000' }}">{{ (float)$task->spent_hours }}</td>
                <td style="text-align: center;">{{ $task->punishment_hours }}</td>
            </tr>
        @endforeach
        
    </table>

    {!! $tasks->appends($params)->links("pagination::bootstrap-4") !!}

@endsection