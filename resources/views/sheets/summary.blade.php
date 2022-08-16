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
    <form action="{{ route('timesheets') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th width="5%">USER</th>
                <!-- <th width="5%">PROJECT</th>
                <th width="5%">STATUS</th> -->
                <th width="5%">DATE</th>
                <!-- <th width="5%">END DATE</th> -->
                <th width="5%">DISPLAY</th>
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
                    <input  class="form-control datepicker" type="text" name="active_date" value="{{ $active_date }}" placeholder="Date">
                </td>
                <!-- <td>
                    <input  class="form-control datepicker" type="text" name="end_date" value="{{ $end_date }}" placeholder="End Date">
                </td> -->
                <td>
                    <div class="form-group form-check">
                        <input type="checkbox" name="summary" value="true" class="form-check-input" id="summary" {{  ($summary == true ? 'checked' : '') }}>
                        <label class="form-check-label" for="summary">Summary</label>
                    </div>
                </td>
                <td>
                    <button type="submit" title="seaarch" class="btn btn-success">
                            Search
                    </button>
                    <a class="btn btn-success" href="{{ url('/timesheets?reset=true') }}">
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

    <table class="table table-bordered table-responsive-lg" style="width: 30%;margin-left: 30%;">
        <tr>
            <!-- <th width="15%" style="text-align: center;">Date</th> -->
            <th width="50%" style="text-align: center;">User</th>
            <th width="25%" style="text-align: center;">Hours</th>
            <th width="25%" style="text-align: center;">Dedicated</th>
        </tr>

        @foreach ($spents as $spent)
            <tr style="font-size: 13px;">
                <!-- <td align="center">{{ Carbon\Carbon::parse($spent->date)->format('l jS F Y') }}</td> -->
                

                <td style="text-align: center;background-color: {{ ($spent->bg_color) ? $spent->bg_color : '#FFFFFF' }};color: {{ ($spent->txt_color) ? $spent->txt_color : '#000000' }}">{{ ($spent->name) ? $spent->name : '-' }}</td>

                <td style="font-size: 13px;font-weight: bold;text-align: center;background-color: {{ ($spent->spent_hours <= 6) ? '#FFFFFF' : '#FFFFFF' }};color: {{ ($spent->spent_hours <= 6) ? '#B32608' : '#000000' }}">{{ ($spent->spent_hours) ? $spent->spent_hours : '0' }}</td>

                <td style="font-size: 13px;font-weight: bold;text-align: center;background-color: {{ ($spent->dedicated) ? '#FFFFFF' : '#FFFFFF' }};color: {{ ($spent->dedicated) ? '#B32608' : '#000000' }}">{{ ($spent->dedicated) ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach
    </table>

    {!! $spents->appends($params)->links() !!}

@endsection