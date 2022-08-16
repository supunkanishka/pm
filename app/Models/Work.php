<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $table = 'works';
    public $timestamps = true;

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
        'active_date'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }
}
