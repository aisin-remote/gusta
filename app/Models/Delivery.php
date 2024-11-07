<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    // Guarded fields (no mass assignment for id)
    protected $guarded = ['id'];

    /**
     * One-to-many inverse relationship to the user (visitor)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * One-to-many inverse relationship to the PIC (Person in Charge)
     */
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id', 'id');
    }

    /**
     * One-to-many inverse relationship to the department
     */

}
