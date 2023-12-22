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
        Schema::connection('student_service')->create('student_documents', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('student_id');
            $table->string('profile_picture')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('nic_father')->nullable();
            $table->string('nic_mother')->nullable();
            $table->string('marriage_certificate')->nullable();
            $table->string('permission_letter')->nullable();
            $table->string('leaving_certificate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
