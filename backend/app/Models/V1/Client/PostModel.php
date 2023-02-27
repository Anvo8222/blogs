<?php

namespace App\Models\V1\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\V1\Client\CategoryModel;
use App\Models\V1\Backend\UserModel;
use Database\Factories\PostFactory;
use App\Models\V1\Client\ChapterModel;

class PostModel extends Model
{
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PostFactory::new();
    }

    use HasFactory;
    protected $table = "posts";
    public $timestamp = true;
    protected $fillable = ['id', 'name', 'image', 'description', 'status', 'slug', 'user_id'];

    public function categories()
    {
        return $this->belongsToMany(CategoryModel::class, 'category_post', 'post_id', 'category_id');
    }
    public function users()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
    public function chapters()
    {
        return $this->hasMany(ChapterModel::class, 'post_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(CommentModel::class, 'post_id', 'id');
    }
}
