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
                <h2>Users </h2>
            </div>
        </div>
    </div>

    <br/>
    <form id="formId" action="{{ route('users.index') }}" method="GET">
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
                                <input required type="text" value="" name="popup_add_hours_spent_hours" class="form-control" placeholder="Spent Hours">
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
                    <a class="btn btn-success" href="{{ route('users.create') }}">ADD NEW USER</a>&nbsp; | &nbsp;
                    <a class="btn btn-success" href="{{ route('teams.index') }}">TEAMS</a>
                </div>
            </div>
        </div>
        <br/>
        
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th width="15%">TEAM - {{$objects->total()}}</th>
                <th width="15%">STATUS</th>
                <th width="15%">ACTIONS</th>
            </tr>
            <tr>
                <td>
                    <select class="form-control" id="team_id" name="team_id" value="{{ old('team_id') }}">
                        <option value="0">Select Team</option>
                        @foreach ($teams as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $team_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </td>
                <td>
                    <select class="form-control" id="active" name="active" value="{{ old('active') }}">
                        <option value="all">Select Status</option>
                        <option value="1" {{ ( $active == "1" ) ? 'selected' : '' }}> 
                                Active
                            </option>
                        <option value="0" {{ ( $active == "0" ) ? 'selected' : '' }}> 
                                In Active
                            </option>
                    </select>
                </td>
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
            <th width="5%" style="text-align: center;">ID</th>
            <th width="20%" style="text-align: center;">Name</th>
            <th width="15%" style="text-align: center;">Email</th>
            <th width="10%" style="text-align: center;">Phone</th>
            <th width="10%" style="text-align: center;">Role</th>
            <th width="10%" style="text-align: center;">Team</th>
            <th width="10%" style="text-align: center;">active</th>
            <th width="10%" style="text-align: center;">dedicated</th>
            <th width="10%" style="text-align: center;">Action</th>
        </tr>

        @foreach ($objects as $object)
            <tr style="font-size: 13px;">
                <td style="font-size: 10px;text-align: center;">{{ $object->id }}</td>
                <td style="text-align: center;background-color: {{ ($object) ? $object->bg_color : '#FFFFFF' }};color: {{ ($object) ? $object->txt_color : '#000000' }}">{{ ($object->name) ? $object->name : '-' }}</td>
                <td style="font-size: 10px;text-align: center;">{{ $object->email }}</td>
                <td style="font-size: 10px;text-align: center;">{{ $object->phone }}</td>

                <td style="text-align: center;background-color: {{ ($object->team) ? $object->team->bg_color : '#FFFFFF' }};color: {{ ($object->team) ? $object->team->txt_color : '#000000' }}">{{ ($object->team) ? $object->team->name : '-' }}
                @if($object->team && ($object->team->leader_id == $object->id))
                    <span><b>(Leader)</b></span>
                    @endif
                </td>
                
                <td style="font-size: 10px;text-align: center;">{{ $object->role }}</td>
                

                

                <!-- <td style="font-size: 10px;text-align: center;">
                    @if($object->team && ($object->team->leader_id == $object->id))
                    <span style="color: green;"><b>Yes</b></span>
                    @else
                    No
                    @endif
                </td> -->

                
                <td style="font-size: 10px;text-align: center;">
                    {{($object->active == 1) ? 'Yes' : 'No'}}
                </td>


                <td style="font-size: 10px;text-align: center;">
                    {{($object->dedicated == 1) ? 'Yes' : 'No'}}
                </td>

                
                <td style="text-align: center;">
                    <form action="{{ route('users.destroy', $object->id) }}" method="POST">

                        <a href="{{ route('users.edit', $object->id) }}">
                            Edit

                        </a>


                        @csrf
                        @method('DELETE')

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
            <div class="spinner-grow text-danger" id="loader3" role="status">
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


{!! $objects->appends($params)->links("pagination::bootstrap-4") !!}

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

    $('#team_id').on('change', function() {
        $('#loader').show();
        hideSearch();
        this.form.submit();
    });

    $('#active').on('change', function() {
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
        $('#loader3').show();
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