<?php

namespace App\Repositories;

use App\Models\Column;

class ColumnRepository extends Repository
{
	public function model()
	{
		return app(Column::class);
	}

	/**
	 * Get all columns
	 */
	public function getAllColumns()
	{
		return $this->model()
			->with([
				'cards' => function ($q) {
					$q->orderBy('card_order');
				}
			])
			->orderBy('column_order')
			->get();
	}
}