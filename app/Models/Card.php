<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id', 'column_id', 'card_title', 'card_description', 'card_attachment_file', 'card_order', 'card_priority', 'assigned_user'
	];

	protected $casts = [
		'column_id' => 'int'
	];

	public function column()
	: BelongsTo
	{
		return $this->belongsTo(Column::class, 'column_id');
	}

	public function comments()
	: HasMany
	{
		return $this->hasMany(Comment::class, 'card_id');
	}
}
