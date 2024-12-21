<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'total_amount',
        'payment_method',
        'address',
        'status',
        'user_id',
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
