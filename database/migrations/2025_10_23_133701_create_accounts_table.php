<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->bigIncrements('id_userAccount'); 
            $table->foreignId('id_user')
                  ->constrained('users') 
                  ->onDelete('cascade'); 
                  
            $table->boolean('is_liquid')->default(true);
            $table->decimal('balance', 20, 2)->default(0);
            $table->string('username')->unique();
            $table->string('password');
            $table->string('status')->default('active');           
            $table->timestamp('tanggal_daftar')->nullable()->useCurrent();
            $table->timestamp('tanggal_update')->nullable();
            $table->softDeletes('tanggal_hapus'); 
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accounts');
    }
};
