<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';
    public function items()
    {
        return $this->belongsToMany('App\Item', 'item_tag_link');
    }
}
