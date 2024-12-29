<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Movie extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'movie';

    protected $fillable = [
        'title',
        'summary',
        'poster',
        'genre_id',
        'year'
    ];

    protected $primaryKey = 'id';
    public $timestamps = false;

    public function genre(){
        return $this->belongsTo(Genres::class, 'genre_id');
    }

    public function list_reviews(){
        return $this->hasMany(Reviews::class, 'movie_id');
    }

    public function list_cast(){
        return $this->belongsToMany(Casts::class,'cast_movie','movie_id','cast_id');
    }
}