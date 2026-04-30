<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {
    protected $fillable = ['field_id', 'day', 'start_time', 'end_time', 'is_available'];
    public function field() { return $this->belongsTo(Field::class); }
}