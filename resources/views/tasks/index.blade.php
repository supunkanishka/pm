@extends('layouts.app')

@section('content')
    <style type="text/css">
        .my-table th, .my-table td{
            padding: 0.25rem;
            vertical-align: middle !important;
        }

        .reset-date{
            cursor: pointer;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>TASKS </h2>
            </div>
            <!-- <div class="pull-right">
                <a class="btn btn-success" href="{{ route('tasks.create') }}" title="Create a task"> ADD NEW TASK
                    </a>
            </div> -->
        </div>
    </div>

    <br/>
    <form id="formId" action="{{ route('tasks.index') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />
        <input type="hidden" id="update_hours" name="update_hours" value="false" />

        <input type="hidden" id="selected_task_id" name="selected_task_id" value="wow" />

        <!-- popup_add_hours -->
        <div class="modal fade" id="popup_add_hours" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">
                    <div class="modal-title" id="selected_task_title">
                    Update Hours
                    <div class="spinner-grow text-danger" id="loader3" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                    </div>
                </h4>
              </div>
              <hr/>
              <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Date:</strong>
                                <input required autocomplete="off" class="form-control datepicker" type="text" name="popup_add_hours_date" placeholder="Date">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Spent Hours: (15mins = 0.25 / 30mins = 0.5 / 45mins = 0.75)</strong>
                                <input required type="text" autocomplete="off" value="" name="popup_add_hours_spent_hours" class="form-control" placeholder="Spent Hours">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" id="submit_update_hours" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end_popup_add_hours -->

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right">
                    <!-- <a class="btn btn-success" href="{{ route('tasks.create') }}" title="Create a task"> ADD NEW TASK
                        </a> -->
                    <a data-toggle="modal" title="Add this item" class="btn btn-success" href="#select-project-modal">ADD NEW TASK</a>
                    <!-- <a class="btn btn-success" href="{{ url('/tasks/create?project_id=5') }}" title="Create a task"> MY ALL TASKS
                    </a> -->
                        &nbsp;
                    
                    <!-- <a class="btn btn-success" href="{{ url('/tasks?allnotcompleted=true') }}" title="Create a task"> ALL NOT COMPLETED TASKS
                    </a>
                    &nbsp; -->
                    <!-- <a class="btn btn-success" href="{{ url('/tasks?todo=true') }}">
                        TO DO
                    </a>
                    &nbsp; -->
                    <!-- <a class="btn btn-success" href="{{ url('/tasks?reset=true') }}">
                        ALL TASKS
                    </a>
                    &nbsp; -->
                    <a class="btn btn-success" href="{{ route('projects.index') }}" title="Create a task"> PROJECTS
                        </a>
                    &nbsp;
                    <a class="btn btn-success" href="{{ route('sprints.index') }}" title="Create a task"> SPRINTS
                        </a>
                    &nbsp;
                    <a class="btn btn-danger" href="{{ url('/tasks?allmytasks=true') }}" title="Create a task"> MY ALL ACTIVE TASKS
                    </a>
                    &nbsp;
                    <a class="btn btn-warning" href="{{ route('track') }}" title="Create a task"> TIME SHEET
                        </a>
                    &nbsp;
                    <!-- <a class="btn btn-success" href="{{ route('fileupload') }}" title="Create a task"> IMPORT TASKS
                        </a>
                    &nbsp; -->
                    @if(Auth::user()->name == "Supun" || Auth::user()->name == "Sameesha")
                    <a data-toggle="modal" title="Add this item" class="btn btn-success" href="#file-upload-select-project-modal">UPLOAD TASK LIST</a>
                        &nbsp;
                    @endif
                </div>
            </div>
        </div>
        <br/>
        
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <td style="font-weight: bold;" colspan="7">TOTAL ESTIMATE : {{ $estimated_hours }}</td>
            </tr>
            <tr>
                <th width="15%">TEXT</th>
                <th width="15%">USER</th>
                <th width="15%">PROJECT</th>
                <th width="15%">SPRINT</th>
                <th width="15%">STATUS</th>
                <th width="15%">DATE <span class="reset-date btn btn-danger">Clear-Date</span></th>
                <th width="15%">ACTIONS</th>
            </tr>
            <tr>
                <td>
                    <input autocomplete="off" class="form-control" type="text" name="text" value="{{ $text }}" placeholder="Text">
                </td>
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
                <td>
                    <select class="form-control" id="project_id" name="project_id" value="{{ old('project_id') }}">
                        <option value="0">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $project_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td>
                <td>
                    <select class="form-control" id="sprint_id" name="sprint_id" value="{{ old('sprint_id') }}">
                        <option value="0">Select Sprint</option>
                        @foreach ($sprints as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $sprint_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td>
                <td>
                    <select class="form-control" id="status_id" name="status_id" value="{{ old('status_id') }}">
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
                    
                </td>
                <!-- <td>
                    <input  class="form-control datepicker" type="text" name="end_date" value="{{ $end_date }}" placeholder="End Date">
                </td> -->
                <td>
                    <button id="btnSubmit" type="submit" title="seaarch" class="btn btn-success">
                        SEARCH
                    </button>
                    <div class="spinner-grow text-danger" id="loader" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                </td>
            </tr>
        </table>
    </form>
    

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg my-table">
        <tr>
            <th width="3%" style="text-align: center;">ID</th>
            <th width="25%" style="text-align: center;">Name</th>
            <th width="5%" style="text-align: center;">Est</th>
            <th width="5%" style="text-align: center;">Spent</th>
            <th width="8%" style="text-align: center;">Project</th>
            <th width="9%" style="text-align: center;">Sprint</th>
            <th width="8%" style="text-align: center;">User</th>
            <th width="10%" style="text-align: center;">Status</th>
            <th width="7%" style="text-align: center;">Created</th>
            <th width="7%" style="text-align: center;">Due</th>
            <th width="18%" style="text-align: center;">Action</th>
        </tr>

        @foreach ($tasks as $task)
            <tr style="font-size: 13px;" id='rowid-{{ $task->id }}'>
                <td style="font-size: 10px;background-color: {{ ($task->bug==1) ? '#FFA500' : '#FFFFFF' }};">{{ $task->id }}</td>
                <td><span style="color:green;font-weight: bold;">{{ ($task->meeting == 1) ? 'Meeting : ' : '' }}</span>
                    
                    <a href="{{ route('tasks.show', $task->id) }}" title="show" target="_blank">
                            {{ $task->name }}
                    </a>
                </td>
                <td style="text-align: center;">{{ $task->estimated_hours }}</td>
                <td style="text-align: center;">{{ ($task->spents->count() != 0) ? $task->spents[0]->total_spent : '0' }}</td>
                <td align="center" style="font-size: 10px">{{ ($task->project) ? $task->project->name : '-' }}</td>
                <td align="center" style="font-size: 10px">{{ ($task->sprint) ? $task->sprint->name : '-' }}</td>
                <td style="text-align: center;background-color: {{ ($task->user) ? $task->user->bg_color : '#FFFFFF' }};color: {{ ($task->user) ? $task->user->txt_color : '#000000' }}">{{ ($task->user) ? $task->user->name : '-' }}</td>
                <!-- <td style="font-size: 10px;text-align: center; background-color: {{ ($task->status) ? $task->status->color : '#FFFFFF' }};">{{ ($task->status) ? $task->status->name : '-' }}</td> -->

                <td>
                    <select id="{{$task->id}}-taskid" style="background-color: {{ ($task->status) ? $task->status->color : '#FFFFFF' }};" data-popupupdatestatusid="{{ $task->id }}" class="form-control popup_update_status">
                        <option value="0">Select Status</option>
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $task->status_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td>
                
                <td style="text-align: center;">{{ ($task->created_at) ?  Carbon\Carbon::parse($task->created_at)->format('Y-m-d')  : '-' }}</td>
                <td style="text-align: center;">{{ ($task->due_date) ? $task->due_date : '-' }}</td>
                <td style="text-align: center;">
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">

                        <!-- <a href="{{ route('tasks.show', $task->id) }}" title="show" target="_blank">
                            Show
                        </a> -->

                        <a data-toggle="modal" data-description="{{ $task->description }}" data-title="{{ $task->name }}" title="Add this item" class="open-AddTextDialog" href="#myModal">View</a>

                        <a href="{{ route('tasks.edit', $task->id) }}">
                            Edit

                        </a>

                        <a data-toggle="modal" data-tasktitle="{{ $task->name }}" data-taskid="{{ $task->id }}" title="Add this item" class="open_popup_add_hours" href="#popup_add_hours">Hours</a>


                        @csrf
                        @method('DELETE')

                        <!-- <button type="submit" title="delete" style="border: none; background-color:transparent;color: red">
                            Delete

                        </button> -->
                        @if(Auth::user()->email == "supunr@insharptechnologies.com")
                        <button class="deletebutton" data-deletetaskid="{{ $task->id }}" type="button" title="delete" style="border: none; background-color:transparent;color: red">
                            Delete

                        </button>
                        @endif
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
            <div class="modal-title" id="modal-title"></div>
        </h4>
      </div>
      <hr/>
      <div class="modal-body" id="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Select Project -->
<div class="modal fade" id="select-project-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
            <div class="modal-title">
            Select Project
            <div class="spinner-grow text-danger" id="loader2" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            </div>
        </h4>
      </div>
      <hr/>
      <div class="modal-body">

        <form action="{{ route('tasks.create') }}" method="POST" >
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Project:</strong>
                    <select class="form-control" id="selected_project_id" name="selected_project_id" value="{{ old('project_id') }}">
                        <option value="">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ old('project_id') == $key ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- File Upload Select Project -->
<div class="modal fade" id="file-upload-select-project-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
            <div class="modal-title">
            Select Project
            <div class="spinner-grow text-danger" id="loader4" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            </div>
        </h4>
      </div>
      <hr/>
      <div class="modal-body">

        <form action="{{ route('tasks.create') }}" method="POST" >
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Project:</strong>
                    <select class="form-control" id="file_upload_selected_project_id" name="selected_project_id" value="{{ old('project_id') }}">
                        <option value="">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ old('project_id') == $key ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


{!! $tasks->appends($params)->links("pagination::bootstrap-4") !!}

@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#popup_add_hours_date').val('');
    $('#popup_add_hours_spent_hours').val();
    $('#update_hours').val(false);

    // $('.datepicker').datepicker({
    //      orientation: 'auto bottom'
    // });

    // console.log($('#popup_add_hours_date').val(''));
    // console.log($('#popup_add_hours_date').val('woo2'));
    //  console.log($('#popup_add_hours_date').val());
    // console.log($('#popup_add_hours_spent_hours').val());
    

    $('#loader').hide();
    $('#loader2').hide();
    $('#loader3').hide();
    $('#loader4').hide();

    $(document).on("click", "#submit_update_hours", function () {
        this.form.submit();
        $("#submit_update_hours").attr("disabled", true);
    });

    
    $(document).on("click", ".reset-date", function () {
        $('#active_date').val('');
        $('#loader').show();
        hideSearch();
        $("#formId").submit();
    });

    $(document).on("click", ".open_popup_add_hours", function () {
        $('#update_hours').val(true);
        var taskid = $(this).data('taskid');
        var tasktitle = $(this).data('tasktitle');
        $('#selected_task_id').val(taskid);

        $("#selected_task_title").html(tasktitle);
    });

    $(document).on("click", ".open-AddTextDialog", function () {
        var txtTitle = $(this).data('title');
        var txtDescription = $(this).data('description');
        $("#modal-title").html(txtTitle);
        $("#modal-body").html(txtDescription);
    });

    $('#project_id').on('change', function() {
        $('#loader').show();
        hideSearch();
        this.form.submit();
    });

    $('#sprint_id').on('change', function() {
        $('#loader').show();
        hideSearch();
        this.form.submit();
    });

    $('#user_id').on('change', function() {
        $('#loader').show();
        hideSearch();
        this.form.submit();
    });

    $('#status_id').on('change', function() {
        $('#loader').show();
        hideSearch();
        this.form.submit();
    });

    $('#selected_project_id').on('change', function() {
        $('#loader2').show();
        var selected_project_id = $('#selected_project_id').find(":selected").val();
        
        window.location = '/tasks/create?project_id=' + selected_project_id;
    });

    $('#file_upload_selected_project_id').on('change', function() {
        $('#loader4').show();
        var file_upload_selected_project_id = $('#file_upload_selected_project_id').find(":selected").val();
        
        window.location = '/fileupload?project_id=' + file_upload_selected_project_id;
    });

    function hideSearch(){
        $("#btnSubmit").hide();
    }

    $(document).on("click", "#btnSubmit", function () {
        this.form.submit();
    });

    $('.popup_update_status').on('change', function() {
        let _token   = $('meta[name="csrf-token"]').attr('content');

        var popup_update_task_id = $(this).data('popupupdatestatusid');

        var optionSelected = $("option:selected", this);
        var popup_update_status_id = this.value;

        switch(popup_update_status_id) {
          case "1":
            $('#'+popup_update_task_id+'-taskid').css('color', '#000');
            $('#'+popup_update_task_id+'-taskid').css('background-color', '#fff');
            break;
          case "2":
            $('#'+popup_update_task_id+'-taskid').css('color', '#fff');
            $('#'+popup_update_task_id+'-taskid').css('background-color', '#F95700FF');
            break;
          case "3":
            $('#'+popup_update_task_id+'-taskid').css('color', '#fff');
            $('#'+popup_update_task_id+'-taskid').css('background-color', '#FEE715FF');
            break;
          case "4":
            $('#'+popup_update_task_id+'-taskid').css('color', '#fff');
            $('#'+popup_update_task_id+'-taskid').css('background-color', '#17fc54');
            break;
          case "5":
            $('#'+popup_update_task_id+'-taskid').css('color', '#fff');
            $('#'+popup_update_task_id+'-taskid').css('background-color', '#0DAAD8');
            break;
          case "6":
            $('#'+popup_update_task_id+'-taskid').css('color', '#fff');
            $('#'+popup_update_task_id+'-taskid').css('background-color', '#FFA500');
            break;
          default:
            $('#'+popup_update_task_id+'-taskid').css('color', '#000');
            $('#'+popup_update_task_id+'-taskid').css('background-color', '#fff');
        }

        $.ajax({
        url: "/updatetaskstatus",
        type:"POST",
        data:{
          popup_update_task_id:popup_update_task_id,
          popup_update_status_id:popup_update_status_id,
          _token: _token
        },
        success:function(response){
          console.log(response);
          if(response) {
            $('.success').text(response.success);
          }
        },
       });

        // $.ajax({
        //     url: '/updatetaskstatus',
        //     type: 'POST',
        //     beforeSend: function (request) {
        //         return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
        //     },
        //     success: function(response){
        //         var blob = new Blob([response]);
        //         var link = document.createElement('a');
        //         link.href = window.URL.createObjectURL(blob);
        //         link.download = "Sample.pdf";
        //         link.click();
        //     }
        // })
    });

    $(document).on("click", ".deletebutton", function () {
        let _token   = $('meta[name="csrf-token"]').attr('content');

        var deletetaskid = $(this).data('deletetaskid');

        $('#rowid-'+deletetaskid).hide();

        $.ajax({
            url: "/deletetask",
            type:"POST",
            data:{
              deletetaskid:deletetaskid,
              _token: _token
            },
            success:function(response){
              console.log(response);
              if(response) {
                $('.success').text(response.success);
              }
            },
           });
    });

    $('select').on('change', function() {
      if ($(this).val()) {
    return $(this).css('color', 'black');
      } else {
    return $(this).css('color', 'red');
      }
    });
    
    
});
</script>
@stop