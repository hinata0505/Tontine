<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('distributions')) {
            Schema::create('distributions', function (Blueprint $table) {
                $table->id('id_distrib');
                $table->date('mois')->unique();
                $table->decimal('montant_remis', 10, 2);
                $table->dateTime('date_distribution')->useCurrent();
                $table->unsignedBigInteger('id_memb');

                $table->foreign('id_memb')
                    ->references('id_memb')
                    ->on('membres')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};