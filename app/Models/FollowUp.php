<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class FollowUp extends Model
{
    use HasFactory;

    protected $table = 'tr_agent_follow_up';

    protected $guarded = [];

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }
}
