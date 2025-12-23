<x-filament-panels::page>
    <x-filament-panels::header
        heading="💰 Финансовые отчеты"
        subheading="Статистика прибыли и продаж"
    />
    
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Верхняя панель фильтров -->
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #3b82f6;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #1e3a8a; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Фильтры отчета
                </h3>
                
                <button 
                    wire:click="refreshData"
                    style="padding: 0.5rem 1rem; background: linear-gradient(to right, #3b82f6, #1e40af); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';"
                    onmouseout="this.style.transform=''; this.style.boxShadow='none';"
                >
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Обновить
                </button>
            </div>
            
            <form wire:submit.prevent="loadData">
                {{ $this->form }}
            </form>
        </div>
        
        <!-- Карточки статистики за период -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
            <!-- Общая прибыль -->
            <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 1.75rem; font-weight: bold;">{{ number_format($periodProfit, 2) }} ₽</div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Прибыль за период</div>
                    </div>
                    <div style="font-size: 2rem;">💰</div>
                </div>
            </div>
            
            <!-- Количество заказов -->
            <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 1.75rem; font-weight: bold;">{{ $periodOrders }}</div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Заказов за период</div>
                    </div>
                    <div style="font-size: 2rem;">📦</div>
                </div>
            </div>
            
            <!-- Средний чек -->
            <div style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 1.75rem; font-weight: bold;">
                            @if($periodOrders > 0)
                                {{ number_format($periodProfit / $periodOrders, 2) }} ₽
                            @else
                                0 ₽
                            @endif
                        </div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Средний чек</div>
                    </div>
                    <div style="font-size: 2rem;">📊</div>
                </div>
            </div>
            
            <!-- Прибыль за месяц -->
            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 1.75rem; font-weight: bold;">{{ number_format($overallStats['current_month_profit'] ?? 0, 2) }} ₽</div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Прибыль за месяц</div>
                    </div>
                    <div style="font-size: 2rem;">📅</div>
                </div>
            </div>
        </div>
        
        <!-- Общая статистика -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Левая колонка: Ежедневная прибыль -->
            <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #3b82f6; overflow: hidden;">
                <div style="background: linear-gradient(to right, #3b82f6, #1e40af); padding: 1rem 1.5rem;">
                    <div style="font-size: 1.25rem; font-weight: bold; color: white; display: flex; align-items: center;">
                        <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Ежедневная прибыль
                        <span style="margin-left: 0.5rem; background: rgba(255,255,255,0.2); color: white; font-size: 0.875rem; padding: 0.25rem 0.5rem; border-radius: 9999px;">
                            {{ count($dailyProfits) }} дней
                        </span>
                    </div>
                </div>
                
                <div style="padding: 1.5rem;">
                    @if($dailyProfits->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 400px; overflow-y: auto;">
                            @foreach($dailyProfits as $profit)
                                <div style="padding: 1rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e2e8f0; transition: all 0.2s;"
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                                     onmouseout="this.style.transform=''; this.style.boxShadow='none';">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 600; color: #1e3a8a; font-size: 1.125rem;">
                                                {{ $profit->date->format('d.m.Y') }} 
                                                @if($profit->date->isToday())
                                                    <span style="font-size: 0.75rem; background: #10b981; color: white; padding: 0.125rem 0.5rem; border-radius: 9999px; margin-left: 0.5rem;">Сегодня</span>
                                                @endif
                                            </div>
                                            <div style="display: flex; gap: 1rem; font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">
                                                <span>{{ $profit->orders_count }} заказов</span>
                                                <span>Средний чек: {{ number_format($profit->average_order_value, 2) }} ₽</span>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 1.5rem; font-weight: bold; color: #10b981;">
                                                {{ number_format($profit->total_profit, 2) }} ₽
                                            </div>
                                            @if($profit->date->isToday())
                                                <div style="font-size: 0.75rem; color: #3b82f6; margin-top: 0.25rem;">
                                                    Активно сейчас
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: #9ca3af;">
                            <div style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #d1d5db;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <p style="font-weight: 500; color: #6b7280;">Нет данных за выбранный период</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Правая колонка: Топ заказов -->
            <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #f59e0b; overflow: hidden;">
                <div style="background: linear-gradient(to right, #f59e0b, #d97706); padding: 1rem 1.5rem;">
                    <div style="font-size: 1.25rem; font-weight: bold; color: white; display: flex; align-items: center;">
                        <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Топ заказов за период
                        <span style="margin-left: 0.5rem; background: rgba(255,255,255,0.2); color: white; font-size: 0.875rem; padding: 0.25rem 0.5rem; border-radius: 9999px;">
                            Топ-10
                        </span>
                    </div>
                </div>
                
                <div style="padding: 1.5rem;">
                    @if($topOrders->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 400px; overflow-y: auto;">
                            @foreach($topOrders as $order)
                                <div style="padding: 1rem; background: #fffbeb; border-radius: 0.5rem; border: 1px solid #fcd34d; transition: all 0.2s;"
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.1)';"
                                     onmouseout="this.style.transform=''; this.style.boxShadow='none';">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 600; color: #92400e; font-size: 1.125rem;">
                                                №{{ $order->order_number }}
                                                <span style="font-size: 0.75rem; background: #d97706; color: white; padding: 0.125rem 0.5rem; border-radius: 9999px; margin-left: 0.5rem;">
                                                    {{ $order->status === 'served' ? 'Выдан' : 'Готов' }}
                                                </span>
                                            </div>
                                            <div style="display: flex; gap: 1rem; font-size: 0.875rem; color: #92400e; margin-top: 0.25rem;">
                                                <span>Стол: {{ $order->table->name }}</span>
                                                <span>Официант: {{ $order->user->name }}</span>
                                                <span>{{ $order->created_at->format('H:i') }}</span>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 1.5rem; font-weight: bold; color: #92400e;">
                                                {{ number_format($order->total_amount, 2) }} ₽
                                            </div>
                                            <div style="font-size: 0.75rem; color: #d97706; margin-top: 0.25rem;">
                                                {{ $order->guests_count }} гостя
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: #9ca3af;">
                            <div style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #d1d5db;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <p style="font-weight: 500; color: #6b7280;">Нет заказов за выбранный период</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Общая статистика за все время -->
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #10b981; overflow: hidden;">
            <div style="background: linear-gradient(to right, #10b981, #059669); padding: 1rem 1.5rem;">
                <div style="font-size: 1.25rem; font-weight: bold; color: white; display: flex; align-items: center;">
                    <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Общая статистика за все время
                </div>
            </div>
            
            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                    <!-- Общая прибыль -->
                    <div style="padding: 1.5rem; background: #f0fdf4; border-radius: 0.75rem; border: 2px solid #a7f3d0; text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: bold; color: #059669; margin-bottom: 0.5rem;">
                            {{ number_format($overallStats['total_profit_all_time'] ?? 0, 2) }} ₽
                        </div>
                        <div style="font-size: 0.875rem; color: #047857; font-weight: 500;">
                            Общая прибыль
                        </div>
                    </div>
                    
                    <!-- Всего заказов -->
                    <div style="padding: 1.5rem; background: #f0f9ff; border-radius: 0.75rem; border: 2px solid #bae6fd; text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: bold; color: #0369a1; margin-bottom: 0.5rem;">
                            {{ $overallStats['total_orders_all_time'] ?? 0 }}
                        </div>
                        <div style="font-size: 0.875rem; color: #0369a1; font-weight: 500;">
                            Всего заказов
                        </div>
                    </div>
                    
                    <!-- Средняя дневная прибыль -->
                    <div style="padding: 1.5rem; background: #fef7ff; border-radius: 0.75rem; border: 2px solid #e9d5ff; text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: bold; color: #7c3aed; margin-bottom: 0.5rem;">
                            {{ number_format($overallStats['average_daily_profit'] ?? 0, 2) }} ₽
                        </div>
                        <div style="font-size: 0.875rem; color: #7c3aed; font-weight: 500;">
                            Средняя дневная прибыль
                        </div>
                    </div>
                    
                    <!-- Лучший день -->
                    <div style="padding: 1.5rem; background: #fef3c7; border-radius: 0.75rem; border: 2px solid #fcd34d; text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: bold; color: #d97706; margin-bottom: 0.5rem;">
                            @if($overallStats['best_day'] ?? false)
                                {{ number_format($overallStats['best_day']->total_profit, 2) }} ₽
                            @else
                                0 ₽
                            @endif
                        </div>
                        <div style="font-size: 0.875rem; color: #d97706; font-weight: 500;">
                            Лучший день
                            @if($overallStats['best_day'] ?? false)
                                <div style="font-size: 0.75rem; margin-top: 0.25rem;">
                                    {{ $overallStats['best_day']->date->format('d.m.Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Автообновление данных каждую минуту
        document.addEventListener('livewire:initialized', () => {
            setInterval(() => {
                Livewire.dispatch('refreshData');
            }, 60000); // 60 секунд
            
            // Обновляем при возвращении на вкладку
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    Livewire.dispatch('refreshData');
                }
            });
        });
        
        // Функция для экспорта в Excel (можно реализовать позже)
        function exportToExcel() {
            alert('Экспорт в Excel будет реализован позже');
        }
    </script>
</x-filament-panels::page>