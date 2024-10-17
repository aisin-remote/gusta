<?php

namespace App\Models;

use App\ApprovalHistory;
use App\Checkin;
use App\FacilityDetail;
use App\RoomDetail;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //
    protected $guarded = ['id'];

    // one to many (inverse) relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id', 'id');
    }
    public function checkin()
    {
        return $this->hasOne(Checkin::class);
    }

    public function room_detail()
    {
        return $this->hasOne(RoomDetail::class);
    }

    public function facility_detail()
    {
        return $this->hasOne(FacilityDetail::class);
    }

    public function approval_history()
    {
        return $this->hasOne(ApprovalHistory::class);
    }
}
