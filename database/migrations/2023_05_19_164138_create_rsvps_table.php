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
        Schema::create('rsvps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->string('name');
            $table->string('number');
            $table->boolean('notification_sent')->default(false);
            $table->boolean('viewed')->default(false);
            $table->timestamp('viewed_on')->nullable();
            $table->boolean('will_attend')->default(false);
            $table->integer('number_attending')->default(0);
            $table->string('short_id')->unique();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvps');
    }
};
