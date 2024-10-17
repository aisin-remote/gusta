<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';

    protected $guarded = ['id'];

    public function facility_detail()
    {
        return $this->hasMany(FacilityDetail::class);
    }
}
