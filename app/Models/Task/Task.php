<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['task','user_id','description','type'];

    public function user(){
        return $this->belongsTo('App\Models\User\User', 'user_id', 'id');
    }
    
    public function scopeByTask( $query, $task )
    {
        return $query->where( 'task', 'LIKE', "%$task%" );
    }

    public function scopeByUserId($query, $userId){
        return $query->whereHas('user',function($query) use ($userId){
            return $query->where('user_id', $userId);
        });
    }


}
