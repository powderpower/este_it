<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item';
    protected $fillable = ['show_count'];
    public $timestamps = false;
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'item_tag_link');
    }
}
