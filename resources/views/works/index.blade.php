@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>WORKS </h2>
            </div>
        </div>
    </div>

    <br/>
    <form action="{{ route('works.index') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('works.create') }}" title="Create a work"> ADD NEW WORK
                        </a>
                        &nbsp;
                    <a class="btn btn-success" href="{{ url('/works?allmyworks=true') }}" title="Create a task"> MY ALL WORKS
                    </a>
                    &nbsp;
                    <a class="btn btn-success" href="{{ url('/works?allnotcompleted=true') }}" title="Create a task"> ALL NOT COMPLETED WORKS
                    </a>
                    &nbsp;
                    <a class="btn btn-success" href="{{ url('/works?todo=true') }}">
                        TO DO
                    </a>
                    &nbsp;
                    <a class="btn btn-success" href="{{ url('/works?reset=true') }}">
                        ALL WORKS
                    </a>
                </div>
            </div>
        </div>
        <br/>
        
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th width="25%">TEXT</th>
                <th width="15%">USER</th>
                <th width="15%">PROJECT</th>
                <th width="15%">STATUS</th>
                <th width="15%">ACTIVE DATE</th>
                <th width="15%">ACTIONS</th>
            </tr>
            <tr>
                <td>
                    <input autocomplete="off" class="form-control" type="text" name="text" value="{{ $text }}" placeholder="Text">
                </td>
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
                <td>
                    <select class="form-control" name="status_id" value="{{ old('status_id') }}">
                        <option value="0">Select Status</option>
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $status_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td>
                <td>
                    <input id="active_date" autocomplete="off" class="form-control datepicker" type="text" name="active_date" value="{{ $active_date }}" placeholder="Active Date">
                    <!-- <button type="button" title="seaarch" class="btn btn-danger clear">
                            Clear
                    </button> -->
                </td>
                <!-- <td>
                    <input  class="form-control datepicker" type="text" name="end_date" value="{{ $end_date }}" placeholder="End Date">
                </td> -->
                <td>
                    <button type="submit" title="seaarch" class="btn btn-success">
                            Search
                    </button>
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
            <th width="3%" width="5%" style="text-align: center;">ID</th>
            <th width="26%" width="5%" style="text-align: center;">Name</th>
            <th width="14%" width="5%" style="text-align: center;">Project</th>
            <th width="8%" width="5%" style="text-align: center;">User</th>
            <th width="6%" width="5%" style="text-align: center;">Status</th>
            <th width="10%" width="5%" style="text-align: center;">Created Date</th>
            <th width="10%" width="5%" style="text-align: center;">Due Date</th>
            <th width="15%" width="5%" style="text-align: center;">Action</th>
        </tr>

        @foreach ($works as $work)
            <tr style="font-size: 13px;">
                <td style="font-size: 10px">{{ $work->id }}</td>
                <td>{{ $work->name }}</td>
                <td align="center" style="font-size: 10px">{{ ($work->project) ? $work->project->name : '-' }}</td>
                <td style="text-align: center;background-color: {{ ($work->user) ? $work->user->bg_color : '#FFFFFF' }};color: {{ ($work->user) ? $work->user->txt_color : '#000000' }}">{{ ($work->user) ? $work->user->name : '-' }}</td>
                <td style="font-size: 10px;text-align: center; background-color: {{ ($work->status) ? $work->status->color : '#FFFFFF' }};">{{ ($work->status) ? $work->status->name : '-' }}</td>
                
                <td style="text-align: center;">{{ ($work->created_at) ?  Carbon\Carbon::parse($work->created_at)->format('Y-m-d')  : '-' }}</td>
                <td style="text-align: center;">{{ ($work->due_date) ? $work->due_date : '-' }}</td>
                <td style="text-align: center;">
                    <form action="{{ route('works.destroy', $work->id) }}" method="POST">

                        <!-- <a href="{{ route('works.show', $work->id) }}" title="show" target="_blank">
                            Show
                        </a> -->

                        <a data-toggle="modal" data-description="{{ $work->description }}" data-title="{{ $work->name }}" title="Add this item" class="open-AddTextDialog" href="#myModal">View</a>

                        <a href="{{ route('works.edit', $work->id) }}" target="_blank">
                            Edit

                        </a>

                        

                        @csrf
                        @method('DELETE')

                        <button type="submit" title="delete" style="border: none; background-color:transparent;color: red">
                            Delete

                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
            <div class="modal-title"></div>
        </h4>
      </div>
      <hr/>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    {!! $works->appends($params)->links() !!}

@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $(document).on("click", ".open-AddTextDialog", function () {
        var txtTitle = $(this).data('title');
        var txtDescription = $(this).data('description');
        $(".modal-title").html(txtTitle);
        $(".modal-body").html(txtDescription);
    });
});
</script>
@stop