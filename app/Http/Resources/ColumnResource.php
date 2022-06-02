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
			'cards' => $this->cards->map(function ($card) {
				return [
					'id'               => $card->id,
					'column_id'        => $card->column_id,
					'card_title'       => $card->card_title,
					'card_description' => $card->card_description,
					'card_order'       => $card->card_order,
					'comments'         => $card->comments()
						->orderBy('created_at')
						->get()
						->map(function ($comment) {
							return [
								'id'          => $comment->id,
								'card_id'     => $comment->card_id,
								'description' => $comment->description,
								'created_at'  => $comment->created_at,
							];
						}),
				];
			}),
		];
	}
}
