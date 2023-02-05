<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'user_id', 'status', 'redeemed_at'];

    public function redeemedBy() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
