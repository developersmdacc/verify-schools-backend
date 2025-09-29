<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('username')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('status')->default('active');
            $table->text('bio')->nullable();
            $table->string('address')->nullable();
            $table->date('dob')->nullable();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn([
                'username', 'phone', 'profile_photo', 
                'status', 'bio', 'address', 'dob', 'last_login_at'
            ]);
        });
    }

};
