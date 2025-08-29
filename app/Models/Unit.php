<?php

namespace App\Models;

use App\Models\Concerns\OwnedByUser;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use OwnedByUser;
    protected $fillable = ['code','name','level','user_id'];
public function subjects(){ return $this->hasMany(Subject::class); }
}
