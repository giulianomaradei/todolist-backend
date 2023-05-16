<?php

namespace App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['comment','user_id'];

    public function user(){
        return $this->belongsTo('App\Models\User\User', 'user_id', 'id');
    }
    

    public function scopeByUserId($query, $userId){
        return $query->whereHas('user',function($query) use ($userId){
            return $query->where('user_id', $userId);
        });
    }


}
