<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Column extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id', 'column_title', 'column_order'
	];

	protected $casts = [
		'user_id' => 'int'
	];

	public function cards()
	: HasMany
	{
		return $this->hasMany(Card::class, 'column_id');
	}
}
