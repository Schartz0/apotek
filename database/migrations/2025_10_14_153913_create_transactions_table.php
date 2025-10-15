<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Informasi umum transaksi
            $table->string('ref_no')->index();
            $table->string('channel')->nullable();
            $table->timestamp('created_at_manual')->nullable();
            $table->string('created_by')->nullable();

            // Informasi klien
            $table->string('client_name')->nullable(); 
            $table->integer('age')->nullable();
            $table->string('occupation')->nullable();
            $table->enum('sex', ['M', 'F'])->nullable();

            // Produk (service atau med)
            $table->enum('product_type', ['service', 'med']);
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->integer('qty')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->string('duration')->nullable();

            // Jadwal (khusus service)
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();

            // Staff dan lokasi
            $table->string('staff_nik');
            $table->string('location');

            // Status transaksi
            $table->enum('status', ['NEW', 'COMPLETED','CANCELLED'])->default('NEW');

            $table->timestamps();

            // // Relasi foreign key
            // $table->foreign('staff_nik')
            //       ->references('nik')
            //       ->on('staffs')
            //       ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
