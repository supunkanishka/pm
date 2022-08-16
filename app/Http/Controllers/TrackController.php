<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Status;
use App\Models\Spent;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;

class TrackController extends Controller
{
    public function report(Request $request)
    {
        // $users = User::get();
        $subjectData = [];

        // foreach ($users as $user) {
        //     $subjectData[] = [$user->name , 5];
        // }

        $user_id = $request->user_id;
        $project_id = $request->project_id;
        $status_id = $request->status_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $active_date = $request->active_date;
        $summary = $request->summary;
        $total_hours = 0;

        if($request->user_id == null){
            //$authUser = Auth::user();
            $user_id = Auth::user()->id;
        }else{
            $user_id = $request->user_id;
        }

        $db_start_date = null;

        if($request->start_date != null && $request->start_date != ""){
            $db_start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
        }else{
            $start_date = Carbon::now()->startOfWeek()->format('d/m/Y');
            $db_start_date = Carbon::createFromFormat('d/m/Y', $start_date);
        }

        $db_end_date = null;

        if($request->end_date != null && $request->end_date != ""){
            $db_end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);
        }else{
            $end_date = Carbon::now()->startOfWeek()->addDays(4)->format('d/m/Y');
            $db_end_date = Carbon::createFromFormat('d/m/Y', $end_date);
        }

        // $now = Carbon::now();
        // $weekStartDate = $now->startOfWeek();
        // $weekEndDate = $now->endOfWeek();



        $query = User::select([
            'users.name as user_name',
            'users.dedicated as dedicated',
            'users.bg_color as bg_color',
            'users.txt_color',
            'spents.date as date',
            DB::raw("SUM(spents.spent_hours) as spent_hours")])
            ->leftJoin('tasks', function($j) use ($db_start_date,$db_end_date){ 
                return $j->on('tasks.user_id','=','users.id')
                    ->join('projects', 'tasks.project_id', '=', 'projects.id')
                    ->leftJoin('spents', 'spents.task_id', '=', 'tasks.id')
                    //->where('spents.date',$db_active_date->toDateString())
                    // ->whereDate('spents.date','<=',Carbon::now()->endOfWeek()->toDateString())
                    // ->whereDate('spents.date','>=',Carbon::now()->startOfWeek()->toDateString())
                    // ->whereDate('spents.date','<=',$db_end_date)
                    // ->whereDate('spents.date','>=',$db_start_date)
                    ->whereDate('spents.date','>=',$db_start_date->toDateString())
                    ->whereDate('spents.date','<=',$db_end_date->toDateString())
                    ->groupBy('spents.task_id');
            })
        ->where('users.active',1)
        ->where('users.role','User')
        ->groupBy('spents.date');

        if($user_id != null && $user_id != 0 && $user_id != ""){
            $query = $query->where('users.id',$user_id);
        }

        // if($project_id != null && $project_id != 0 && $project_id != ""){
        //     $query = $query->where('projects.id',$project_id);
        // }

        $query = $query->orderBy('spents.date', 'asc');

        $users = User::where('active','1')
        ->where('role','User')
        ->orderBy('name', 'asc')->pluck('name', 'id');
        $projects = Project::where('active',1)->orderBy('name', 'asc')->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        $total_hours = $query->sum('spents.spent_hours');

        $developers = $query->get();

        

