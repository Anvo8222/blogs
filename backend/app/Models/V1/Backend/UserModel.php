<?php

namespace App\Models\V1\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\V1\Backend\SessionUserModel;
use App\Models\V1\Client\CommentModel;
use App\Models\V1\Client\PostModel;

class UserModel extends Model
{
    use HasFactory;
    protected $table = "users";
    public $timestamp = true;
    protected $fillable = ['id', 'name', 'email', 'password', 'phone', 'avatar', 'status', 'token', 'level'];

    public function session_users()
    {
        return $this->hasOne(SessionUserModel::class, 'user_id', 'id');
    }
    public function posts()
    {
        return $this->hasMany(PostModel::class, 'user_id');
    }
    public function comments()
    {
        return $this->hasMany(CommentModel::class, 'user_id', 'id');
    }
}
