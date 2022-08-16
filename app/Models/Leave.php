<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';
    public $timestamps = true;

    //protected $appends = array('spent_hours');

    protected $casts = [
        'cost' => 'float'
    ];

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'date',
        'type',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function typemodel()
    {
        return $this->belongsTo('App\Models\Type','type');
    }
}
