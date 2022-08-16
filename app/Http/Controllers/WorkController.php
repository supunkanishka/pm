<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Project;
use App\Models\User;
use App\Models\Status;
use Carbon\Carbon;
use App\Models\Spent;
use Auth;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::pluck('name', 'id');
        $projects = Project::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        $user_id = $request->user_id;
        $project_id = $request->project_id;
        $status_id = $request->status_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $active_date = $request->active_date;
        $text = $request->text;

        $reset = true;
        $allmyworks = true;
        $allnotcompleted = true;
        $todo = true;

        if($request->todo == null || $request->todo != true){
            $todo = false;
        }

        if($request->allmyworks == null || $request->allmyworks != true){
            $allmyworks = false;
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

        if($allmyworks){
            $authUser = Auth::user();
            $user_id = $authUser->id;
            $project_id = null;
            $status_id = null;
            $start_date = null;
            $end_date = null;
            $active_date = null;
            $text = null;
        }

        if($allnotcompleted){
            $user_id = null;
            $project_id = null;
            $status_id = null;
            $start_date = null;
            $end_date = null;
            $active_date = null;
            $text = null;
        }

        if($todo){
            $user_id = null;
            $project_id = null;
            $status_id = null;
            $start_date = null;
            $end_date = null;
            $active_date = null;
            $text = null;
        }

        $query = Work::with(['project','user','status'])->where( 'created_at', '>', Carbon::now()->subDays(60));

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

        // if($active_date != null && $active_date != ""){
        //     $db_active_date = Carbon::createFromFormat('d/m/Y', $active_date);
        //     $query = $query->whereDate('active_date',$db_active_date->toDateString());

        //     $active_date = $db_active_date->format('d/m/Y');
        // }

        $works = $query->latest()->paginate(100);

        $params = array('reset' => $request->reset,'user_id' => $user_id,'project_id' => $project_id,'active_date' => $active_date);

        return view('works.index', compact('works','projects','statuses','users','user_id','project_id','status_id','start_date','end_date','active_date','params','text'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        $authUser = Auth::user();

        $selectedProjectId = 0;
        $selectedUserId = $authUser->id;
        $selectedStatusId = 1;

        $active_date = Carbon::now()->format('d/m/Y');

        return view('works.create',compact('selectedProjectId','projects','users','selectedUserId','statuses','selectedStatusId','active_date'));
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

        //Work::create($request->all());
        $work = new Work();
        $work->name = $request->get('name');
        $work->description = $request->get('description');
        $work->project_id = $request->get('project_id');
        $work->user_id = $request->get('user_id');
        $work->status_id = $request->get('status_id');
        if($request->get('start_date') != null && $request->get('start_date') != ""){
            $work->start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'));
        }
        
        if($request->get('due_date') != null && $request->get('due_date') != ""){
            $work->due_date = Carbon::createFromFormat('d/m/Y', $request->get('due_date'));
        }

        if($request->get('active_date') != null && $request->get('active_date') != ""){
            $work->active_date = Carbon::createFromFormat('d/m/Y', $request->get('active_date'));
        }

        $work->save();

        return redirect()->route('works.index')
            ->with('success', 'work created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        return view('works.show', compact('work'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        $work = Work::with('project','user')->where('id',$work->id)->first();
        $projects = Project::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        //dd($Work->start_date);

        if($work->start_date != null && $work->start_date != ""){
            $start_date = Carbon::createFromFormat('Y-m-d', $work->start_date);
            $work->start_date = $start_date->format('d/m/Y');
        }

        if($work->due_date != null && $work->due_date != ""){
            $due_date = Carbon::createFromFormat('Y-m-d', $work->due_date);
            $work->due_date = $due_date->format('d/m/Y');
        }

        if($work->active_date != null && $work->active_date != ""){
            $active_date = Carbon::createFromFormat('Y-m-d', $work->active_date);
            $work->active_date = $active_date->format('d/m/Y');
        }


        return view('works.edit', compact('work','projects','users','statuses'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required',
            'estimated_hours' => 'numeric',
        ]);

        $work = Work::where('id',$work->id)->first();
        $work->name = $request->get('name');
        $work->description = $request->get('description');
        $work->project_id = $request->get('project_id');
        $work->user_id = $request->get('user_id');
        $work->status_id = $request->get('status_id');

        

        // $authUser = Auth::user();
        // if($authUser->role == "Admin" || $authUser->email == "sameeshai@insharptechnologies.com"){
        //     $work->estimated_hours = $request->get('estimated_hours');
        // }

        $authUser = Auth::user();

        if($request->get('start_date') != null && $request->get('start_date') != ""){
            $work->start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'));
        }else{
            $work->start_date = null;
        }
        
        if($request->get('due_date') != null && $request->get('due_date') != ""){
            $work->due_date = Carbon::createFromFormat('d/m/Y', $request->get('due_date'));
        }else{
            $work->due_date = null;
        }

        if($request->get('active_date') != null && $request->get('active_date') != ""){
            $work->active_date = Carbon::createFromFormat('d/m/Y', $request->get('active_date'));
        }else{
            $work->active_date = null;
        }

        $work->save();

        return redirect()->route('works.index')
            ->with('success', 'work updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        $work->delete();
        
        return redirect()->route('works.index')
            ->with('success', 'work deleted successfully');
    }
}
