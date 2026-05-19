<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
*/


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispatch_evidences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatch_confirmation_id');
            $table->string('image_path', 1024);
            $table->string('mime_type', 50)->nullable();
            $table->integer('size_int')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('dispatch_confirmation_id', 'fk_de_confirmation')->references('id')->on('dispatch_confirmations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_evidences');
    }
};
