<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=['name','description','price','category_id'];
    protected $hidden = ['created_at','updated_at'];
    
    public function product()
    {
        return $this->belongTo(Category::class,'category_id');
    }
}
