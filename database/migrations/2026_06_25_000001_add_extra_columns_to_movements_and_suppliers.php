<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->string('document', 20)->nullable()->after('amount');
            $table->string('number_vol', 20)->nullable()->after('document');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->unsignedBigInteger('cooperative_id')->nullable()->after('person_id');
            $table->string('file', 150)->nullable()->after('cooperative_id');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['cooperative_id']);
            $table->dropColumn(['cooperative_id', 'file']);
        });

        Schema::table('movements', function (Blueprint $table) {
            $table->dropColumn(['document', 'number_vol']);
        });
    }
};
