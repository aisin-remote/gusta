<?php

namespace App;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    protected $table = 'approval_histories';

    protected $guarded = ['id'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
