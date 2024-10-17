<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $guarded = ['id'];

    public function room_detail()
    {
        return $this->hasMany(RoomDetail::class);
    }
}
