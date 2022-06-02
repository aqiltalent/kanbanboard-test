<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class IndexController extends Controller
{
	/**
	 * Index
	 *
	 * @return Factory|View|Application
	 */
	public function index()
	: Factory|View|Application
	{
		return view('app');
	}
}
