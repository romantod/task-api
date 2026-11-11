<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
    ];


    protected $casts = [
        'status' => 'string',
        'priority' => 'string',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function sharedWith() {
        return $this->belongsToMany(User::class, 'task_user');
    }


}
