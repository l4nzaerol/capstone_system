<?php

// database/migrations/xxxx_xx_xx_create_productions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->date('date');
            $table->enum('stage', ['Preparation', 'Assembly', 'Finishing', 'Quality Control']);
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Hold'])->default('Pending');
            $table->integer('quantity')->default(0);
            $table->json('resources_used')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('productions');
    }
};