<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomDetail extends Model
{
    protected $table = 'room_details';

    protected $guarded = ['id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
