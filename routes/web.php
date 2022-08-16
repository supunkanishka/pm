<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SpentController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\SheetController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TrackController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/tracktotal', [TrackController::class, 'tracktotal'])->name('tracktotal');

Route::group(['middleware' => ['auth','schedule']], function () {
	
	Route::get('fileupload', [FileController::class, 'fileupload'])->name('fileupload');
	Route::get('export', [FileController::class, 'export'])->name('export');
	Route::post('import', [FileController::class, 'import'])->name('import');
	Route::get('downloadfile', [FileController::class, 'downloadfile'])->name('downloadfile');

	Route::post('updatetaskstatus', [TaskController::class, 'updatetaskstatus'])->name('updatetaskstatus');

	Route::post('deletetask', [TaskController::class, 'deletetask'])->name('deletetask');

	Route::resource('tasks', TaskController::class);
	Route::resource('images', ImageController::class);
	
	Route::resource('projects', ProjectController::class);
	Route::resource('sprints', SprintController::class);

	Route::resource('tasks.spents', SpentController::class);

	Route::get('/sample', [SpentController::class, 'sample'])->name('sample');

	Route::get('/setusercolors', [ControlController::class, 'setusercolors'])->name('setusercolors');

	Route::get('/setstatuscolors', [ControlController::class, 'setstatuscolors'])->name('setstatuscolors');

	Route::get('/timesheets', [SheetController::class, 'timesheets'])->name('timesheets');
	Route::get('/track', [TrackController::class, 'track'])->name('track');
	

	Route::get('/velocity', [SheetController::class, 'velocity'])->name('velocity');

	Route::get('/freeusers', [SheetController::class, 'freeusers'])->name('freeusers');

	Route::get('/workhours', [SheetController::class, 'workhours'])->name('workhours');

	Route::get('/samplechart', [SheetController::class, 'samplechart'])->name('samplechart');

	// Route::get('/addnewuser', [ControlController::class, 'addnewuser'])->name('addnewuser');

	Route::get('/addnewuser/{name}/{email}/{bg_color}/{txt_color}', [ControlController::class, 'addnewuser'])->name('addnewuser');

	//Route::get('/addproject', [ControlController::class, 'addproject'])->name('addproject');

	Route::get('/addproject/{name}', [ControlController::class, 'addproject'])->name('addproject');

	Route::get('/addleavetype/{name}', [ControlController::class, 'addleavetype'])->name('addleavetype');

	Route::get('/updateuserrole', [ControlController::class, 'updateuserrole'])->name('updateuserrole');

	Route::get('/updatestatus', [ControlController::class, 'updatestatus'])->name('updatestatus');

	Route::get('/updateproject/{name}/{newname}', [ControlController::class, 'updateproject'])->name('updateproject');

	Route::get('/updateleavetype/{name}/{newname}', [ControlController::class, 'updateleavetype'])->name('updateleavetype');

	Route::get('/updateuseractivestatus', [ControlController::class, 'updateuseractivestatus'])->name('updateuseractivestatus');

	Route::get('/updatededicateduser', [ControlController::class, 'updatededicateduser'])->name('updatededicateduser');

	Route::get('/note', [ControlController::class, 'note'])->name('note');

	Route::get('/fix', [ControlController::class, 'fix'])->name('fix');


	Route::post('/updatenote', [ControlController::class, 'updatenote'])->name('updatenote');

	//Route::resource('leaves', LeaveController::class);

	//Route::get('/report', [SheetController::class, 'report']);
	Route::get('/report', [TrackController::class, 'report'])->name('report');
	
});

Route::group(['middleware' => ['auth','schedule','checkvaliduser']], function () {
	


	Route::resource('leaves', LeaveController::class);

	Route::resource('works', WorkController::class);

	Route::resource('users', UserController::class);

	Route::resource('teams', TeamController::class);
	
});
