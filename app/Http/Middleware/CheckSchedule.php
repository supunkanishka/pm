<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Task;
use App\Models\Status;
use Auth;

class CheckSchedule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $authUser = Auth::user();

        $today = Carbon::now()->toDateString();
        $schedule = Schedule::where('date',$today)
                    ->where('user_id',$authUser->id)
                    ->where('name','active_date')->first();

        if($schedule == null){
            Schedule::where( 'date', '<', Carbon::now()->subDays(1))->delete();
            $schedule = new Schedule();
            $schedule->name = 'active_date';
            $schedule->user_id = $authUser->id;
            $schedule->date = Carbon::now();
            $schedule->save();

            // $pending = Status::where('name','Pending')->first();
            // $inprogress = Status::where('name','In Progress')->first();
            // $review = Status::where('name','Review')->first();
            // $bug = Status::where('name','Bug')->first();
            $completed = Status::where('name','Completed')->first();

            // Task::where('status_id', $pending->id)
            //     ->orWhere('status_id', $inprogress->id)
            //     ->update(['active_date' => Carbon::now()]);

            
            //before change
            // Task::where('user_id', $authUser->id)
            // ->where( 'active_date', '<', Carbon::now())
            // ->where(function ($query) use ($pending,$inprogress,$review) {
            //     $query->where('status_id', $pending->id)
            //           ->orWhere('status_id', $inprogress->id)
            //           ->orWhere('status_id', $bug->id)
            //           ->orWhere('status_id', $review->id);
            // })->update(['active_date' => Carbon::now()]);

            //after change
            Task::where('user_id', $authUser->id)
            ->where( 'active_date', '<', Carbon::now())
            ->where(function ($query) use ($completed) {
                $query->where('status_id','!=', $completed->id)
                      // ->orWhere('status_id', $inprogress->id)
                      // ->orWhere('status_id', $bug->id)
                      // ->orWhere('status_id', $review->id)
                      ;
            })->update(['active_date' => Carbon::now()]);

            // $tasks = Task::where('status_id', $pending->id)
            //     ->orWhere('status_id', $inprogress->id)->get();

            // if($tasks != null){
            //     foreach ($tasks as $task) {
            //         $tasks->active_date = Carbon::now();
            //         $task->save();
            //     }
            // }
        }

        return $next($request);
    }
}
