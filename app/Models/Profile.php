<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Profile extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'profile';

    protected $fillable = ['biodata','age','address','user_id'];

    protected $primaryKey = 'id';

    public $timestamps = false;
}