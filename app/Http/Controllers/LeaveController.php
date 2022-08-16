<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use App\Models\Type;
use Carbon\Carbon;
use Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::pluck('name', 'id');

        $user_id = $request->user_id;

        $query = Leave::with(['user' => function($query){
            //$query->where('active',1);
        },'typemodel'])->where( 'created_at', '>', Carbon::now()->startOfYear());

        if($user_id != null && $user_id != 0 && $user_id != ""){
            $query = $query->where('user_id',$user_id);
        }

        //dd($query->get());

        //$leaves = $query->latest()->paginate(100);
        $query = $query->orderBy('date', 'desc');

        $leaves = $query->get();

        $params = array('user_id' => $user_id);

        return view('leaves.index', compact('leaves','users','user_id','params'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        $users = User::pluck('name', 'id');
        $types = Type::pluck('name', 'id');

        $authUser = Auth::user();

        $selectedTypeId = 1;
        $selectedUserId = $authUser->id;

        return view('leaves.create',compact('selectedUserId','selectedTypeId','users','types'));
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
            'user_id' => 'required',
            'type' => 'required',
            'date' => 'required',
            'description' => 'required',
            'amount' => 'required',
        ]);

        $leave = new Leave();
        $leave->user_id = $request->get('user_id');
        $leave->type = $request->get('type');
        if($request->get('date') != null && $request->get('date') != ""){
            $leave->date = Carbon::createFromFormat('d/m/Y', $request->get('date'));
        }
        $leave->amount = $request->get('amount');
        $leave->description = $request->get('description');
        
        $leave->save();

        return redirect()->route('leaves.index')
            ->with('success', 'Leave created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Leave $leave)
    {
        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leave = Leave::with('user','typemodel')->where('id',$id)->first();

        $users = User::pluck('name', 'id');
        $types = Type::pluck('name', 'id');



        if($leave->date != null && $leave->date != ""){
            $date = Carbon::createFromFormat('Y-m-d', $leave->date);
            $leave->date = $date->format('d/m/Y');
        }

        return view('leaves.edit', compact('leave','types','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'type' => 'required',
            'date' => 'required',
            'description' => 'required',
            'amount' => 'required',
        ]);

        $leave = Leave::where('id',$id)->first();
        $leave->user_id = $request->get('user_id');
        $leave->type = $request->get('type');
        if($request->get('date') != null && $request->get('date') != ""){
            $leave->date = Carbon::createFromFormat('d/m/Y', $request->get('date'));
        }
        $leave->amount = $request->get('amount');
        $leave->description = $request->get('description');


        $leave->save();

        return redirect()->route('leaves.index')
            ->with('success', 'Leave updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leave = Leave::where('id',$id)->first();
        $leave->delete();

        return redirect()->route('leaves.index')
            ->with('success', 'Leave deleted successfully');
    }
}
