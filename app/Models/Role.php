<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Role extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'role';

    protected $fillable = [
        'name'
    ];

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    public function user(){
        return $this->hasMany(User::class, 'role_id');
    }
}