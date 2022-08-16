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
use App\Models\Image;
use Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('active','1')
        ->where('role','User')
        ->orderBy('name', 'asc')->pluck('name', 'id');
        $projects = Project::where('active',1)->orderBy('name', 'asc')->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');
        $sprints = Sprint::orderBy('name', 'asc')->pluck('name', 'id');

        $update_hours = $request->update_hours;
        if($update_hours){

            if($request->get('selected_task_id') != null && $request->get('selected_task_id') != "" && $request->get('popup_add_hours_date') != null && $request->get('popup_add_hours_date') != ""){

                $db_popup_add_hours_date = Carbon::createFromFormat('d/m/Y', $request->get('popup_add_hours_date'));

                $db_spent = Spent::where('task_id',$request->get('selected_task_id'))
                            ->whereDate('date',$db_popup_add_hours_date)
                            ->where('spent_hours',$request->get('popup_add_hours_spent_hours'))
                            ->first();

                if($db_spent == null){
                    $spent = new Spent();
                    $spent->task_id = $request->get('selected_task_id');
                    $spent->date = Carbon::createFromFormat('d/m/Y', $request->get('popup_add_hours_date'));
                    $spent->spent_hours = $request->get('popup_add_hours_spent_hours');
                    $spent->save();
                }

                


                // $spent = new Spent();
                // $spent->task_id = $request->get('selected_task_id');
                // $spent->date = Carbon::createFromFormat('d/m/Y', $request->get('popup_add_hours_date'));
                // $spent->spent_hours = $request->get('popup_add_hours_spent_hours');
                // $spent->save();
            }
            

        }

        $user_id = $request->user_id;
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
            $project_id = null;
            $sprint_id = null;
            $status_id = null;
            $start_date = null;
            $end_date = null;
            $active_date = Carbon::now()->format('d/m/Y');
            $text = null;
        }

        if($allnotcompleted){
            $user_id = null;
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
            $project_id = null;
            $sprint_id = null;
            $status_id = null;
            $start_date = null;
            $end_date = null;
            $active_date = null;
            $text = null;
        }

        $query = Task::with(['spents' => function($query){
            $query->selectRaw('sum(spent_hours) as total_spent,task_id')->groupBy('task_id');
        },'project','sprint','user','status'])
        //->where( 'created_at', '>', Carbon::now()->subDays(365))
        ;

        if($allnotcompleted){
            $status = Status::where('name','Completed')->first();
            $query = $query->where('status_id','!=',$status->id)
            ->orWhere('status_id',null);
        }

        if($todo){
            $pending = Status::where('name','Pending')->first();
            $inprogress = Status::where('name','In Progress')->first();
            $query = $query->where('status_id',$pending->id)
            ->orWhere('status_id',$inprogress->id);
        }

        if($text != null && $text != ""){
            $query = $query->where('name','like','%'. $text .'%')
                    ->orWhere('id',$text);
        }

        if($user_id != null && $user_id != 0 && $user_id != ""){
            $query = $query->where('user_id',$user_id);
        }

        if($project_id != null && $project_id != 0 && $project_id != ""){
            $query = $query->where('project_id',$project_id);
        }

        if($sprint_id != null && $sprint_id != 0 && $sprint_id != ""){
            $query = $query->where('sprint_id',$sprint_id);
        }

        if($status_id != null && $status_id != 0 && $status_id != ""){
            $query = $query->where('status_id',$status_id);
        }

        if($start_date != null && $start_date != ""){
            $db_start_date = Carbon::createFromFormat('d/m/Y', $start_date);
            $query = $query
            ->whereDate('start_date','<=',$db_start_date->toDateString())
            ->whereDate('due_date','>=',$db_start_date->toDateString());

            $start_date = $db_start_date->format('d/m/Y');
        }

        if($end_date != null && $end_date != ""){
            $db_end_date = Carbon::createFromFormat('d/m/Y', $end_date);
            $query = $query->whereDate('due_date',$db_end_date->toDateString());

            $end_date = $db_end_date->format('d/m/Y');
        }

        if($active_date != null && $active_date != ""){
            $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
            $query = $query->whereDate('active_date',$db_active_date->toDateString());

            $active_date = $db_active_date->format('d/m/Y');
        }

        $estimated_hours = $query->sum('estimated_hours');
        
        $tasks = $query->latest()->paginate(100);

        

        $params = array('reset' => $request->reset,'user_id' => $user_id,'project_id' => $project_id,'sprint_id' => $sprint_id,'active_date' => $active_date);

        return view('tasks.index', compact('estimated_hours','tasks','projects','sprints','statuses','users','user_id','project_id','sprint_id','status_id','start_date','end_date','active_date','params','text'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //dd(Carbon::now()->timestamp);
        $projects = Project::where('active',1)->pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');
        $sprints = Sprint::where('project_id',$request->project_id)->pluck('name', 'id');

        $authUser = Auth::user();

        $selectedProjectId = $request->project_id;
        $selectedUserId = $authUser->id;
        $selectedStatusId = 1;

        $active_date = Carbon::now()->format('d/m/Y');

        return view('tasks.create',compact('selectedProjectId','projects','sprints','users','selectedUserId','statuses','selectedStatusId','active_date'));
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
            'project_id' => 'required',
            'estimated_hours' => 'numeric',
        ]);

        //Task::create($request->all());
        $task = new Task();
        $task->name = $request->get('name');
        $task->description = $request->get('description');
        $task->project_id = $request->get('project_id');
        $task->user_id = $request->get('user_id');
        $task->sprint_id = $request->get('sprint_id');
        $task->status_id = $request->get('status_id');
        $task->meeting = ($request->get('meeting') == null) ? 0 : 1;
        $task->bug = ($request->get('bug') == null) ? 0 : 1;
        $task->estimated_hours = $request->get('estimated_hours');
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

        if ($request->hasfile('images')) {
            $images = $request->file('images');

            foreach($images as $image) {
                $name = $image->getClientOriginalName();
                $name = $task->id.Carbon::now()->timestamp.$name;
                $path = $image->storeAs('uploads', $name, 'public');

                $image = new Image();
                $image->task_id = $task->id;
                $image->name = $name;
                $image->path = '/storage/'.$path;
                $image->save();
            }
         }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $task = Task::with('images')->where('id',$task->id)->first();
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $task = Task::with('project','user')->where('id',$task->id)->first();
        $projects = Project::where('active',1)->pluck('name', 'id');
        $sprints = Sprint::where('project_id',$task->project_id)->pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        //dd($task->start_date);

        if($task->start_date != null && $task->start_date != ""){
            $start_date = Carbon::createFromFormat('Y-m-d', $task->start_date);
            $task->start_date = $start_date->format('d/m/Y');
        }

        if($task->due_date != null && $task->due_date != ""){
            $due_date = Carbon::createFromFormat('Y-m-d', $task->due_date);
            $task->due_date = $due_date->format('d/m/Y');
        }

        if($task->active_date != null && $task->active_date != ""){
            $active_date = Carbon::createFromFormat('Y-m-d', $task->active_date);
            $task->active_date = $active_date->format('d/m/Y');
        }


        return view('tasks.edit', compact('task','projects','sprints','users','statuses'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required',
            // 'estimated_hours' => 'numeric',
        ]);

        $task = Task::where('id',$task->id)->first();
        $task->name = $request->get('name');
        $task->description = $request->get('description');
        $task->project_id = $request->get('project_id');
        $task->sprint_id = $request->get('sprint_id');
        $task->user_id = $request->get('user_id');
        $task->status_id = $request->get('status_id');
        $task->meeting = ($request->get('meeting') == null) ? 0 : 1;
        $task->bug = ($request->get('bug') == null) ? 0 : 1;
        

        // $authUser = Auth::user();
        // if($authUser->role == "Admin" || $authUser->email == "sameeshai@insharptechnologies.com"){
        //     $task->estimated_hours = $request->get('estimated_hours');
        // }

        $authUser = Auth::user();
        if($authUser->email == "sameeshai@insharptechnologies.com" || $authUser->email == "supunr@insharptechnologies.com" || $authUser->email == "ravindraw@insharptechnologies.com"){
            $task->estimated_hours = $request->get('estimated_hours');
        }
        
        if($request->get('start_date') != null && $request->get('start_date') != ""){
            $task->start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'));
        }else{
            $task->start_date = null;
        }
        
        if($request->get('due_date') != null && $request->get('due_date') != ""){
            $task->due_date = Carbon::createFromFormat('d/m/Y', $request->get('due_date'));
        }else{
            $task->due_date = null;
        }

        if($request->get('active_date') != null && $request->get('active_date') != ""){
            $task->active_date = Carbon::createFromFormat('d/m/Y', $request->get('active_date'));
        }else{
            $task->active_date = null;
        }

        $task->save();

        if ($request->hasfile('images')) {
            $images = $request->file('images');

            foreach($images as $image) {
                $name = $image->getClientOriginalName();
                $name = $task->id.Carbon::now()->timestamp.$name;
                $path = $image->storeAs('uploads', $name, 'public');

                $image = new Image();
                $image->task_id = $task->id;
                $image->name = $name;
                $image->path = '/storage/'.$path;
                $image->save();
            }
         }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            $task->delete();

            Spent::where('task_id',$task->id)->delete();
            
        }

        

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully');
    }

    public function updatetaskstatus(Request $request)
    {
        $task = Task::where('id',$request->get('popup_update_task_id'))->first();
        $task->status_id = $request->get('popup_update_status_id');

        // $status = Status::where('id',$task->status_id)->first();
        // if(trim($status->name) == "Bug"){
        //     $task->bug =1;
        // }else{
        //     $task->bug =0;
        // }

        
        $task->save();

        return response()->json(['success'=>'Ajax request submitted successfully']);
    }

    public function deletetask(Request $request)
    {
        $deletetaskid = $request->get('deletetaskid');


        $authUser = Auth::user();

        

        if($authUser->role == "Super Admin"){




            $task = Task::where('id',$deletetaskid)->first();
            $task->delete();

            Spent::where('task_id',$deletetaskid)->delete();
            
        }

        return response()->json(['success'=>'Ajax request submitted successfully']);
    }
}
