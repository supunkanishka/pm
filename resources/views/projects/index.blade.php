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
                <a class="btn btn-success" href="{{ route('projects.create') }}" title="Create a task"> ADD NEW PROJECT
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
            <th width="26%" width="5%" style="text-align: center;">Name</th>
            <th width="26%" width="5%" style="text-align: center;">Status</th>
            <th width="10%" width="5%" style="text-align: center;">Start Date</th>
            <th width="10%" width="5%" style="text-align: center;">End Date</th>
            <th width="15%" width="5%" style="text-align: center;">Action</th>
        </tr>

        @foreach ($projects as $project)
            <tr style="font-size: 13px;">
                <td style="font-size: 10px">{{ $project->id }}</td>
                <td style="font-size: 10px">{{ $project->name }}</td>
                <td style="text-align: center;">{{ ($project->active == 1) ? 'Active' : '-' }}</td>
                <td style="text-align: center;">{{ ($project->start_date) ?  Carbon\Carbon::parse($project->start_date)->format('Y-m-d')  : '-' }}</td>
                <td style="text-align: center;">{{ ($project->end_date) ?  Carbon\Carbon::parse($project->end_date)->format('Y-m-d')  : '-' }}</td>
                <td style="text-align: center;">
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST">

                        

                        <a href="{{ route('projects.edit', $project->id) }}">
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

    {!! $projects->appends($params)->links("pagination::bootstrap-4") !!}

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