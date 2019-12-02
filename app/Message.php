<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'user_id_from', 'user_id_to', 'body' ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }
}
