<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'ms_customer';

    protected $guarded = [];

    /**
     *
     */
    public function agent()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public function follow_up()
    {
        return $this->hasMany(FollowUp::class);
    }
}
