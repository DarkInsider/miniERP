<?php

use Illuminate\Support\Facades\Schema;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    static private $superUserName = 'SuperAdministrator';
    static private $superUserEmail = 'dev@gmail.com';
    static private $superUserPassword = 'password';


    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', array('user','administrator'))->default('user');
            $table->string('token')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });
        User::create([
            'id' => 1,
            'name' => CreateUsersTable::$superUserName,
            'email' => CreateUsersTable::$superUserEmail,
            'password' => md5(CreateUsersTable::$superUserPassword),
            'role' => 'administrator',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
