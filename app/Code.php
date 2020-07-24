<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Code extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'code', 'capacity', 'enable', 'created_by', 'updated_by'];

    protected $withCount = ['customers'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modifier()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers()
    {
       return $this->hasMany(Customer::class);
    }
}
