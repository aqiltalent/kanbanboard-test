<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColumnCollection;
use App\Models\Card;
use App\Repositories\CardRepository;
use App\Repositories\ColumnRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
	protected CardRepository   $cardRepo;
	protected ColumnRepository $columnRepo;

	public function __construct(CardRepository $cardRepository, ColumnRepository $columnRepository)
	{
		$this->cardRepo = $cardRepository;
		$this->columnRepo = $columnRepository;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request)
	: JsonResponse
	{
		$request->validate([
			'card_title'       => ['required'],
			'card_description' => ['required'],
		]);

		$this->cardRepo->model()->insertGetId([
			'user_id'              => 1,
			'column_id'            => $request->input('column_id'),
			'card_title'           => $request->input('card_title'),
			'card_description'     => $request->input('card_description'),
			'card_order'           => $request->input('card_order'),
			'card_attachment_file' => '',
			'assigned_user'        => 0
		]);

		return response()->json([
			'result' => 'success',
			'columns'   => new ColumnCollection($this->columnRepo->getAllColumns())
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param Card $card
	 * @return JsonResponse
	 */
	public function update(Request $request, Card $card)
	: JsonResponse
	{
		$request->validate([
			'card_title'       => ['required'],
			'card_description' => ['required'],
		]);

		$card->column_id = $request->input('column_id');
		$card->card_title = $request->input('card_title');
		$card->card_description = $request->input('card_description');
		$card->card_order = $request->input('card_order');
		$card->save();

		return response()->json([
			'result' => 'success'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Card $card
	 * @return JsonResponse
	 */
	public function destroy(Card $card)
	: JsonResponse
	{
		$card->delete();

		return response()->json([
			'result' => 'success',
			'columns'   => new ColumnCollection($this->columnRepo->getAllColumns())
		]);
	}
}
