<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ColumnResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param Request $request
	 * @return array|Arrayable|JsonSerializable
	 */
	public function toArray($request)
	: array|JsonSerializable|Arrayable
	{
		return [
			'id'    => $this->id,
			'title' => $this->column_title,
			'cards' => $this->cards->map(function ($el) {
				return [
					'card_id'          => $el->id,
					'card_title'       => $el->card_title,
					'card_description' => $el->card_description,
					'card_order'       => $el->card_order,
					'comments'         => $el->comments()->orderBy('created_at')->get(),
				];
			}),
		];
	}
}
