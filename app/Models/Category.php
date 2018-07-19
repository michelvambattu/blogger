<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';



    public function blogs() {

    	return $this->belongsToMany('App\Models\Blog', 'blog_categories');
    }
}
