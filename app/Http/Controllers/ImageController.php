<?php

namespace App\Http\Controllers;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($image)
    {
        $image = Image::where('id',$image)->first();
        $productImage = str_replace('/storage', '', $image->path);
        //dd('public' . $productImage);
        Storage::delete('public' . $productImage);

        //Storage::delete("/storage/uploads/50681636565957analatica.jpg");
        //Storage::delete('public/uploads/50681636565957analatica.jpg');
        //public/uploads/50681636565957analatica.jpg

        $image->delete();



        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted image.');
    }
}
