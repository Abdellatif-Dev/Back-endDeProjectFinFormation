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
        Schema::create('devoir_d_execution', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('restaurant_id');
            $table->foreign('restaurant_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            $table->integer('mois'); 
            $table->year('annee');
            
            $table->decimal('montant', 10, 2);
            
            $table->enum('etat', ['payé', 'non payé'])->default('non payé');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoir_d_execution');
    }
};
