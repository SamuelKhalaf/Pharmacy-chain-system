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
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign('notifications_user_id_foreign');
            $table->renameColumn('user_id', 'admin_id');
            $table->renameColumn('message', 'data');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->renameColumn('admin_id', 'user_id');
            $table->renameColumn('data', 'message');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
