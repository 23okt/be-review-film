<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Casts extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'casts';

    protected $fillable = [
        'name',
        'age',
        'bio'
    ];

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    public function casts(){
        return $this->hasMany(CastMovie::class);
    }
}