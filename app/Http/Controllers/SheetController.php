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

class SheetController extends Controller
{

    public function report($value='')
    {
        $users = User::get();
        foreach ($users as $user) {
                $subjectData[] = [$user->name , 5];
            }
        return view('sheets.report',compact('subjectData'));
    }

    public function velocity(Request $request)
    {
        $view = 'sheets.velocity';
        $users = User::orderBy('name', 'asc')->pluck('name', 'id');
        $projects = Project::orderBy('name', 'asc')->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        if($request->user_id == null){
            //$authUser = Auth::user();
            $user_id = Auth::user()->id;
        }else{
            $user_id = $request->user_id;
        }
        
        $project_id = $request->project_id;
        $status_id = $request->status_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $active_date = $request->active_date;
        $summary = $request->summary;
        $total_hours = 0;




        if($request->reset == null || $request->reset == false){
            if($start_date == null || $start_date == ""){
                if($request->start_date == null){
                    $start_date = Carbon::now()->startOfYear()->format('d/m/Y');
                }else{
                    $start_date = $request->start_date;
                }
            }

            if($end_date == null || $end_date == ""){
                if($request->end_date == null){
                    $end_date = Carbon::now()->format('d/m/Y');
                }else{
                    $end_date = $request->end_date;
                }
            }
        }

        $statusCompleted = Status::where('name','Completed')->first();

        $query = Task::select(['users.name as user_name','projects.name as project_name','tasks.id as task_id','tasks.created_at as created_at','tasks.name as task_name','tasks.estimated_hours as estimated_hours',DB::raw("SUM(spents.spent_hours) as spent_hours")])
                 ->leftJoin('spents','tasks.id','=','spents.task_id')
                 ->leftJoin('projects','tasks.project_id','=','projects.id')
                 ->leftJoin('users','tasks.user_id','=','users.id')
                 ->where('tasks.status_id',$statusCompleted->id)
                 ->where('tasks.meeting',0)
                 ->groupBy('tasks.id')
                 ->orderBy('tasks.created_at', 'desc');;

        if($user_id != null && $user_id != 0 && $user_id != ""){
            $query = $query->where('users.id',$user_id);
        }

        if($project_id != null && $project_id != 0 && $project_id != ""){
            $query = $query->where('tasks.project_id',$project_id);
        }

        if(($start_date != null && $start_date != "") && ($end_date != null && $end_date != "")){
            $db_start_date = Carbon::createFromFormat('d/m/Y', $start_date);
            $db_end_date = Carbon::createFromFormat('d/m/Y', $end_date);

            $query = $query
            ->where('tasks.created_at', '>=', $db_start_date)
            ->where('tasks.created_at', '<=', $db_end_date);

            $start_date = $db_start_date->format('d/m/Y');
            $end_date = $db_end_date->format('d/m/Y');
        }

        //dd($query->first()->punishment_hours);
        $result = $query->get();
        $estimated_hours = $result->sum('estimated_hours');
        $spent_hours = $result->sum('spent_hours');
        $punishment_hours = $result->sum('punishment_hours');

        $estimate = $estimated_hours;
        $spent = $spent_hours + $punishment_hours;

        $velocity = 'N/A';

        if($estimate != 0){
            $velocity = (($estimate - $spent) / $estimate) * 100;
        }
        

        //$spents = $query->latest()->paginate(50);
        $tasks = $query->paginate(50);

        $params = array('reset' => $request->reset,'user_id' => $user_id,'project_id' => $project_id,'active_date' => $active_date);

        return view($view, compact('tasks','projects','statuses','users','user_id','project_id','status_id','start_date','end_date','active_date','params','summary','estimated_hours','spent_hours','punishment_hours','velocity'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function timesheets(Request $request)
    {
        $view = 'sheets.index';
        $users = User::orderBy('name', 'asc')->pluck('name', 'id');
        $projects = Project::orderBy('name', 'asc')->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        $user_id = $request->user_id;
        $project_id = $request->project_id;
        $status_id = $request->status_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $active_date = $request->active_date;
        $summary = $request->summary;
        $total_hours = 0;


        if($request->reset == null || $request->reset == false){
            if($active_date == null || $active_date == ""){
                $active_date = Carbon::now()->format('d/m/Y');
            }
        }

        if($summary != null && $summary == true){
            $view = 'sheets.summary';

            //latest code
            $query = Spent::select(['date','users.dedicated','users.id','users.name','users.bg_color','users.txt_color',DB::raw("SUM(spent_hours) as spent_hours")])
            ->leftJoin('tasks','tasks.id','=','spents.task_id')
            ->rightJoin('users','users.id','=','tasks.user_id')
            ->where('users.role','User')
            ->groupBy('users.id');

            if($user_id != null && $user_id != 0 && $user_id != ""){
                $query = $query->where('users.id',$user_id);
            }

            if($active_date != null && $active_date != ""){
                $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
                $query = $query->where('spents.date',null)
                                ->orWhereDate('spents.date',$db_active_date->toDateString());

                if($user_id != null && $user_id != 0 && $user_id != ""){
                    $query = $query->where('users.id',$user_id);
                }

                $active_date = $db_active_date->format('d/m/Y');
            }
            //end

            //new code
            // $query = User::select(['spents.date','users.dedicated','users.name','users.bg_color','users.txt_color',DB::raw("SUM(spents.spent_hours) as spent_hours")])
            // ->leftJoin('tasks','users.id','=','tasks.user_id')
            // ->leftJoin('spents','tasks.id','=','spents.task_id')
            // ->where('role','User')
            // ->where('active',1)
            // ->groupBy('users.id');

            // if($user_id != null && $user_id != 0 && $user_id != ""){
            //     $query = $query->where('users.id',$user_id);
            // }

            // if($active_date != null && $active_date != ""){
            //     $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
            //     $query = $query->where('spents.date',null)
            //                     ->orWhereDate('spents.date',$db_active_date->toDateString());

            //     if($user_id != null && $user_id != 0 && $user_id != ""){
            //         $query = $query->where('users.id',$user_id);
            //     }

            //     $active_date = $db_active_date->format('d/m/Y');
            // }



            //$query = $query->orderBy('spents.date', 'desc');
            $query = $query->orderBy('spent_hours', 'desc');

            //dd($query->get());
        }else{
            $query = Spent::with(['task','task.user' => function($query){
            },'task.project','task.status','task.user']);

            if($user_id != null && $user_id != 0 && $user_id != ""){
                $query = $query->whereHas('task.user', function($query) use ($user_id){
                    $query = $query->where('user_id',$user_id);
                });
            }

            if($project_id != null && $project_id != 0 && $project_id != ""){
                $query = $query->whereHas('task.project', function($query) use ($project_id){
                    $query = $query->where('project_id',$project_id);
                });
            }

            if($active_date != null && $active_date != ""){
                $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
                $query = $query
                ->whereDate('date',$db_active_date->toDateString());

                $active_date = $db_active_date->format('d/m/Y');
            }

            $total_hours = $query->sum('spent_hours');

            $query = $query->orderBy('date', 'desc');
        }

        //dd($query->get());

        //$spents = $query->latest()->paginate(50);
        $spents = $query->paginate(50);

        $params = array('reset' => $request->reset,'user_id' => $user_id,'project_id' => $project_id,'active_date' => $active_date);

        return view($view, compact('spents','projects','statuses','users','user_id','project_id','status_id','start_date','end_date','active_date','params','summary','total_hours'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function freeusers(Request $request)
    {
        $active_date = $request->active_date;

        if($active_date == null || $active_date == ""){
            $active_date = Carbon::now();
        }else{
            $active_date = Carbon::createFromFormat('d/m/Y', $active_date);
        }

        

        $taskUsers = Task::select('users.id')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->where('active_date',$active_date->toDateString())
            ->groupBy('tasks.user_id')->get()->toArray();
        
        $query = User::where('role','User')
                    ->where('active',true)
                    ->where('dedicated',false)
                    ->whereNotIn('id',$taskUsers);

        $users = $query->paginate(50);

        $active_date = $active_date->format('d/m/Y');

        return view('sheets.freeusers', compact('users','active_date'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function samplechart(Request $request)
    {
        $active_date = $request->active_date;

        if($active_date == null || $active_date == ""){
            $active_date = Carbon::now();
        }else{
            $active_date = Carbon::createFromFormat('d/m/Y', $active_date);
        }

        

        $taskUsers = Task::select('users.id')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->where('active_date',$active_date->toDateString())
            ->groupBy('tasks.user_id')->get()->toArray();
        
        $query = User::where('role','User')->whereNotIn('id',$taskUsers);

        $users = $query->paginate(50);

        $active_date = $active_date->format('d/m/Y');

        return view('sheets.samplechart', compact('users','active_date'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function workhours(Request $request)
    {
        $view = 'sheets.workhours';
        $users = User::pluck('name', 'id');
        $projects = Project::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        $user_id = $request->user_id;
        $project_id = $request->project_id;
        $status_id = $request->status_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $active_date = $request->active_date;
        $summary = $request->summary;
        $total_hours = 0;


        if($request->reset == null || $request->reset == false){
            if($active_date == null || $active_date == ""){
                $active_date = Carbon::now()->format('d/m/Y');
            }
        }

        if($summary != null && $summary == true){
            $view = 'sheets.summary';

            //old code
            // $query = Spent::select(['spents.date','users.name','users.bg_color','users.txt_color',DB::raw("SUM(spents.spent_hours) as spent_hours")])
            // ->join('tasks', 'spents.task_id', '=', 'tasks.id')
            // ->join('users', 'tasks.user_id', '=', 'users.id')
            // ->groupBy('tasks.user_id')
            // ;

            // if($user_id != null && $user_id != 0 && $user_id != ""){
            //     $query = $query->where('tasks.user_id',$user_id);
            // }else{
            //     $query = $query->groupBy('tasks.user_id');
            // }

            // if($active_date != null && $active_date != ""){
            //     $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
            //     $query = $query
            //     ->whereDate('spents.date',$db_active_date->toDateString());

            //     $active_date = $db_active_date->format('d/m/Y');
            // }else{
            //     $query = $query->groupBy('spents.date');
            // }

            // $query = $query->orderBy('spents.date', 'desc');

            //new code
            $query = User::select(['spents.date','users.name','users.bg_color','users.txt_color',DB::raw("SUM(spents.spent_hours) as spent_hours")])
            ->leftJoin('tasks','users.id','=','tasks.user_id')
            ->leftJoin('spents','tasks.id','=','spents.task_id')
            ->where('role','User')
            ->groupBy('users.id');

            if($user_id != null && $user_id != 0 && $user_id != ""){
                $query = $query->where('users.id',$user_id);
            }

            if($active_date != null && $active_date != ""){
                $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
                $query = $query->where('spents.date',null)
                                ->orWhereDate('spents.date',$db_active_date->toDateString());

                if($user_id != null && $user_id != 0 && $user_id != ""){
                    $query = $query->where('users.id',$user_id);
                }

                $active_date = $db_active_date->format('d/m/Y');
            }



            //$query = $query->orderBy('spents.date', 'desc');
            $query = $query->orderBy('spent_hours', 'desc');

            //dd($query->get());
        }else{
            $query = Spent::with(['task','task.user' => function($query){
            },'task.project','task.status','task.user']);

            if($user_id != null && $user_id != 0 && $user_id != ""){
                $query = $query->whereHas('task.user', function($query) use ($user_id){
                    $query = $query->where('user_id',$user_id);
                });
            }

            if($project_id != null && $project_id != 0 && $project_id != ""){
                $query = $query->whereHas('task.project', function($query) use ($project_id){
                    $query = $query->where('project_id',$project_id);
                });
            }

            if($active_date != null && $active_date != ""){
                $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
                $query = $query
                ->whereDate('date',$db_active_date->toDateString());

                $active_date = $db_active_date->format('d/m/Y');
            }

            $total_hours = $query->sum('spent_hours');

            $query = $query->orderBy('date', 'desc');
        }


        //$spents = $query->latest()->paginate(50);
        $spents = $query->paginate(50);

        $params = array('reset' => $request->reset,'user_id' => $user_id,'project_id' => $project_id,'active_date' => $active_date);

        return view($view, compact('spents','projects','statuses','users','user_id','project_id','status_id','start_date','end_date','active_date','params','summary','total_hours'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
