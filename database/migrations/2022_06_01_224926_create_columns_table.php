<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	: void
	{
		Schema::create('columns', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('user_id')->index();
			$table->string('column_title');
			$table->tinyInteger('column_order');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	: void
	{
		Schema::dropIfExists('columns');
	}
};
