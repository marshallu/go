<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $keyType = 'string';
    public $incrementing = false;

    public $possibleCharacters = 'abcdefghijkmnopqrstuvwxyz234567890';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
