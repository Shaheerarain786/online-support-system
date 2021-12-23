<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'customer_id',
       
    ];

    public function answers(){

        return $this->hasMany(Answer::class);
    
    }
    public function customer(){
        return $this->belongsTo(User::class);
    }
    public function latestReply(){
        return $this->hasOne(Answer::class)->latest();
    }
}
