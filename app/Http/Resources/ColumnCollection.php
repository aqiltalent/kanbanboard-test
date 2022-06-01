<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ColumnCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request)
	: array
	{
		return [
			'columns'    => $this->collection->map(fn($el) => new ColumnResource($el)),
			'pagination' => [
				'total'        => $this->total(),
				'count'        => $this->count(),
				'per_page'     => $this->perPage(),
				'current_page' => $this->currentPage(),
				'last_page'    => $this->lastPage(),
			]
		];
	}
}
