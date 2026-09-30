<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'id_user');
            $table->renameColumn('password', 'mot_de_passe');

            $table->string('code_connexion', 50)->unique()->after('id_user');
            $table->enum('role', ['admin', 'membre'])
                ->default('membre')
                ->after('mot_de_passe');

            $table->unsignedBigInteger('id_memb')
                ->unique()
                ->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['code_connexion']);
            $table->dropUnique(['id_memb']);

            $table->dropColumn(['code_connexion', 'role', 'id_memb']);

            $table->renameColumn('id_user', 'id');
            $table->renameColumn('mot_de_passe', 'password');
        });
    }
};