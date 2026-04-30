<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Field extends Model {
    protected $fillable = ['category_id', 'name', 'image_url', 'description', 'price_per_hour'];
    
    public function category() { return $this->belongsTo(Category::class); }
    public function schedules() { return $this->hasMany(Schedule::class); }
    public function facilities() { return $this->hasMany(Facility::class); }
}