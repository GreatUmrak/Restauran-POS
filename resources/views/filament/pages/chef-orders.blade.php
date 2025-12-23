<x-filament-panels::page>
    <div wire:poll.10s>
    <x-filament-panels::header
        heading="Панель повара"
        subheading="Управление приготовлением заказов"
    />
    
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Верхняя панель статистики -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
            <!-- Всего заказов -->
            <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ $this->orders->where('status', 'created')->count() }}</div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Новые заказы</div>
                    </div>
                    <div style="font-size: 1.5rem;">🆕</div>
                </div>
            </div>
            
            <!-- Готовятся -->
            <div style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ $this->orders->where('status', 'preparing')->count() }}</div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">В процессе</div>
                    </div>
                    <div style="font-size: 1.5rem;">👨‍🍳</div>
                </div>
            </div>
            
            <!-- Готовы -->
            <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ $this->orders->where('status', 'ready')->count() }}</div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Готовы</div>
                    </div>
                    <div style="font-size: 1.5rem;">✅</div>
                </div>
            </div>
            
            <!-- Всего -->
            <div style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ $this->orders->count() }}</div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Всего</div>
                    </div>
                    <div style="font-size: 1.5rem;">📋</div>
                </div>
            </div>
        </div>
        
        <!-- Фильтры -->
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #3b82f6;">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="font-size: 1.125rem; font-weight: 600; color: #1e3a8a;">Фильтры:</div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <!-- Фильтр по статусу -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1e3a8a;">Статус заказа</label>
                        <select 
                            wire:model.live="statusFilter"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #3b82f6; border-radius: 0.5rem; color: #1e3a8a;"
                        >
                            @foreach($this->getStatusOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Фильтр по столу -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1e3a8a;">Стол</label>
                        <select 
                            wire:model.live="tableFilter"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #3b82f6; border-radius: 0.5rem; color: #1e3a8a;"
                        >
                            <option value="">Все столы</option>
                            @foreach($this->getTables() as $table)
                                <option value="{{ $table->id }}">{{ $table->name }} (№{{ $table->number }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Список заказов -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @forelse($orders as $order)
                <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid {{ $order->status === 'created' ? '#94a3b8' : ($order->status === 'preparing' ? '#f59e0b' : '#10b981') }}; overflow: hidden; transition: all 0.2s;"
                     onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.15)';"
                     onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
                    
                    <!-- Заголовок заказа -->
                    <div style="background: linear-gradient(to right, {{ $order->status === 'created' ? '#64748b' : ($order->status === 'preparing' ? '#f59e0b' : '#10b981') }}, {{ $order->status === 'created' ? '#475569' : ($order->status === 'preparing' ? '#d97706' : '#059669') }}); padding: 1rem 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <span style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: rgba(255,255,255,0.2); color: white; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                                    {{ $this->getStatusLabel($order->status) }}
                                </span>
                                
                                <div style="color: white;">
                                    <div style="font-size: 1.25rem; font-weight: bold;">{{ $order->order_number }}</div>
                                    <div style="font-size: 0.875rem; opacity: 0.9;">Стол: {{ $order->table->name }} | Время: {{ $order->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                            
                            <div style="text-align: right; color: white;">
                                <div style="font-size: 1.125rem; font-weight: bold;">{{ number_format($order->total_amount, 2) }} ₽</div>
                                <div style="font-size: 0.875rem; opacity: 0.9;">{{ $order->guests_count }} гостя</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Детали заказа -->
                    <div style="padding: 1.5rem;">
                        <div style="margin-bottom: 1rem;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #1e3a8a; margin-bottom: 0.75rem;">Состав заказа:</h4>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                @foreach($order->items as $item)
                                    <div style="padding: 1rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                            <div>
                                                <div style="font-weight: 600; color: #1e3a8a; margin-bottom: 0.25rem;">
                                                    {{ $item->dish->name }} × {{ $item->quantity }}
                                                </div>
                                                <div style="font-size: 0.875rem; color: #64748b;">
                                                    Цена: {{ number_format($item->price, 2) }} ₽ за шт.
                                                    @if($item->special_instructions)
                                                        <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #d97706;">
                                                            📝 {{ $item->special_instructions }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div style="font-weight: 700; color: #1e40af;">
                                                {{ number_format($item->price * $item->quantity, 2) }} ₽
                                            </div>
                                        </div>
                                        
                                        <!-- МОДИФИКАТОРЫ БЛЮДА - ИСПРАВЛЕННАЯ ЧАСТЬ -->
                                        @if($item->modifiers && $item->modifiers->count() > 0)
                                            <div style="margin-top: 0.5rem; padding: 0.75rem; background: #eff6ff; border-radius: 0.5rem; border: 1px solid #dbeafe;">
                                                <div style="font-size: 0.875rem; font-weight: 600; color: #1e40af; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                    Модификаторы:
                                                </div>
                                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                                    @foreach($item->modifiers as $modifier)
                                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; background: rgba(255,255,255,0.7); border-radius: 0.375rem;">
                                                            <div>
                                                                <div style="font-weight: 500; color: #1e3a8a; font-size: 0.875rem;">
                                                                    {{ $modifier->name }}
                                                                </div>
                                                                @if($modifier->modifierGroup)
                                                                    <div style="font-size: 0.75rem; color: #8b5cf6; margin-top: 0.125rem;">
                                                                        Группа: {{ $modifier->modifierGroup->name }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div style="font-weight: 600; color: #10b981; font-size: 0.875rem;">
                                                                @php
                                                                    // Получаем цену модификатора
                                                                    $modifierPrice = 0;
                                                                    if (isset($modifier->pivot) && $modifier->pivot->price) {
                                                                        $modifierPrice = $modifier->pivot->price;
                                                                    } elseif ($modifier->price) {
                                                                        $modifierPrice = $modifier->price;
                                                                    }
                                                                @endphp
                                                                @if($modifierPrice > 0)
                                                                    +{{ number_format($modifierPrice, 2) }} ₽
                                                                @else
                                                                    Бесплатно
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Общая сумма по блюду с модификаторами -->
                                        @if($item->modifiers && $item->modifiers->count() > 0)
                                            @php
                                                // Рассчитываем общую сумму модификаторов
                                                $modifiersTotal = 0;
                                                foreach ($item->modifiers as $modifier) {
                                                    if (isset($modifier->pivot) && $modifier->pivot->price) {
                                                        $modifiersTotal += $modifier->pivot->price;
                                                    } elseif ($modifier->price) {
                                                        $modifiersTotal += $modifier->price;
                                                    }
                                                }
                                                $itemTotalWithModifiers = ($item->price + $modifiersTotal) * $item->quantity;
                                            @endphp
                                            <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed #dbeafe;">
                                                <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                                                    <span style="color: #64748b;">Сумма модификаторов:</span>
                                                    <span style="font-weight: 600; color: #8b5cf6;">+{{ number_format($modifiersTotal * $item->quantity, 2) }} ₽</span>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; font-size: 0.875rem; margin-top: 0.25rem;">
                                                    <span style="color: #64748b;">Итого за блюдо:</span>
                                                    <span style="font-weight: 700; color: #1e40af;">{{ number_format($itemTotalWithModifiers, 2) }} ₽</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        @if($order->notes)
                            <div style="margin-bottom: 1rem; padding: 0.75rem; background: #fef3c7; border-radius: 0.5rem; font-size: 0.875rem; color: #92400e; border: 1px solid #fbbf24;">
                                <div style="font-weight: 500; margin-bottom: 0.25rem;">Комментарий к заказу:</div>
                                {{ $order->notes }}
                            </div>
                        @endif
                        
                        <!-- Кнопки действий -->
                        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 2px solid #e2e8f0;">
                            @if($order->status === 'created')
                                <button 
                                    wire:click="markPreparing({{ $order->id }})"
                                    style="padding: 0.75rem 1.5rem; background: linear-gradient(to right, #f59e0b, #d97706); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.3)';"
                                    onmouseout="this.style.transform=''; this.style.boxShadow='none';"
                                >
                                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Начать готовить
                                </button>
                            @endif
                            
                            @if($order->status === 'preparing')
                                <button 
                                    wire:click="markReady({{ $order->id }})"
                                    style="padding: 0.75rem 1.5rem; background: linear-gradient(to right, #10b981, #059669); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)';"
                                    onmouseout="this.style.transform=''; this.style.boxShadow='none';"
                                >
                                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Готово
                                </button>
                            @endif
                            
                            <div style="font-size: 0.875rem; color: #64748b; display: flex; align-items: center; gap: 0.5rem; margin-left: auto;">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="background: white; border-radius: 0.75rem; padding: 4rem 2rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px dashed #cbd5e1;">
                    <div style="width: 4rem; height: 4rem; margin: 0 auto 1.5rem; color: #cbd5e1;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Нет заказов</h3>
                    <p style="color: #64748b;">Когда появятся новые заказы, они отобразятся здесь</p>
                </div>
            @endforelse
        </div>
    </div>
    
<script>
    // Используем Livewire 3 подход для автообновления
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire инициализирован для ChefOrders');
        
        // Функция для обновления данных
        function refreshChefOrders() {
            console.log('Обновляем данные повара...');
            Livewire.dispatch('refresh-chef-orders');
        }
        
        // Обновляем сразу
        refreshChefOrders();
        
        // Устанавливаем интервал обновления каждые 10 секунд
        setInterval(refreshChefOrders, 10000);
        
        // Также обновляем при фокусе на вкладке
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                refreshChefOrders();
            }
        });
        
        // Обновляем при клике на странице (на случай если интервал не работает)
        document.addEventListener('click', () => {
            refreshChefOrders();
        });
    });
</script>
</div>
</x-filament-panels::page>