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
use Auth;

class SprintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sprints = Sprint::with('project')->latest()->paginate(50);

        $params = [];

        return view('sprints.index', compact('sprints','params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('active',1)->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');
        $selectedStatusId = 1;

        return view('sprints.create',compact('projects','statuses','selectedStatusId'));
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
        $sprint = new Sprint();
        $sprint->name = $request->get('name');
        $sprint->project_id = $request->get('project_id');
        $sprint->status_id = $request->get('status_id');
        if($request->get('start_date') != null && $request->get('start_date') != ""){
            $sprint->start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'));
        }
        
        if($request->get('due_date') != null && $request->get('due_date') != ""){
            $sprint->due_date = Carbon::createFromFormat('d/m/Y', $request->get('due_date'));
        }

        $sprint->save();

        return redirect()->route('sprints.index')
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
    public function edit($sprint)
    {
        $sprint = Sprint::with('project')->where('id',$sprint)->first();
        $projects = Project::where('active',1)->pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        //dd($task->start_date);

        if($sprint->start_date != null && $sprint->start_date != ""){
            $start_date = Carbon::createFromFormat('Y-m-d', $sprint->start_date);
            $sprint->start_date = $start_date->format('d/m/Y');
        }

        if($sprint->due_date != null && $sprint->due_date != ""){
            $due_date = Carbon::createFromFormat('Y-m-d', $sprint->due_date);
            $sprint->due_date = $due_date->format('d/m/Y');
        }


        return view('sprints.edit', compact('sprint','projects','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sprint)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required',
        ]);

        $sprint = Sprint::where('id',$sprint)->first();
        $sprint->name = $request->get('name');
        $sprint->project_id = $request->get('project_id');
        $sprint->status_id = $request->get('status_id');
        

        
        
        if($request->get('start_date') != null && $request->get('start_date') != ""){
            $sprint->start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'));
        }else{
            $sprint->start_date = null;
        }
        
        if($request->get('due_date') != null && $request->get('due_date') != ""){
            $sprint->due_date = Carbon::createFromFormat('d/m/Y', $request->get('due_date'));
        }else{
            $sprint->due_date = null;
        }


        $sprint->save();

        return redirect()->route('sprints.index')
            ->with('success', 'Task updated successfully');
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
