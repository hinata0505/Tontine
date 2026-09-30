<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('membres')) {
            Schema::create('membres', function (Blueprint $table) {
                $table->id('id_memb');
                $table->string('nom_memb', 100);
                $table->string('telephone', 20);
                $table->integer('ordre_tour')->unique();
                $table->enum('frequence_cotisation', ['jour', 'semaine', 'mois']);
                $table->decimal('montant_cotisation', 10, 2);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};