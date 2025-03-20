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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->onDelete('cascade');
            $table->string('reference_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('method');
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->string('token')->unique();
            $table->string('url')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamp('expired_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
