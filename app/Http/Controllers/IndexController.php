<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColumnCollection;
use App\Repositories\ColumnRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class IndexController extends Controller
{
	protected ColumnRepository $columnRepo;

	public function __construct(ColumnRepository $columnRepository)
	{
		$this->columnRepo = $columnRepository;
	}

	/**
	 * Index
	 *
	 * @return Factory|View|Application
	 */
	public function index()
	: Factory|View|Application
	{

		$columns = new ColumnCollection($this->columnRepo->getAllColumns());

		return view('page.board', compact('columns'));
	}
}
