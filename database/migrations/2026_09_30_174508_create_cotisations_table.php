<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cotisations')) {
            Schema::create('cotisations', function (Blueprint $table) {
                $table->id('id_coti');
                $table->date('mois');
                $table->decimal('montant', 10, 2);
                $table->dateTime('date_versement')->useCurrent();
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
        Schema::dropIfExists('cotisations');
    }
};