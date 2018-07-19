<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    public $timestamps = 'true';



    public function categories() {

    	return $this->belongsToMany('App\Models\Category', 'blog_categories');
    }

    public function author(){

    	return $this->belongsTo('App\User', 'author_id');
    }

    public function getCreatedAtAttribute() {

    return date('Y-m-d', strtotime($this->attributes['created_at']));
  }

   public function getUpdatedAtAttribute() {

    return date('Y-m-d', strtotime($this->attributes['updated_at']));
  }
}
