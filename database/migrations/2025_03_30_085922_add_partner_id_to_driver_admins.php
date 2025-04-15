<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('driver_admins', function (Blueprint $table) {
            $table->unsignedBigInteger('partner_id')->nullable()->after('id');
            $table->foreign('partner_id')->references('id')->on('partner_admins')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('driver_admins', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn('partner_id');
        });
    }
};

