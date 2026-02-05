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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name_store')->nullable();
            $table->string('logo_store')->nullable();
            $table->string('addres')->nullable();
            $table->string('no_tlp')->nullable();
            $table->enum('themes',['light' , 'dark' , 'default' , 'all'])->nullable();
            $table->json('lists_menus_sidebar')->nullable();
            $table->json('lists_pages')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
