<?php

namespace App\Models\V1\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\V1\Backend\UserModel;

class SessionUserModel extends Model
{
    use HasFactory;
    protected $table = "session_users";
    public $timestamp = true;
    protected $fillable = ['id', 'token', 'refresh_token', 'token_expired', "user_id", "refresh_token_expired"];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id', 'user_id');
    }
}