        $params = array('reset' => $request->reset,'user_id' => $user_id,'project_id' => $project_id,'active_date' => $active_date);

        
        //return view('track.report',compact('subjectData','data','developers'));
        // return view('track.report', compact('subjectData','data','developers','projects','statuses','users','user_id','project_id','status_id','start_date','end_date','active_date','params','summary','total_hours'))
        //     ->with('i', (request()->input('page', 1) - 1) * 5);
        return view('track.report', compact('subjectData','developers','projects','statuses','users','user_id','project_id','status_id','start_date','end_date','active_date','params','summary','total_hours'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

	public function tracktotal(Request $request)
    {
    	// $active_date = Carbon::now()->format('d/m/Y');
    	// $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);

    	$user_id = $request->user_id;
        $project_id = $request->project_id;
        $status_id = $request->status_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $active_date = $request->active_date;
        $summary = $request->summary;
        $total_hours = 0;

        $db_active_date = null;

        if($request->active_date != null && $request->active_date != ""){
            $db_active_date = Carbon::createFromFormat('d/m/Y', $request->active_date);
        }else{
        	$active_date = Carbon::now()->format('d/m/Y');
    	 	$db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
        }

        $query = User::select([
        	'users.name as user_name',
        	'users.dedicated as dedicated',
        	'users.bg_color',
        	'users.txt_color',
        	'spents.date as date',
        	DB::raw("SUM(spents.spent_hours) as spent_hours")])
			->leftJoin('tasks', function($j) use ($db_active_date){ 
        		return $j->on('tasks.user_id','=','users.id')
        			->join('projects', 'tasks.project_id', '=', 'projects.id')
                	->leftJoin('spents', 'spents.task_id', '=', 'tasks.id')
                	->where('spents.date',$db_active_date->toDateString())
                	->groupBy('spents.task_id');
        	})
        ->where('users.active',1)
        ->where('users.role','User')
        ->groupBy('users.id');

        $query = $query->orderBy('spent_hours', 'desc');

        $users = User::where('active','1')
        ->where('role','User')
        ->orderBy('name', 'asc')->pluck('name', 'id');
        $projects = Project::where('active',1)->orderBy('name', 'asc')->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        $total_hours = $query->sum('spents.spent_hours');

        $developers = $query->paginate(50);

        $params = array('reset' => $request->reset,'user_id' => $user_id,'project_id' => $project_id,'active_date' => $active_date);

        return view('track.tracktotal', compact('developers','projects','statuses','users','user_id','project_id','status_id','start_date','end_date','active_date','params','summary','total_hours'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function track(Request $request)
    {
    	// $active_date = Carbon::now()->format('d/m/Y');
    	// $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);

    	$user_id = $request->user_id;
        $project_id = $request->project_id;
        $status_id = $request->status_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $active_date = $request->active_date;
        $summary = $request->summary;
        $total_hours = 0;

       //  $db_active_date = null;

       //  if($request->active_date != null && $request->active_date != ""){
       //      $db_active_date = Carbon::createFromFormat('d/m/Y', $request->active_date);
       //  }else{
       //  	$active_date = Carbon::now()->format('d/m/Y');
    	 	// $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
       //  }

        $db_start_date = null;

        if($request->start_date != null && $request->start_date != ""){
            $db_start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
        }else{
            $start_date = Carbon::now()->format('d/m/Y');
            $db_start_date = Carbon::createFromFormat('d/m/Y', $start_date);
        }

        $db_end_date = null;

        if($request->end_date != null && $request->end_date != ""){
            $db_end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);
        }else{
            $end_date = Carbon::now()->format('d/m/Y');
            $db_end_date = Carbon::createFromFormat('d/m/Y', $end_date);
        }

        $query = User::select(['users.name as user_name',
        	'users.bg_color',
        	'users.txt_color',
        	'projects.name as project_name',
        	'tasks.name as task_name',
        	'tasks.id as task_id',
        	'tasks.estimated_hours as estimated_hours',
        	'spents.date as date',
        	'spents.spent_hours as spent_hours',
        	//DB::raw("SUM(spents.spent_hours) as spent_hours")
        	])
			->leftJoin('tasks', function($j) use ($db_start_date,$db_end_date){ 
        		return $j->on('tasks.user_id','=','users.id')
        			->join('projects', 'tasks.project_id', '=', 'projects.id')
                	->leftJoin('spents', 'spents.task_id', '=', 'tasks.id')
                	//->where('spents.date',$db_active_date->toDateString())
                    ->whereDate('spents.date','>=',$db_start_date->toDateString())
                    ->whereDate('spents.date','<=',$db_end_date->toDateString())
                    ;
        	})
        ->where('users.active',1)
        ->where('users.role','User')
        //->where('tasks.status_id',4)
        //->groupBy('tasks.id')
        ;

        if($user_id != null && $user_id != 0 && $user_id != ""){
            $query = $query->where('users.id',$user_id);
        }

        if($project_id != null && $project_id != 0 && $project_id != ""){
            $query = $query->where('projects.id',$project_id);
        }

        // $users = User::orderBy('name', 'asc')->pluck('name', 'id');
        $users = User::where('active','1')
        ->where('role','User')
        ->orderBy('name', 'asc')->pluck('name', 'id');
        $projects = Project::where('active',1)->orderBy('name', 'asc')->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        $total_hours = $query->sum('spents.spent_hours');

        $developers = $query->paginate(100);

        $params = array('reset' => $request->reset,'user_id' => $user_id,'project_id' => $project_id,'active_date' => $active_date);

        return view('track.index', compact('developers','projects','statuses','users','user_id','project_id','status_id','start_date','end_date','active_date','params','summary','total_hours'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
