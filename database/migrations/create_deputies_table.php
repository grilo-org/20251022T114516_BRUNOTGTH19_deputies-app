<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deputies', function (Blueprint $table) {
            $table->id();
            $table->integer('external_id')->unique();
            $table->string('name');
            $table->string('party', 10);
            $table->string('state', 2);
            $table->string('photo_url');
            $table->string('email')->nullable();
            $table->integer('legislature_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deputies');
    }
};