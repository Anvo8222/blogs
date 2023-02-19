<?php

namespace App\Models\V1\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\V1\Client\PostModel;

class ChapterModel extends Model
{
    use HasFactory;
    protected $table = "chapters";
    public $timestamp = true;
    protected $fillable = ['id', 'title', 'description', 'image', 'content', 'slug-chapter', 'post_id'];
    public function posts()
    {
        return $this->belongsTo(PostModel::class, 'post_id', 'id');
    }
}
