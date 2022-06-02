<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColumnCollection;
use App\Repositories\ColumnRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Spatie\DbDumper\Databases\MySql;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
	 * @return BinaryFileResponse
	 */
	public function dumpDatabase()
	: BinaryFileResponse
	{
		$dbName = config('database.connections.mysql.database');
		$file = "dump_{$dbName}.sql";

		MySql::create()
			->setDbName($dbName)
			->setUserName(config('database.connections.mysql.username'))
			->setPassword(config('database.connections.mysql.password'))
			->dumpToFile($file);

		$headers = [
			'Content-Type: application/sql',
		];

		return response()->download(public_path() . "/{$file}", "{$dbName}.sql", $headers);
	}
}
