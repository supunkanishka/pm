@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>SPRINTS</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('tasks.index') }}" title="Go back"> GO BACK</i> </a>
            </div>
        </div>
    </div>

    <br/>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('sprints.create') }}" title="Create a task"> ADD NEW SPRINT
                    </a>
            </div>
        </div>
    </div>
    <br/>
    

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th width="3%" width="5%" style="text-align: center;">ID</th>
            <th width="13%" width="5%" style="text-align: center;">Project</th>
            <th width="13%" width="5%" style="text-align: center;">Sprint</th>
            <th width="10%" width="5%" style="text-align: center;">Start Date</th>
            <th width="10%" width="5%" style="text-align: center;">End Date</th>
            <th width="15%" width="5%" style="text-align: center;">Action</th>
        </tr>

        @foreach ($sprints as $sprint)
            <tr style="font-size: 13px;">
                <td style="font-size: 10px">{{ $sprint->id }}</td>
                <td style="font-size: 10px">{{ $sprint->project->name }}</td>
                <td style="font-size: 10px">{{ $sprint->name }}</td>
                <td style="text-align: center;">{{ ($sprint->start_date) ?  Carbon\Carbon::parse($sprint->start_date)->format('Y-m-d')  : '-' }}</td>
                <td style="text-align: center;">{{ ($sprint->due_date) ?  Carbon\Carbon::parse($sprint->due_date)->format('Y-m-d')  : '-' }}</td>
                <td style="text-align: center;">
                    <form action="{{ route('sprints.destroy', $sprint->id) }}" method="POST">

                        

                        <a href="{{ route('sprints.edit', $sprint->id) }}">
                            Edit

                        </a>

                        @csrf
                        @method('DELETE')

                        <!-- <button type="submit" title="delete" style="border: none; background-color:transparent;color: red">
                            Delete

                        </button> -->
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

    {!! $sprints->appends($params)->links("pagination::bootstrap-4") !!}

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