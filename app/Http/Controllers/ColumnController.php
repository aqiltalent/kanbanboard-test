<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColumnCollection;
use App\Models\Column;
use App\Repositories\ColumnRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ColumnController extends Controller
{
	protected ColumnRepository $columnRepo;

	public function __construct(ColumnRepository $columnRepository)
	{
		$this->columnRepo = $columnRepository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
	 */
	public function index()
	: JsonResponse
	{
		$allColumns = $this->columnRepo->getAllColumns();

		return response()->json([
			'result' => 'success',
			'data'   => new ColumnCollection($allColumns)
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	: Response
	{
		//
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
			'column_title' => ['required']
		]);

		$this->columnRepo->model()->create([
			'user_id'      => 1, // TODO: auth()->user()->id
			'column_title' => $request->input('column_title'),
			'column_order' => ($this->columnRepo->count() + 1),
		]);

		return response()->json([
			'result'  => 'success',
			'columns' => new ColumnCollection($this->columnRepo->getAllColumns())
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Column $column
	 * @return Response
	 */
	public function show(Column $column)
	: Response
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Column $column
	 * @return Response
	 */
	public function edit(Column $column)
	: Response
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param Column $column
	 * @return Response
	 */
	public function update(Request $request, Column $column)
	: Response
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Column $column
	 * @return JsonResponse
	 */
	public function destroy(Column $column)
	: JsonResponse
	{
		$column->cards()->delete();

		$column->delete();

		return response()->json([
			'result'  => 'success',
			'columns' => new ColumnCollection($this->columnRepo->getAllColumns())
		]);
	}
}
