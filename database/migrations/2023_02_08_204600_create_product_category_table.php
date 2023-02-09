<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable($value = false);
            $table->text('description')->nullable($value = false);
            $table->bigInteger('created_by')->index()->nullable($value = true)->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->nullable($value = false)->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->boolean('product_cat_status')->default(1)->nullable($value = false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_category');
    }
};
