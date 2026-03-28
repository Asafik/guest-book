<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('full_name');
            $table->text('address');
            $table->string('institution')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->enum('purpose', ['coordination', 'audience', 'monitoring', 'meeting', 'visit', 'other']);
            $table->string('meet_with')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
