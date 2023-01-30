<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'user_id', 'status'];

    public function redeemed() {
        $this->where('status', '=', 'redeemed');
    }

    public function notRedeemed() {
        $this->where('status', '=', 'not_redeemed');
    }

}
