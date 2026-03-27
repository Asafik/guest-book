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
            $table->string('app_name')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('institution_short')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('qr_path')->nullable();
            $table->unsignedBigInteger('scan_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
