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
		Schema::create('comments', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('user_id')->index();
			$table->bigInteger('card_id')->index();
			$table->longText('description');
			$table->text('attachment_file');
			$table->bigInteger('replied_user')->index();
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
		Schema::dropIfExists('comments');
	}
};
