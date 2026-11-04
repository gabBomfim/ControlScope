<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('controls', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('function');
            $table->string('category');
            $table->string('title');
            $table->text('description');
            $table->text('recommendation');
            $table->string('status')->default('not_started');
            $table->timestamps();
            $table->index(['function', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controls');
    }
};
