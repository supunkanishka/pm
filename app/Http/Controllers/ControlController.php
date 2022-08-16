<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Status;
use App\Models\Schedule;
use App\Models\Spent;
use App\Models\Type;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Auth;

class ControlController extends Controller
{
    public function fix()
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            // $spents = Spent::whereDate('created_at',Carbon::now()->toDateString())
            // ->whereDate('date','2021-06-09')
            // ->get();
            
            // foreach ($spents as $spent) {
            //     $spent->date = '2021-09-06';
            //     $spent->save();
            // }

            // $spents = Spent::whereDate('created_at',Carbon::now()->subDays(1)->toDateString())
            // ->whereDate('date','2021-06-09')
            // ->get();
            
            // foreach ($spents as $spent) {
            //     $spent->date = Carbon::now()->subDays(1);
            //     $spent->save();
            // }

            //second
            // $spents2 = Spent::whereDate('created_at',Carbon::now()->subDays(1)->toDateString())
            // ->whereDate('date','2021-03-09')
            // ->get();
            
            // foreach ($spents2 as $spent2) {
            //     $spent2->date = '2021-09-03';
            //     $spent2->save();
            // }
        }
    }

    public function note()
    {
        $parameter = Parameter::first();

        if($parameter == null){
            $parameter = new Parameter();
        }

        //Schedule::where( 'date', '<', Carbon::now()->subDays(1))->delete();

        return view('control.note',compact('parameter'));
    }

    public function updatenote(Request $request)
    {
        $request->validate([
            'note' => 'max:150',
        ]);

        $parameter = Parameter::first();

        if($parameter == null){
            $parameter = new Parameter();
        }
        
        $parameter->note = $request->note;
        $parameter->save();

        return redirect()->route('tasks.index')
            ->with('success', 'Note Updated.');
    }

    public function setusercolors()
    {
        $authUser = Auth::user();
        if($authUser->role == "Super Admin"){
            //set colors
            $bg_colors = [
                '#5F4B8BFF',
                '#42EADDFF',
                '#000000FF',
                '#00A4CCFF',
                '#F95700FF',
                '#00203FFF',
                '#ADEFD1FF',
                '#606060FF',
                '#D6ED17FF',
                '#ED2B33FF',
                '#D85A7FFF',
                '#2C5F2D',
                '#00539CFF',
                '#FEE715FF',
                '#5CC8D7FF',
                '#B1624EFF',
                '#F2AA4CFF',
                '#A07855FF',
                '#603F83FF',
                '#2BAE66FF',
                '#E94B3CFF',
                '#990011FF',
            ];

            $txt_colors = [
                '#FFFFFF',
                '#000000',
                '#FFFFFF',
                '#000000',
                '#FFFFFF',
                '#FFFFFF',
                '#000000',
                '#FFFFFF',
                '#000000',
                '#FFFFFF',
                '#FFFFFF',
                '#FFFFFF',
                '#FFFFFF',
                '#FFFFFF',
                '#FFFFFF',
                '#FFFFFF',
                '#000000',
                '#FFFFFF',
                '#FFFFFF',
                '#FFFFFF',
                '#FFFFFF',
                '#FFFFFF',
            ];

            $users = User::all();

            $count = 0;

            foreach ($users as $dbUser) {
                $user = User::where('id',$dbUser->id)->first();
                $user->bg_color = $bg_colors[$count];
                $user->txt_color = $txt_colors[$count];
                $user->save();

                $count++;
            }

            echo 'Done';
        }
    }

    public function setstatuscolors()
    {
    	$authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            //set colors
            $colors = [
                'Pending' => '#FFFFFF',
                'On Hold' => '#F95700FF',
                'In Progress' => '#FEE715FF',
                'Completed' => '#006B38FF',
            ];


            foreach ($colors as $key => $value) {
            	$status = Status::where('name',$key)->first();
            	$status->color = $value;
                $status->save();
            }

            echo 'Done';
        }
    }

    public function addnewuser($name,$email,$bg_color,$txt_color)
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            // DB::table('users')->insert([
            //     'name' => 'Dilan',
            //     'email' => 'dilanm@insharptechnologies.com',
            //     'password' => Hash::make('12345678'),
            //     'role' => 'User',
            //     'bg_color' => '#1c100b',
            //     'txt_color' => '#FFFFFF',
            // ]);

            // DB::table('users')->insert([
            //     'name' => 'Ranula',
            //     'email' => 'ranular@insharptechnologies.com',
            //     'password' => Hash::make('12345678'),
            //     'role' => 'User',
            //     'bg_color' => '#042f66',
            //     'txt_color' => '#FFFFFF',
            // ]);

            // DB::table('users')->insert([
            //     'name' => 'Vidush',
            //     'email' => 'vidushrajc@insharptechnologies.com',
            //     'password' => Hash::make('12345678'),
            //     'role' => 'User',
            //     'bg_color' => '#edb879',
            //     'txt_color' => '#000000',
            // ]);

            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role' => 'User',
                'bg_color' => '#'.$bg_color,
                'txt_color' => '#'.$txt_color,
            ]);

            echo 'Done';
        }

        
    }

    public function addproject($name = "")
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            if($name != "")
            {
                DB::table('projects')->insert([
                    'name' => $name,
                ]);
            }
            

            echo 'Done';
        }

        
    }

    public function addleavetype($type = "")
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            if($type != "")
            {
                DB::table('types')->insert([
                    'name' => $type,
                ]);
            }
            

            echo 'Done';
        }

        
    }

    public function updateproject($name = "",$newname = "")
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            if($name != "" && $newname != ""){
                $project = Project::where('name',$name)->first();
                $project->name = $newname;
                $project->save();
            }
            

            echo 'Done';
        }

        
    }

    public function updateleavetype($name = "",$newname = "")
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            if($name != "" && $newname != ""){
                $obj = Type::where('name',$name)->first();
                $obj->name = $newname;
                $obj->save();
            }
            

            echo 'Done';
        }

        
    }

    public function updateuserrole()
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            $user = User::where('name','Sachith')->first();
            $user->role = "User";
            $user->save();

            $user = User::where('name','Chanaka')->first();
            $user->role = "User";
            $user->save();

            $user = User::where('name','Yashan')->first();
            $user->role = "User";
            $user->save();

            $user = User::where('name','Chamoda')->first();
            $user->role = "User";
            $user->save();

            echo 'Done';
        }

        
    }

    public function updatestatus()
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            $affected = Task::where('status_id', null)->update(array('status_id' => 1));

            $affected = Task::where('status_id', '')->update(array('status_id' => 1));

            echo 'Done';
        }

        
    }

    public function updateuseractivestatus()
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            $user = User::where('email','ishans@insharptechnologies.com')->first();
            $user->active = 0;
            $user->save();

            $user = User::where('email','ranular@insharptechnologies.com')->first();
            $user->active = 0;
            $user->save();

            echo 'Done';
        }

        
    }

    public function updatededicateduser()
    {
        $authUser = Auth::user();

        if($authUser->role == "Super Admin"){
            
            $user = User::where('email','sachithb@insharptechnologies.com')->first();
            $user->dedicated = 1;
            $user->save();

            $user = User::where('email','chamodak@insharptechnologies.com')->first();
            $user->dedicated = 1;
            $user->save();

            $user = User::where('email','chanakaf@insharptechnologies.com')->first();
            $user->dedicated = 1;
            $user->save();

            $user = User::where('email','yashanw@insharptechnologies.com')->first();
            $user->dedicated = 1;
            $user->save();

            $user = User::where('email','erandak@insharptechnologies.com')->first();
            $user->dedicated = 1;
            $user->save();

            $user = User::where('email','pasanm@insharptechnologies.com')->first();
            $user->dedicated = 1;
            $user->save();
            

            echo 'Done';
        }

        
    }

    
}
