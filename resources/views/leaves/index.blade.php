@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>leaves</h2>
            </div>
        </div>
    </div>

    <br/>
    <form action="{{ route('leaves.index') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('leaves.create') }}" title="Create a leave"> ADD NEW LEAVE
                        </a>
                </div>
            </div>
        </div>
        <br/>
        
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th width="25%">User</th>
                <th width="25%">ACTIONS</th>
                <th width="25%"></th>
                <th width="25%"></th>
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
                <td>
                    <button id="btnSubmit" type="submit" title="seaarch" class="btn btn-success">
                            Search
                    </button>
                    <div class="spinner-grow text-danger" id="loader" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                </td>
                <td>
                    
                </td>
                <td>
                    
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
            <th width="15%" style="text-align: center;">USER</th>
            <th width="15%" style="text-align: center;">DATE</th>
            <th width="15%" style="text-align: center;">ANNUAL</th>
            <th width="15%" style="text-align: center;">CASUAL</th>
            <th width="15%" style="text-align: center;">SICK</th>
            <th width="25%" style="text-align: center;">Action</th>
        </tr>

        <?php 
        $annual = 0;
        $casual = 0;
        $sick = 0;
        ?>

        @foreach ($leaves as $leave)
        <?php
        if($leave->typemodel->name == 'Annual'){
            $annual = $annual + $leave->amount;
        }

        if($leave->typemodel->name == 'Casual'){
            $casual = $casual + $leave->amount;
        }

        if($leave->typemodel->name == 'Sick'){
            $sick = $sick + $leave->amount;
        }
        
        ?>
            <tr style="font-size: 13px;">
                <td style="text-align: center;background-color: {{ ($leave->user) ? $leave->user->bg_color : '#FFFFFF' }};color: {{ ($leave->user) ? $leave->user->txt_color : '#000000' }}">{{ ($leave->user) ? $leave->user->name : '-' }}</td>
                <td style="text-align: center;">{{ ($leave->date) ?  Carbon\Carbon::parse($leave->date)->format('Y-m-d')  : '-' }}</td>
                <td style="text-align: center;">{{ ($leave->typemodel->name == 'Annual') ? $leave->amount : '-' }}</td>
                <td style="text-align: center;">{{ ($leave->typemodel->name == 'Casual') ? $leave->amount : '-' }}</td>
                <td style="text-align: center;">{{ ($leave->typemodel->name == 'Sick') ? $leave->amount : '-' }}</td>
                
                
                <td style="text-align: center;">
                    <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST">


                        <a data-toggle="modal" data-description="{{ $leave->description }}" data-title="{{ Carbon\Carbon::parse($leave->date)->format('Y-m-d') }}" title="Add this item" class="open-AddTextDialog" href="#myModal">View</a>

                        

                        <a href="{{ route('leaves.edit', $leave->id) }}">
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
        <tr style="font-size: 13px;">
                <td></td>
                <td></td>
                <td style="text-align: center;font-weight: bold;">{{ $annual }}</td>
                <td style="text-align: center;font-weight: bold;">{{ $casual }}</td>
                <td style="text-align: center;font-weight: bold;">{{ $sick }}</td>
                <td></td>
            </tr>
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

    

@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#loader').hide();

    function hideSearch(){
        $("#btnSubmit").hide();
    }

    $('#user_id').on('change', function() {
        $('#loader').show();
        hideSearch();
        this.form.submit();
    });

    $(document).on("click", ".open-AddTextDialog", function () {
        var txtTitle = $(this).data('title');
        var txtDescription = $(this).data('description');
        $(".modal-title").html(txtTitle);
        $(".modal-body").html(txtDescription);
    });
});
</script>
@stop