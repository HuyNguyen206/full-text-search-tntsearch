<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;
use App\Models\Comment;
use App\Models\Post;
use Laravel\Scout\Searchable;

class Multiple extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        Comment::class,
        Post::class
//    'Comment',
//        'Post'
    ];

    public function shouldBeSearchable()
    {
        // Check if the class uses the Searchable trait before calling shouldBeSearchable
//        if (array_key_exists(Searchable::class, class_uses($this->model))) {
//            return $this->model->shouldBeSearchable();
//        }
       return true;
    }
}
