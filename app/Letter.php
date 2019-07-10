<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    public $guarded = [];
    public function access()
    {
        return $this->belongsTo('App\Access');
    }
}
