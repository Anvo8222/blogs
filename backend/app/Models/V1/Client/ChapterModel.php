<?php

namespace App\Models\V1\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\V1\Client\PostModel;
use Database\Factories\ChapterFactory;

class ChapterModel extends Model
{
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ChapterFactory::new();
    }
    use HasFactory;
    protected $table = "chapters";
    public $timestamp = true;
    protected $fillable = ['id', 'title', 'description', 'image', 'content', 'slug-chapter', 'post_id'];
    public function posts()
    {
        return $this->belongsTo(PostModel::class, 'post_id', 'id');
    }
}
