<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardStatus extends Model
{
    protected $table = 'card_statuses';

    protected $guarded = ['id'];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
