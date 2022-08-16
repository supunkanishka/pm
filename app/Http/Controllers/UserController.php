<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Status;
use Carbon\Carbon;
use App\Models\Spent;
use App\Models\Sprint;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('name', 'asc')->pluck('name', 'id');
        $projects = Project::orderBy('name', 'asc')->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');
        $sprints = Sprint::orderBy('name', 'asc')->pluck('name', 'id');
        $teams = Team::orderBy('name', 'asc')->pluck('name', 'id');

        $update_hours = $request->update_hours;
        if($update_hours){

            if($request->get('selected_task_id') != null && $request->get('selected_task_id') != "" && $request->get('popup_add_hours_date') != null && $request->get('popup_add_hours_date') != ""){
                $spent = new Spent();
                $spent->task_id = $request->get('selected_task_id');
                $spent->date = Carbon::createFromFormat('d/m/Y', $request->get('popup_add_hours_date'));
                $spent->spent_hours = $request->get('popup_add_hours_spent_hours');
                $spent->save();
            }
            

        }

        $user_id = $request->user_id;
        $team_id = $request->team_id;
        $active = ($request->active == null) ? 1 : $request->active;
        $project_id = $request->project_id;
        $sprint_id = $request->sprint_id;
        $status_id = $request->status_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $active_date = $request->active_date;
        $text = $request->text;

        if($project_id != null && $project_id != "" && $project_id != 0){
            $sprints = Sprint::where('project_id',$project_id)->pluck('name', 'id');
        }
        

        $reset = true;
        $allmytasks = true;
        $allnotcompleted = true;
        $todo = true;

        if($request->todo == null || $request->todo != true){
            $todo = false;
        }

        if($request->allmytasks == null || $request->allmytasks != true){
            $allmytasks = false;
        }

        if($request->reset == null || $request->reset != true){
            $reset = false;
        }

        if($request->allnotcompleted == null || $request->allnotcompleted != true){
            $allnotcompleted = false;
        }

        if($reset == false){
            if($active_date == null || $active_date == ""){
                $active_date = Carbon::now()->format('d/m/Y');
            }
        }

        if($allmytasks){
            $authUser = Auth::user();
            $user_id = $authUser->id;
            $active = null;
            $team_id = null;
            $project_id = null;
            $sprint_id = null;
            $status_id = null;
            $start_date = null;
            $end_date = null;
            $active_date = null;
            $text = null;
        }

        if($allnotcompleted){
            $user_id = null;
            $active = null;
            $team_id = null;
            $project_id = null;
            $sprint_id = null;
            $status_id = null;
            $start_date = null;
            $end_date = null;
            $active_date = null;
            $text = null;
        }

        if($todo){
            $user_id = null;
            $active = null;
            $team_id = null;
            $project_id = null;
            $sprint_id = null;
            $status_id = null;
            $start_date = null;
            $end_date = null;
            $active_date = null;
            $text = null;
        }

        // $query = Task::with(['spents' => function($query){
        //     $query->selectRaw('sum(spent_hours) as total_spent,task_id')->groupBy('task_id');
        // },'project','sprint','user','status'])
        // //->where( 'created_at', '>', Carbon::now()->subDays(365))
        // ;

        // if($allnotcompleted){
        //     $status = Status::where('name','Completed')->first();
        //     $query = $query->where('status_id','!=',$status->id)
        //     ->orWhere('status_id',null);
        // }

        // if($todo){
        //     $pending = Status::where('name','Pending')->first();
        //     $inprogress = Status::where('name','In Progress')->first();
        //     $query = $query->where('status_id',$pending->id)
        //     ->orWhere('status_id',$inprogress->id);
        // }

        // if($text != null && $text != ""){
        //     $query = $query->where('name','like','%'. $text .'%')
        //             ->orWhere('id',$text);
        // }

        // if($user_id != null && $user_id != 0 && $user_id != ""){
        //     $query = $query->where('user_id',$user_id);
        // }

        // if($project_id != null && $project_id != 0 && $project_id != ""){
        //     $query = $query->where('project_id',$project_id);
        // }

        // if($sprint_id != null && $sprint_id != 0 && $sprint_id != ""){
        //     $query = $query->where('sprint_id',$sprint_id);
        // }

        // if($status_id != null && $status_id != 0 && $status_id != ""){
        //     $query = $query->where('status_id',$status_id);
        // }

        // if($start_date != null && $start_date != ""){
        //     $db_start_date = Carbon::createFromFormat('d/m/Y', $start_date);
        //     $query = $query
        //     ->whereDate('start_date','<=',$db_start_date->toDateString())
        //     ->whereDate('due_date','>=',$db_start_date->toDateString());

        //     $start_date = $db_start_date->format('d/m/Y');
        // }

        // if($end_date != null && $end_date != ""){
        //     $db_end_date = Carbon::createFromFormat('d/m/Y', $end_date);
        //     $query = $query->whereDate('due_date',$db_end_date->toDateString());

        //     $end_date = $db_end_date->format('d/m/Y');
        // }

        // if($active_date != null && $active_date != ""){
        //     $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
        //     $query = $query->whereDate('active_date',$db_active_date->toDateString());

        //     $active_date = $db_active_date->format('d/m/Y');
        // }

        //$tasks = $query->latest()->paginate(100);

        $query = User::with(['team'])->orderBy('team_id', 'desc');


        if($active != null && $active != "" && $active != "all"){
            $query = $query->where('active',$active);
        }

        if($team_id != null && $team_id != 0 && $team_id != ""){
            $query = $query->where('team_id',$team_id);
        }

        $objects = $query->latest()->paginate(100);


        $params = array('reset' => $request->reset,'team_id' => $team_id,'user_id' => $user_id,'project_id' => $project_id,'sprint_id' => $sprint_id,'active_date' => $active_date);

        

        return view('users.index', compact('objects','teams','projects','sprints','statuses','users','user_id','active','team_id','project_id','sprint_id','status_id','start_date','end_date','active_date','params','text'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $projects = Project::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');
        $teams = Team::pluck('name', 'id');

        $authUser = Auth::user();

        $selectedDedicatedId = 0;
        $selectedActiveId = 1;
        $selectedTeamId = 0;

        return view('users.create',compact('teams','selectedDedicatedId','selectedActiveId','selectedTeamId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        //Task::create($request->all());
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->bg_color = ($request->get('bg_color')) ? $request->get('bg_color') : "#ffffff";
        $user->txt_color = ($request->get('txt_color')) ? $request->get('txt_color') : "#000000";
        $user->password = Hash::make('12345678');
        $user->role = 'User';
        $user->active = 1;
        $user->dedicated = 0;
        $user->team_id = $request->get('team_id');

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user = User::with('team')->where('id',$user->id)->first();
        $statuses = Status::pluck('name', 'id');
        $teams = Team::pluck('name', 'id');

        return view('users.edit', compact('teams','user','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = User::where('id',$user->id)->first();
        $user->name = $request->get('name');
        $user->phone = $request->get('phone');
        $user->bg_color = ($request->get('bg_color')) ? $request->get('bg_color') : "#ffffff";
        $user->txt_color = ($request->get('txt_color')) ? $request->get('txt_color') : "#000000";
        $user->active = $request->get('active');
        $user->dedicated = $request->get('dedicated');

        if($user->email == "supunr@insharptechnologies.com"){
            $user->role = "Super Admin";
        }else{
            $user->role = $request->get('role');
        }
        

        $user->team_id = $request->get('team_id');

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
