<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Spent extends Model
{
    use HasFactory;

    protected $table = 'spents';
    public $timestamps = true;

    protected $casts = [
        'cost' => 'float'
    ];

    protected $fillable = [
        'task_id',
        'date',
        'spent_hours',
    ];

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

    // public function setDateAttribute($date)
    // {

    //     if($date == null){
    //         $this->attributes['date'] = Carbon::now();
    //     }else{
    //         $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $date);
    //     }

        
    // }

    // public function getDateAttribute($date)
    // {
    //     $date = new Carbon($date);
    //     return $date->format('d/m/Y');
    // }
}
