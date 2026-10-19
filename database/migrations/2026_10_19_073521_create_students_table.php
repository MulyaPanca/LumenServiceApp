<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('nim', 20)->unique();
        $table->string('name', 100);
        $table->string('email', 100)->unique();
        $table->string('phone', 20);
        $table->text('address');
        $table->enum('gender', array('Laki-laki', 'Perempuan'));
        $table->date('birth_date');
        $table->string('major', 100);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
