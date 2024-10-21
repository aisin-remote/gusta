<?php

namespace App;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';

    protected $guarded = ['id'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
