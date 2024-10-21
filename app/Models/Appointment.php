<?php

namespace App\Models;

use App\Card;
use App\User;
use App\Guest;
use App\Checkin;
use App\RoomDetail;
use App\FacilityDetail;
use App\ApprovalHistory;
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
    
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }
    
    public function guests()
    {
        return $this->hasMany(Guest::class);
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
