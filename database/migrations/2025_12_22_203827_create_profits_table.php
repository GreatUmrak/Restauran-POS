<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profits', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Дата для ежедневной прибыли
            $table->decimal('total_profit', 12, 2)->default(0); // Общая прибыль за день
            $table->integer('orders_count')->default(0); // Количество заказов
            $table->decimal('average_order_value', 10, 2)->default(0); // Средний чек
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profits');
    }
};