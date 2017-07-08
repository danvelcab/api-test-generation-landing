<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name')->unique();
			$table->string('surnames');
			$table->string('email');
			$table->string('password');
			$table->rememberToken('rememberToken');
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}