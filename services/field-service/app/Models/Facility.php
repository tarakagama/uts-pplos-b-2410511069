<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model {
    protected $fillable = ['field_id', 'facility_name'];
    public function field() { return $this->belongsTo(Field::class); }
}