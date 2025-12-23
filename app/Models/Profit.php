<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'total_profit',
        'orders_count',
        'average_order_value'
    ];

    protected $casts = [
        'date' => 'date',
        'total_profit' => 'decimal:2',
        'average_order_value' => 'decimal:2'
    ];

    // Получить прибыль за сегодня
    public static function getTodayProfit()
    {
        return self::firstOrCreate([
            'date' => today()
        ], [
            'total_profit' => 0,
            'orders_count' => 0,
            'average_order_value' => 0
        ]);
    }

    // Обновить прибыль при создании заказа
    public static function updateProfit(Order $order)
    {
        $profit = self::getTodayProfit();
        
        $profit->total_profit += $order->total_amount;
        $profit->orders_count += 1;
        $profit->average_order_value = $profit->orders_count > 0 
            ? $profit->total_profit / $profit->orders_count 
            : 0;
            
        $profit->save();
    }

    // Получить прибыль за период
    public static function getProfitForPeriod($startDate, $endDate)
    {
        return self::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }

    // Получить общую статистику - ИСПРАВЛЕННЫЙ МЕТОД
    public static function getOverallStats()
    {
        $totalProfit = self::sum('total_profit') ?? 0;
        $totalOrders = self::sum('orders_count') ?? 0;
        $averageDailyProfit = self::count() > 0 ? self::avg('total_profit') : 0;
        $bestDay = self::orderBy('total_profit', 'desc')->first();
        
        $currentMonthProfit = self::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('total_profit') ?? 0;
            
        $currentMonthOrders = self::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('orders_count') ?? 0;

        return [
            'total_profit_all_time' => $totalProfit,
            'total_orders_all_time' => $totalOrders,
            'average_daily_profit' => $averageDailyProfit,
            'best_day' => $bestDay,
            'current_month_profit' => $currentMonthProfit,
            'current_month_orders' => $currentMonthOrders,
        ];
    }
}