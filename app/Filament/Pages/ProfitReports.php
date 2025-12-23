<?php

namespace App\Filament\Pages;

use App\Models\Profit;
use App\Models\Order;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ProfitReports extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected string $view = 'filament.pages.profit-reports';
    
    public $data = [];
    public $dailyProfits = [];
    public $overallStats = [];
    public $topOrders = [];
    public $periodProfit = 0;
    public $periodOrders = 0;
    
    public function mount(): void
    {
        // ТОЛЬКО администраторы могут видеть эту страницу
        abort_unless(Auth::user()->hasRole('admin'), 403);
        
        $this->form->fill([
            'period' => 'today',
            'start_date' => today()->subDays(7),
            'end_date' => today(),
        ]);
        
        $this->loadData();
    }
    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('period')
                    ->label('Период')
                    ->options([
                        'today' => 'Сегодня',
                        'yesterday' => 'Вчера',
                        'week' => 'Эта неделя',
                        'month' => 'Этот месяц',
                        'year' => 'Этот год',
                        'custom' => 'Выбрать период',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function () {
                        $this->loadData();
                    }),
                    
                DatePicker::make('start_date')
                    ->label('Начало периода')
                    ->visible(function ($get) {
                        return $get('period') === 'custom';
                    })
                    ->reactive()
                    ->afterStateUpdated(function () {
                        $this->loadData();
                    }),
                    
                DatePicker::make('end_date')
                    ->label('Конец периода')
                    ->visible(function ($get) {
                        return $get('period') === 'custom';
                    })
                    ->reactive()
                    ->afterStateUpdated(function () {
                        $this->loadData();
                    }),
            ])
            ->statePath('data');
    }
    
    public function loadData(): void
    {
        $period = $this->data['period'] ?? 'today';
        $startDate = $this->data['start_date'] ?? today();
        $endDate = $this->data['end_date'] ?? today();
        
        switch ($period) {
            case 'today':
                $startDate = today();
                $endDate = today();
                break;
            case 'yesterday':
                $startDate = today()->subDay();
                $endDate = today()->subDay();
                break;
            case 'week':
                $startDate = today()->startOfWeek();
                $endDate = today();
                break;
            case 'month':
                $startDate = today()->startOfMonth();
                $endDate = today();
                break;
            case 'year':
                $startDate = today()->startOfYear();
                $endDate = today();
                break;
        }
        
        $this->dailyProfits = Profit::getProfitForPeriod($startDate, $endDate);
        $this->overallStats = Profit::getOverallStats();
        
        // Рассчитываем прибыль за период
        $this->periodProfit = $this->dailyProfits->sum('total_profit');
        $this->periodOrders = $this->dailyProfits->sum('orders_count');
        
        // Получаем топ заказов
        $this->topOrders = Order::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ])
        ->whereIn('status', ['served', 'ready'])
        ->with(['table', 'user'])
        ->orderBy('total_amount', 'desc')
        ->take(10)
        ->get();
    }
    
    public function refreshData()
    {
        $this->loadData();
        Notification::make()
            ->title('Данные обновлены')
            ->success()
            ->send();
    }
    
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-currency-dollar';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Финансовые отчеты';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Аналитика';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    
    // ТОЛЬКО для администратора
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('admin');
    }
    
    // Скрываем из навигации для не-админов
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('admin');
    }
}