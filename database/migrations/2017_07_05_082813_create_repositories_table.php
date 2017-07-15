<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRepositoriesTable extends Migration {

	public function up()
	{
		Schema::create('repositories', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('type')->default('git');
			$table->text('url');
			$table->boolean('private');
			$table->integer('user_id')->unsigned();
			$table->text('folder');
            $table->text('params-file-path');
            $table->text('specifications-file-path');
        });
	}

	public function down()
	{
		Schema::drop('repositories');
	}
}