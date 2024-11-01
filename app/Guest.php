<?php

namespace App;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $table = 'guests';

    protected $guarded = ['id'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function card_status()
    {
        return $this->hasMany(CardStatus::class);
    }
}
