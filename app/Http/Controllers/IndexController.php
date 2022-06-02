<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColumnCollection;
use App\Repositories\ColumnRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Spatie\DbDumper\Databases\MySql;

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

	/**
	 * Dump Database
	 *
	 * @return void
	 */
	public function dumpDatabase()
	: void
	{
		$dbName = config('database.connections.mysql.database');

		MySql::create()
			->setDbName($dbName)
			->setUserName(config('database.connections.mysql.username'))
			->setPassword(config('database.connections.mysql.password'))
			->dumpToFile("dump_{$dbName}.sql");
	}
}
