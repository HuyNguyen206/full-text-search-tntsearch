<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Comment extends Model
{
    //
    use Searchable;
    const SEARCHABLE_FIELDS = ['id', 'body'];

    public function toSearchableArray()
    {
        return $this->only(self::SEARCHABLE_FIELDS);
    }
}
