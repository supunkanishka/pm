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

    <form action="{{ route('tracktotal') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />
        <table class="table table-bordered table-responsive-lg" style="width: 50%;margin-left: 30%;">
            <tr>
                <!-- <th width="5%">USER</th> -->
                <!-- <th width="5%">PROJECT</th> -->
                <th width="5%">DATE</th>
                <!-- <th width="5%">DISPLAY</th> -->
                <th width="5%">ACTIONS</th>
            </tr>
            <tr>
                <!-- <td>
                    <select class="form-control" name="user_id" value="{{ old('user_id') }}">
                        <option value="0">Select User</option>
                        @foreach ($users as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $user_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td> -->
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
                    <input  class="form-control datepicker" type="text" name="active_date" value="{{ $active_date }}" placeholder="Date" autocomplete="off">
                </td>
                <td>
                    <button type="submit" title="seaarch" class="btn btn-success">
                            Search
                    </button>
                    <!-- <a class="btn btn-success" href="{{ url('/timesheets?reset=true') }}">
                        Reset
                    </a> -->
                </td>
            </tr>
        </table>
    </form>
    

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg" style="width: 50%;margin-left: 30%;">
        <!-- <tr>
            <td style="font-weight: bold;" colspan="7">TOTAL HOURS : {{ $total_hours }}</td>
        </tr> -->
        <tr>
            <th width="40%" style="text-align: center;">User</th>
            <th width="20%" style="text-align: center;">date</th>
            <th width="20%" style="text-align: center;">Spent</th>
            <th width="20%" style="text-align: center;">Dedicated</th>
        </tr>
        @foreach ($developers as $developer)
            <tr style="font-size: 13px;">
                <td style="text-align: center;background-color: {{ ($developer->bg_color) ? $developer->bg_color : '#FFFFFF' }};color: {{ ($developer->txt_color) ? $developer->txt_color : '#000000' }}">{{ $developer->user_name }}</td>
                <td style="text-align: center;">{{ $developer->date}}</td>
                <!-- <td style="text-align: center;">{{ $developer->spent_hours}}</td> -->
                <td style="font-size: 13px;font-weight: bold;text-align: center;background-color: {{ ($developer->spent_hours <= 6) ? '#FFFFFF' : '#FFFFFF' }};color: {{ ($developer->spent_hours <= 6) ? '#B32608' : '#000000' }}">{{ ($developer->spent_hours) ? $developer->spent_hours : '0' }}</td>
                <td style="font-size: 13px;font-weight: bold;text-align: center;background-color: {{ ($developer->dedicated) ? '#FFFFFF' : '#FFFFFF' }};color: {{ ($developer->dedicated) ? '#B32608' : '#000000' }}">{{ ($developer->dedicated) ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach
        
    </table>

    {!! $developers->appends($params)->links("pagination::bootstrap-4") !!}

@endsection