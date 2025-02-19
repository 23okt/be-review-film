<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Reviews extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'reviews';

    protected $fillable = ['critic', 'rating', 'user_id' , 'movie_id'];

    protected $primaryKey = 'id';

    public $timestamps = false;
}