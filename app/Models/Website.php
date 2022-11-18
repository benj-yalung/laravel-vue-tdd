<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'title',
        'description',
        'featured_image',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'author_id');
    }

    public function post()
    {
        return $this->hasMany('App\Models\WebsitePost', 'website_id', 'id')->orderBy('id', 'DESC');
    }

    public function subscribers()
    {
        return $this->hasMany('App\Models\WebsiteSubscriber', 'website_id', 'id');
    }
}
