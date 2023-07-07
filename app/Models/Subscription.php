<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(SubscriptionItem::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
