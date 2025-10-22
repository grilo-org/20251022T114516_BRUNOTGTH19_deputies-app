<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('external_id')->nullable();
            $table->foreignId('deputy_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 10, 2);
            $table->date('date');
            $table->string('description');
            $table->string('document_url')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_document')->nullable();
            $table->string('expense_type');
            $table->integer('year');
            $table->integer('month');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};