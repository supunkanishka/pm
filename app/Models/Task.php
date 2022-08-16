<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    public $timestamps = true;

    protected $appends = array('punishment_hours');

    //protected $appends = array('spent_hours');

    protected $casts = [
        'cost' => 'float'
    ];

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'user_id',
        'status_id',
        'start_date',
        'due_date',
        'estimated_hours',
        'active_date',
        'meeting'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function sprint()
    {
        return $this->belongsTo('App\Models\Sprint');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function spents()
    {
        return $this->hasMany('App\Models\Spent');
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image');
    }

    //start date

    // public function setStartDateAttribute($date)
    // {

    //     if($date == null){
    //         $this->attributes['start_date'] = "";
    //     }else{
    //         $this->attributes['start_date'] = Carbon::createFromFormat('d/m/Y', $date);
    //     }

        
    // }

    // public function getStartDateAttribute($date)
    // {
    //     $date = new Carbon($date);
    //     return $date->format('d/m/Y');
    // }

    // //due date
    // public function setDueDateAttribute($date)
    // {
    //     if($date == null){
    //         $this->attributes['due_date'] = "";
    //     }else{
    //         $this->attributes['due_date'] = Carbon::createFromFormat('d/m/Y', $date);
    //     }

        
    // }

    // public function getDueDateAttribute($date)
    // {
    //     $date = new Carbon($date);
    //     return $date->format('d/m/Y');
    // }

    // public function setProjectIdAttribute($id)
    // {
    //     //dd($id);

    //     // if($statusId == null || $statusId == ""){
    //     //     $this->attributes['status_id'] = 1;
    //     // }
        
    // }

    public function getPunishmentHoursAttribute($value) {
        if($this->spent_hours == null || $this->spent_hours == ""){
            return ($this->estimated_hours*1.1);
        }
        
        return 0;
    }
}
