<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $guarded = ['id'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
