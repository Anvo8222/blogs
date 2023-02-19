<?php

namespace App\Models\V1\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\PostCategoryFactory;

class CategoryPostModel extends Model
{
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PostCategoryFactory::new();
    }
    use HasFactory;
    protected $table = "category_post";
    public $timestamp = true;
}
