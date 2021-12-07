<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    public $timestamps = true;

    public const USER_FRIEND_CONFIRMED_STATUS = 'confirmed';
    public const USER_FRIEND_PENDING_STATUS = 'pending';
    public const USER_FRIEND_BLOCKED_STATUS = 'blocked';
}
