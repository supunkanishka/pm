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

class SpentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$task)
    {
        $id=$task;
        $spents = Spent::with('task')->where('task_id',$id)->latest()->paginate(10);

        $task = Task::where('id', $id)->first();

        return view('spents.index', compact('spents','id','task'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($taskId)
    {
        $task = Task::where('id', $taskId)->first();

        return view('spents.create',compact('task'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$taskId)
    {
        $request->validate([
            'date' => 'required',
            'spent_hours' => 'required|numeric',
        ]);

        $spent = new Spent();
        $spent->task_id = $taskId;
        $spent->date = Carbon::createFromFormat('d/m/Y', $request->get('date'));
        $spent->spent_hours = $request->get('spent_hours');

        $task = Task::where('id',$taskId)->first();
        $authUser = Auth::user();

        if($task != null && $task->user_id != null){
            if($authUser->id == $task->user_id || $authUser->role == "Super Admin"){
                $spent->save();
            }
        }
        
        

        return redirect()->route('tasks.spents.index',$taskId)
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($taskId,$spentId)
    {
        $spent = Spent::where('id',$spentId);
        return view('spents.show', compact('spent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($taskId,$spentId)
    {
        $spent = Spent::where('id',$spentId);

        return view('spents.edit', compact('spent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $taskId,$spentId)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($taskId,$spentId)
    {
        $task = Task::where('id',$taskId)->first();
        $spent = Spent::where('id',$spentId)->first();

        $authUser = Auth::user();

        if(
            ($task->user_id != null && $task->user_id == $authUser->id) 
            || 
            ($authUser->role == "Super Admin")
        ){
            $spent->delete();
        }
        

        return redirect()->route('tasks.spents.index',$taskId)
            ->with('success', 'Task deleted successfully');
    }

    
}
