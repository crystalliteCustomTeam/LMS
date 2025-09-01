<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->integer('user_ID');
            $table->string('profileImage');
            $table->string('gender');
            $table->date('dob');
            $table->string('userType')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('zipCode')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile');
    }
};
