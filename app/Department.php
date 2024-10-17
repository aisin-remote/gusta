<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
