<?php

namespace App\Models\V1\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionUserModel extends Model
{
    use HasFactory;
    protected $table = "session_users";
    public $timestamp = true;
    protected $fillable = ['id', 'token', 'refresh_token', 'token_expired', 'refresh_token_expired', 'user_id'];
}
