<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('staffs', function (Blueprint $table) {
            $table->string('nik', 20)->primary();
            $table->string('name');
            $table->enum('sex', ['M', 'F'])->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('staffs');
    }
};
