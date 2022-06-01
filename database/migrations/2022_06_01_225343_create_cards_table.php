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
	{
		Schema::create('cards', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('user_id')->index();
			$table->bigInteger('column_id')->index();
			$table->string('card_title');
			$table->longText('card_description');
			$table->text('card_attachment_file');
			$table->tinyInteger('card_order');
			$table->enum('card_priority', ['normal', 'hot', 'bug'])->default('normal');
			$table->bigInteger('assigned_user')->index();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('cards');
	}
};
