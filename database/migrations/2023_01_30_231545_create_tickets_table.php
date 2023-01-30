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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique('tickets_idx_unique_code');
            $table->integer('user_id')->index('tickets_idx_user_id');
            $table->enum('status', ['redeemed', 'not_redeemed']);
            $table->timestamps();

            $table->index(['code', 'user_id'], 'tickets_idx_code_user_id');
            $table->index('status', 'tickets_idx_status');
            $table->index(['code', 'user_id', 'status'], 'tickets_idx_code_user_id_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
