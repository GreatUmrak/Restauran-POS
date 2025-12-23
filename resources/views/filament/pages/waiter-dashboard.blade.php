<x-filament-panels::page>
    <div wire:poll.15s>
    <x-filament-panels::header
        heading="Панель официанта"
        subheading="Добро пожаловать, {{ auth()->user()->name }}!"
    />
    
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Верхняя панель статистики -->
        <div>
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                @php $stats = $this->getStatistics(); @endphp
                
                <!-- Всего заказов -->
                <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-height: 110px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: bold;">{{ $stats['total_orders'] }}</div>
                            <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Всего заказов</div>
                        </div>
                        <div style="font-size: 1.5rem;">📋</div>
                    </div>
                </div>
                
                <!-- Готовятся -->
                <div style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-height: 110px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: bold;">{{ $stats['preparing_orders'] }}</div>
                            <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Готовятся</div>
                        </div>
                        <div style="font-size: 1.5rem;">👨‍🍳</div>
                    </div>
                </div>
                
                <!-- Готовы к выдаче -->
                <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-height: 110px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: bold;">{{ $stats['ready_orders'] }}</div>
                            <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Готовы</div>
                        </div>
                        <div style="font-size: 1.5rem;">✅</div>
                    </div>
                </div>
                
                <!-- Свободные столы -->
                <div style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-height: 110px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: bold;">{{ $stats['free_tables'] }}</div>
                            <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Свободно</div>
                        </div>
                        <div style="font-size: 1.5rem;">🪑</div>
                    </div>
                </div>
                
                <!-- Занятые столы -->
                <div style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-height: 110px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: bold;">{{ $stats['occupied_tables'] }}</div>
                            <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Занято</div>
                        </div>
                        <div style="font-size: 1.5rem;">🚫</div>
                    </div>
                </div>
                
                <!-- Всего столов -->
                <div style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-height: 110px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: bold;">{{ $stats['total_tables'] }}</div>
                            <div style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.5rem;">Всего столов</div>
                        </div>
                        <div style="font-size: 1.5rem;">🏠</div>
                    </div>
                </div>
            </div>
            
            <!-- Фильтры -->
            <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="font-size: 1.125rem; font-weight: 600; color: #1e3a8a;">Фильтры:</div>
                        
                        <!-- Табы фильтров -->
                        <div style="display: flex; gap: 0.5rem;">
                            <button 
                                wire:click="$set('activeTab', 'all')"
                                style="padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s; 
                                       {{ $activeTab === 'all' 
                                          ? 'background: #2563eb; color: white; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);' 
                                          : 'background: #f1f5f9; color: #475569;' 
                                       }}"
                            >
                                Все заказы
                            </button>
                            <button 
                                wire:click="$set('activeTab', 'preparing')"
                                style="padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s;
                                       {{ $activeTab === 'preparing' 
                                          ? 'background: #f59e0b; color: white; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3);' 
                                          : 'background: #f1f5f9; color: #475569;' 
                                       }}"
                            >
                                Готовятся
                            </button>
                            <button 
                                wire:click="$set('activeTab', 'ready')"
                                style="padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s;
                                       {{ $activeTab === 'ready' 
                                          ? 'background: #10b981; color: white; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);' 
                                          : 'background: #f1f5f9; color: #475569;' 
                                       }}"
                            >
                                Готовы к выдаче
                            </button>
                        </div>
                    </div>
                    
                    <!-- Фильтр по столу -->
                    <div>
                        <select 
                            wire:model.live="selectedTable"
                            style="width: 100%; max-width: 300px; padding: 0.5rem 1rem; border: 2px solid #3b82f6; border-radius: 0.5rem; color: #1e3a8a;"
                        >
                            <option value="">Все столы</option>
                            @foreach($this->getTables() as $table)
                                <option value="{{ $table->id }}">{{ $table->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Два столбца с заказами -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Левая колонка: Заказы, которые готовятся -->
            <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; overflow: hidden; height: fit-content;">
                <div style="background: linear-gradient(to right, #f59e0b, #d97706); padding: 1rem 1.5rem;">
                    <div style="font-size: 1.25rem; font-weight: bold; color: white; display: flex; align-items: center;">
                        <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Готовятся на кухне
                        <span style="margin-left: 0.5rem; background: #92400e; color: white; font-size: 0.875rem; padding: 0.25rem 0.5rem; border-radius: 9999px;">
                            {{ $this->getPreparingOrders()->count() }}
                        </span>
                    </div>
                </div>
                
                <div style="padding: 1.5rem;">
                    @if($this->getPreparingOrders()->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($this->getPreparingOrders() as $order)
                                <div style="background: #fffbeb; border: 2px solid #fbbf24; border-radius: 0.75rem; padding: 1rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;"
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)';"
                                     onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                        <div>
                                            <div style="font-size: 1.25rem; font-weight: bold; color: #92400e;">
                                                {{ $order->order_number }}
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                                <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; background: #fef3c7; color: #92400e; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                                                    Стол: {{ $order->table->name }}
                                                </span>
                                                <span style="font-size: 0.875rem; color: #92400e;">
                                                    {{ $order->created_at->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 1.125rem; font-weight: bold; color: #92400e;">
                                                {{ number_format($order->total_amount, 2) }} ₽
                                            </div>
                                            <div style="font-size: 0.875rem; color: #d97706;">
                                                {{ $order->guests_count }} гостя
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: 0.75rem;">
                                        <div style="font-size: 0.875rem; font-weight: 500; color: #92400e; margin-bottom: 0.25rem;">Состав:</div>
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            @foreach($order->items as $item)
                                                <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                                                    <span style="color: #92400e; max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ $item->quantity }}× {{ $item->dish->name }}
                                                        @if($item->special_instructions)
                                                            <span style="font-size: 0.75rem; color: #d97706; margin-left: 0.25rem;">
                                                                ({{ $item->special_instructions }})
                                                            </span>
                                                        @endif
                                                    </span>
                                                    <span style="font-weight: 500; color: #92400e;">
                                                        {{ number_format($item->price * $item->quantity, 2) }} ₽
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @if($order->notes)
                                        <div style="margin-bottom: 0.75rem; padding: 0.5rem; background: #fef3c7; border-radius: 0.5rem; font-size: 0.875rem; color: #92400e; border: 1px solid #fbbf24;">
                                            📝 {{ $order->notes }}
                                        </div>
                                    @endif
                                    
                                    <!-- Кнопки действий -->
                                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.5rem;">
                                        <button 
                                            wire:click="deleteOrder({{ $order->id }})"
                                            onclick="return confirm('Вы уверены, что хотите удалить заказ №{{ $order->order_number }}?')"
                                            style="padding: 0.5rem 1rem; background: linear-gradient(to right, #ef4444, #dc2626); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                            onmouseover="this.style.transform='scale(1.05)';"
                                            onmouseout="this.style.transform='';"
                                        >
                                            Удалить
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: #9ca3af;">
                            <div style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #d1d5db;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p style="font-weight: 500; color: #6b7280;">Нет заказов, которые готовятся</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Правая колонка: Заказы готовы к выдаче -->
            <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; overflow: hidden; height: fit-content;">
                <div style="background: linear-gradient(to right, #10b981, #059669); padding: 1rem 1.5rem;">
                    <div style="font-size: 1.25rem; font-weight: bold; color: white; display: flex; align-items: center;">
                        <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Готовы к выдаче
                        <span style="margin-left: 0.5rem; background: #065f46; color: white; font-size: 0.875rem; padding: 0.25rem 0.5rem; border-radius: 9999px;">
                            {{ $this->getReadyToServeOrders()->count() }}
                        </span>
                    </div>
                </div>
                
                <div style="padding: 1.5rem;">
                    @if($this->getReadyToServeOrders()->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($this->getReadyToServeOrders() as $order)
                                <div style="background: #f0fdf4; border: 2px solid #34d399; border-radius: 0.75rem; padding: 1rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;"
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)';"
                                     onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                        <div>
                                            <div style="font-size: 1.25rem; font-weight: bold; color: #065f46;">
                                                {{ $order->order_number }}
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                                <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; background: #d1fae5; color: #065f46; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                                                    Стол: {{ $order->table->name }}
                                                </span>
                                                <span style="font-size: 0.875rem; color: #065f46;">
                                                    {{ $order->created_at->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 1.125rem; font-weight: bold; color: #065f46;">
                                                {{ number_format($order->total_amount, 2) }} ₽
                                            </div>
                                            <div style="font-size: 0.875rem; color: #059669;">
                                                {{ $order->guests_count }} гостя
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: 0.75rem;">
                                        <div style="font-size: 0.875rem; font-weight: 500; color: #065f46; margin-bottom: 0.25rem;">Состав:</div>
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            @foreach($order->items as $item)
                                                <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                                                    <span style="color: #065f46; max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ $item->quantity }}× {{ $item->dish->name }}
                                                        @if($item->special_instructions)
                                                            <span style="font-size: 0.75rem; color: #059669; margin-left: 0.25rem;">
                                                                ({{ $item->special_instructions }})
                                                            </span>
                                                        @endif
                                                    </span>
                                                    <span style="font-weight: 500; color: #065f46;">
                                                        {{ number_format($item->price * $item->quantity, 2) }} ₽
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @if($order->notes)
                                        <div style="margin-bottom: 0.75rem; padding: 0.5rem; background: #d1fae5; border-radius: 0.5rem; font-size: 0.875rem; color: #065f46; border: 1px solid #34d399;">
                                            📝 {{ $order->notes }}
                                        </div>
                                    @endif
                                    
                                    <!-- Кнопки действий -->
                                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.5rem;">
                                        <button 
                                            wire:click="markServed({{ $order->id }})"
                                            onclick="return confirm('Отметить заказ №{{ $order->order_number }} как выданный?\n\n⚠️ ВНИМАНИЕ: Стол {{ $order->table->name }} останется занятым до ручного освобождения.')"
                                            style="padding: 0.5rem 1rem; background: linear-gradient(to right, #10b981, #059669); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                            onmouseover="this.style.transform='scale(1.05)';"
                                            onmouseout="this.style.transform='';"
                                        >
                                            Выдан
                                        </button>
                                        
                                        <button 
                                            wire:click="deleteOrder({{ $order->id }})"
                                            onclick="return confirm('Вы уверены, что хотите удалить заказ №{{ $order->order_number }}?')"
                                            style="padding: 0.5rem 1rem; background: linear-gradient(to right, #ef4444, #dc2626); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                            onmouseover="this.style.transform='scale(1.05)';"
                                            onmouseout="this.style.transform='';"
                                        >
                                            Удалить
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: #9ca3af;">
                            <div style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #d1d5db;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p style="font-weight: 500; color: #6b7280;">Нет заказов, готовых к выдаче</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Нижняя секция: Столы -->
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="background: linear-gradient(to right, #1e40af, #3b82f6); padding: 1rem 1.5rem;">
                <div style="font-size: 1.25rem; font-weight: bold; color: white; display: flex; align-items: center;">
                    <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Столы
                </div>
            </div>
            
            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; align-items: stretch;">
                    @foreach($this->getTables() as $table)
                        @php
                            $tableStatus = $this->getTableStatus($table);
                            $statusColor = $this->getTableStatusColor($tableStatus);
                            $statusText = $this->getTableStatusText($tableStatus);
                        @endphp
                        
                        <div 
                            style="display: flex; flex-direction: column; padding: 1rem; border-radius: 0.75rem; text-align: center; cursor: pointer; transition: all 0.2s; min-height: 180px; 
                                   @if($statusColor === 'success') background: #f0f9ff; border: 2px solid #3b82f6; color: #1e40af; @endif
                                   @if($statusColor === 'danger') background: #fef2f2; border: 2px solid #ef4444; color: #dc2626; @endif
                                   @if($statusColor === 'warning') background: #fffbeb; border: 2px solid #f59e0b; color: #d97706; @endif
                                   @if($statusColor === 'gray') background: #f8fafc; border: 2px solid #94a3b8; color: #475569; @endif"
                            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)';"
                            onmouseout="this.style.transform=''; this.style.boxShadow='';"
                            wire:click="$set('selectedTable', {{ $table->id }})"
                        >
                            <!-- Иконка статуса -->
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem; flex-shrink: 0;">
                                @if($statusColor === 'success') 🟢
                                @elseif($statusColor === 'danger') 🔴
                                @elseif($statusColor === 'warning') 🟡
                                @else 🔵 @endif
                            </div>
                            
                            <!-- Название стола -->
                            <div style="font-size: 1.125rem; font-weight: bold; margin-bottom: 0.25rem; line-height: 1.2; min-height: 2.5rem; display: flex; align-items: center; justify-content: center;">
                                {{ $table->name }}
                            </div>
                            
                            <!-- Номер стола -->
                            <div style="font-size: 0.875rem; color: inherit; opacity: 0.75; margin-bottom: 0.5rem; flex-shrink: 0;">
                                №{{ $table->number }}
                            </div>
                            
                            <!-- Статус -->
                            <div style="margin-bottom: 0.5rem; flex-shrink: 0;">
                                <span style="display: inline-block; padding: 0.25rem 0.5rem; background: white; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; border: 1px solid;">
                                    {{ $statusText }}
                                </span>
                            </div>
                            
                            <!-- Доп. информация -->
                            <div style="font-size: 0.875rem; margin-bottom: 0.75rem; flex-grow: 1;">
                                <div style="font-weight: 500;">Вместимость: {{ $table->capacity }} чел.</div>
                                @if($table->orders_count > 0)
                                    <div style="margin-top: 0.25rem; font-size: 0.75rem; font-weight: 500; background: rgba(59, 130, 246, 0.1); color: #1d4ed8; padding: 0.25rem 0.5rem; border-radius: 9999px;">
                                        Активных заказов: {{ $table->orders_count }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Кнопка -->
                            <div style="margin-top: auto;">
                                @if($table->status !== 'free' && $table->orders_count == 0)
                                    <button 
                                        wire:click="freeTable({{ $table->id }})"
                                        onclick="return confirm('Вы уверены, что хотите освободить стол {{ $table->name }}?')"
                                        style="display: inline-block; padding: 0.5rem 1rem; background: #3b82f6; color: white; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 500; text-decoration: none; transition: all 0.2s; border: none; cursor: pointer; width: 100%;"
                                        onmouseover="this.style.background='#2563eb';"
                                        onmouseout="this.style.background='#3b82f6';"
                                    >
                                        Освободить стол
                                    </button>
                                @else
                                    <a 
                                        href="{{ route('filament.admin.pages.order-menu', ['table_id' => $table->id]) }}"
                                        style="display: inline-block; padding: 0.5rem 1rem; background: #3b82f6; color: white; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 500; text-decoration: none; transition: all 0.2s; width: 100%;"
                                        onmouseover="this.style.background='#2563eb';"
                                        onmouseout="this.style.background='#3b82f6';"
                                        onclick="event.stopPropagation();"
                                    >
                                        Создать заказ
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Плавающая кнопка для создания заказа -->
    <a 
        href="{{ route('filament.admin.pages.order-menu') }}"
        style="position: fixed; bottom: 2rem; right: 2rem; background: linear-gradient(45deg, #1e40af 0%, #3b82f6 100%); color: white; padding: 1rem 1.5rem; border-radius: 9999px; font-weight: bold; box-shadow: 0 10px 25px rgba(30, 64, 175, 0.5); display: flex; align-items: center; justify-content: center; z-index: 100; transition: all 0.3s; text-decoration: none;"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 30px rgba(30, 64, 175, 0.6)';"
        onmouseout="this.style.transform=''; this.style.boxShadow='0 10px 25px rgba(30, 64, 175, 0.5)';"
    >
        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Новый заказ
    </a>
    
<script>
    // Автообновление для официанта
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire инициализирован для WaiterDashboard');
        
        // Функция обновления
        function refreshWaiterDashboard() {
            console.log('Обновляем данные официанта...');
            // В Livewire 3 используем dispatch для вызова метода
            Livewire.dispatch('refresh-waiter-dashboard');
        }
        
        // Обновляем сразу
        refreshWaiterDashboard();
        
        // Устанавливаем интервал каждые 15 секунд
        const waiterInterval = setInterval(refreshWaiterDashboard, 15000);
        
        // Обновляем при возвращении на вкладку
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                refreshWaiterDashboard();
            }
        });
        
        // Останавливаем интервал при уходе со страницы
        window.addEventListener('beforeunload', () => {
            clearInterval(waiterInterval);
        });
    });
</script>
</div>
</x-filament-panels::page>