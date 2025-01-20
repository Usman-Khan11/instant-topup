<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('sitename', 150)->nullable();
            $table->string('logo', 150)->nullable();
            $table->text('mail_config')->nullable();
            $table->integer('page_length')->default(10);
            $table->timestamps();
        });

        DB::table('general_settings')->insert([
            [
                'sitename' => 'Instant Topup',
                'logo' => 'assets/img/logo.png',
                'mail_config' => '',
                'page_length' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
