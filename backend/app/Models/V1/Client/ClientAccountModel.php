<?php

namespace App\Models\V1\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserFactory;

class ClientAccountModel extends Model
{
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    use HasFactory;
    protected $table = "users";
    public $timestamp = true;
    protected $fillable = ['id', 'name', 'email', 'password', 'phone', 'avatar', 'token', 'level'];
}
