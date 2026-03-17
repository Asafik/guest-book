<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('Buku Tamu Digital');
            $table->string('institution_name')->default('Jember Command Center');
            $table->string('institution_short')->default('JCC');
            $table->string('year')->default('2026');
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('qr_url')->nullable()->default('/');
            $table->string('qr_path')->nullable();
            $table->integer('scan_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
