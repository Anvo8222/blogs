<?php

namespace App\Models\V1\Client;

use App\Models\V1\Backend\SessionUserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\CommentFactory;

class CommentModel extends Model
{

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return CommentFactory::new();
    }

    use HasFactory;
    protected $table = "comments";
    public $timestamp = true;
    protected $fillable = ['id', 'content', 'id_parent', 'user_id', 'post_id', 'created_at'];
    public function post()
    {
        return $this->belongsTo(PostModel::class);
    }
    public function users()
    {
        return $this->belongsTo(SessionUserModel::class, 'user_id', 'id');
    }
}
