<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

	protected $fillable = ['id', 'long_url', 'created_by', 'redirect_count', 'last_redirected_at'];

	protected $keyType = 'string';
	public $incrementing = false;

	public $possibleCharacters = 'abcdefghijkmnopqrstuvwxyz234567890';
}
