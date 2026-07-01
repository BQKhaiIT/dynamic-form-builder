<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_fields', function (Blueprint $table) {

            $table->id();

            $table->foreignId('form_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('label');

            $table->enum('type', [
                'text',
                'number',
                'date',
                'color',
                'select',
            ]);

            $table->string('placeholder')->nullable();

            $table->boolean('required')->default(false);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
