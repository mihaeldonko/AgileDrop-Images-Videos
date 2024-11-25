<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title');              
            $table->text('description')->nullable();
            $table->string('file_path');      
            $table->string('file_type');    
            $table->unsignedBigInteger('file_size'); 
            $table->timestamps();          
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
};
