<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['phone'];

    public function code()
    {
        return $this->belongsTo(Code::class);
    }
}
