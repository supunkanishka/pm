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
use Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        $projects = Project::latest()->paginate(50);

        $params = [];

        return view('projects.index', compact('projects','params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
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
        ]);

        //Task::create($request->all());
        $project = new Project();
        $project->name = $request->get('name');
        $project->active = ($request->get('active') == null) ? 0 : 1;

        if($request->get('start_date') != null && $request->get('start_date') != ""){
            $project->start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'));
        }
        
        if($request->get('end_date') != null && $request->get('end_date') != ""){
            $project->end_date = Carbon::createFromFormat('d/m/Y', $request->get('end_date'));
        }

        $project->save();

        return redirect()->route('projects.index')
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
    public function edit($project)
    {
        $project = Project::where('id',$project)->first();

        if($project->start_date != null && $project->start_date != ""){
            $start_date = Carbon::createFromFormat('Y-m-d', $project->start_date);
            $project->start_date = $start_date->format('d/m/Y');
        }

        if($project->end_date != null && $project->end_date != ""){
            $end_date = Carbon::createFromFormat('Y-m-d', $project->end_date);
            $project->end_date = $end_date->format('d/m/Y');
        }


        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $project = Project::where('id',$project)->first();
        $project->name = $request->get('name');
        $project->active = ($request->get('active') == null) ? 0 : 1;

        if($request->get('start_date') != null && $request->get('start_date') != ""){
            $project->start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'));
        }else{
            $project->start_date = null;
        }

        if($request->get('end_date') != null && $request->get('end_date') != ""){
            $project->end_date = Carbon::createFromFormat('d/m/Y', $request->get('end_date'));
        }else{
            $project->start_date = null;
        }
        

        $project->save();

        return redirect()->route('projects.index')
            ->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $authUser = Auth::user();
        
        if($authUser->role == "Super Admin"){
            //$project->delete();
        }

        return redirect()->route('projects.index')
            ->with('success', 'Task deleted successfully');
    }
}
