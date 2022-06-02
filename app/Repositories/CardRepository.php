<?php

namespace App\Repositories;

use App\Models\Card;

class CardRepository extends Repository
{
	public function model()
	{
		return app(Card::class);
	}
}