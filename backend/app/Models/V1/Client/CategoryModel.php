<?php

namespace App\Models\V1\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\V1\Client\PostModel;

class CategoryModel extends Model
{
    use HasFactory;
    protected $table = "categories";
    public $timestamp = true;
    protected $fillable = ['id', 'name', 'description', 'slug'];
    public function posts()
    {
        return $this->belongsToMany(PostModel::class, 'category_post', 'post_id', 'category_id');
    }
}
