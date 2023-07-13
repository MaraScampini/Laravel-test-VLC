<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multitask extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'status'
    ];

    public function user(){
        return $this->belongsToMany(User::class, 'multitask_user', 'multitask_id', 'user_id');
 }
}
