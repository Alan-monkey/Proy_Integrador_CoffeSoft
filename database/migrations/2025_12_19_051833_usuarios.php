<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // id (PK)
            $table->string('user_name', 100)->unique();
            $table->string('user_pass', 255);
            $table->string('user_tipo', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
