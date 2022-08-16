@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>TIME SHEET </h2>
            </div>
        </div>
    </div>

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
    <form action="{{ route('track') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th width="5%">USER</th>
                <th width="5%">PROJECT</th>
                <!-- <th width="5%">STATUS</th> -->
                <th width="5%">START DATE</th>
                <!-- <th width="5%">END DATE</th> -->
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
                    <input  class="form-control datepicker" type="text" name="active_date" value="{{ $active_date }}" placeholder="Date" autocomplete="off">
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
                    <a class="btn btn-success" href="{{ url('/track?reset=true') }}">
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
            <td style="font-weight: bold;" colspan="7">TOTAL HOURS : {{ $total_hours }}</td>
        </tr>
        <tr>
            <th width="10%" style="text-align: center;">User</th>
            <th width="10%" style="text-align: center;">Task Id</th>
            <th width="10%" style="text-align: center;">Project</th>
            <th width="50%" style="text-align: center;">Task</th>
            <th width="5%" style="text-align: center;">Est</th>
            <th width="10%" style="text-align: center;">date</th>
            <th width="5%" style="text-align: center;">Spent</th>
        </tr>
        @foreach ($developers as $developer)
            <tr style="font-size: 13px;">
                <td style="text-align: center;background-color: {{ ($developer->bg_color) ? $developer->bg_color : '#FFFFFF' }};color: {{ ($developer->txt_color) ? $developer->txt_color : '#000000' }}">{{ $developer->user_name }}</td>
                <td>{{ $developer->task_id}}</td>
                <td align="center" style="font-size: 10px">{{ $developer->project_name}}</td>
                <td>{{ $developer->task_name}}</td>
                <td>{{ $developer->estimated_hours}}</td>
                <td>{{ $developer->date}}</td>
                <td>{{ $developer->spent_hours}}</td>
            </tr>
        @endforeach
        
    </table>

    {!! $developers->appends($params)->links("pagination::bootstrap-4") !!}

@endsection