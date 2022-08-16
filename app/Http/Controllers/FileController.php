<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TasksExport;
use App\Imports\TasksImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Status;
use Carbon\Carbon;
use App\Models\Spent;
use App\Models\Sprint;
use Auth;

class FileController extends Controller
{
    public function fileupload(Request $request)
    {
    	$projects = Project::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');
        $sprints = Sprint::where('project_id',$request->project_id)->pluck('name', 'id');

        $authUser = Auth::user();

        $selectedProjectId = $request->project_id;
        $selectedUserId = $authUser->id;
        $selectedStatusId = 1;

        $active_date = Carbon::now()->format('d/m/Y');

        return view('files.import',compact('selectedProjectId','projects','sprints','users','selectedUserId','statuses','selectedStatusId','active_date'));
        //return view('files.import');
    }

    public function export() 
    {
        return Excel::download(new TasksExport, 'tasks.xlsx');
    }

    public function import(Request $request) 
    {
    	//dd($request);
    	//insert model
    	// Excel::import(new TasksImport,request()->file('file'));
        //return back();
    	//return response()->json(["rows"=>$rows]);


    	if($request->file('file') != null){
    		$rows = Excel::toArray(new TasksImport, $request->file('file'));
    	
	    	$count = 0;
	    	foreach ($rows[0] as $row) {
	    		if($count != 0){
	    			if($row[0] != null && $row[0] != ""){
	    				$task = new Task();
				        $task->name = $row[0].' : '.$row[1];
				        $task->description = $row[2];
				        $task->estimated_hours = $row[3];
				        $task->project_id = $request->get('project_id');
				        $task->sprint_id = $request->get('sprint_id');
				        $task->user_id = $request->get('user_id');
				        $task->status_id = $request->get('status_id');

				        if($request->get('start_date') != null && $request->get('start_date') != ""){
				            $task->start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'));
				        }
				        
				        if($request->get('due_date') != null && $request->get('due_date') != ""){
				            $task->due_date = Carbon::createFromFormat('d/m/Y', $request->get('due_date'));
				        }

				        if($request->get('active_date') != null && $request->get('active_date') != ""){
				            $task->active_date = Carbon::createFromFormat('d/m/Y', $request->get('active_date'));
				        }

				        $task->save();
	    			}
	    		}

	    		$count++;
	    	}
    	}
    	

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
        
    }

    public function downloadfile(Request $request) {
        $fileName = "samaple_task_list.xlsx";
        $filePath = storage_path($fileName);
        $headers = ['Content-Type: application/vnd.ms-excel'];

        return response()->download($filePath, $fileName, $headers);


        // Get path from storage directory
        // $filename = "samaple_task_list.xlsx";
        // $path = storage_path('data/' . $filename);

        // // Download file with custom headers
        // return response()->download($path, $filename, [
        //     'Content-Type' => 'application/vnd.ms-excel',
        //     'Content-Disposition' => 'inline; filename="' . $filename . '"'
        // ]);
    }
}
