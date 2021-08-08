<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;
    //
    const SEARCHABLE_FIELDS = ['title', 'body'];

    public function toSearchableArray()
    {
        return $this->only(self::SEARCHABLE_FIELDS);
    }

    public function searchableAs()
    {
        return 'posts_index';
    }
}
