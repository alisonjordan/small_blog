<?php

namespace App\Models;

use App\Models\User;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    use Searchable;
    

    protected $fillable = [
        'title',
        'body',
        'user_id',
    ];

    public function toSearchableArray(){
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }

    public function authorPost(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
