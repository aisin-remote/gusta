<?php

namespace App;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $table = 'checkin';

    protected $guarded = ['id'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
