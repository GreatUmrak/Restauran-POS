<x-filament-panels::page>
    <x-filament-panels::header
        heading="Меню для заказа"
        subheading="Выберите блюда из меню"
    />
    
    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
        <!-- Верхняя панель: Выбор стола и информация -->
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #3b82f6;">
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Выбор стола -->
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e3a8a; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 1.5rem; height: 1.5rem; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        1. Выберите стол
                    </h3>
                    
                    @if(!$table_id)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                            @foreach($this->getAvailableTables() as $table)
                                <div 
                                    style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; cursor: pointer; transition: all 0.2s; text-align: center; background: white;"
                                    wire:click="$set('table_id', {{ $table->id }})"
                                    onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';"
                                    onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform=''; this.style.boxShadow='none';"
                                >
                                    <div style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #3b82f6;">🍽️</div>
                                    <div style="font-weight: 600; color: #1e3a8a; margin-bottom: 0.25rem;">{{ $table->name }}</div>
                                    <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.5rem;">№{{ $table->number }}</div>
                                    
                                    @if($table->status === 'free')
                                        <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #dbeafe; color: #1e40af; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; border: 1px solid #93c5fd;">
                                            Свободен
                                        </span>
                                    @elseif($table->status === 'occupied')
                                        <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; border: 1px solid #fca5a5;">
                                            Занят
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #fef3c7; color: #92400e; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; border: 1px solid #fcd34d;">
                                            В процессе
                                        </span>
                                    @endif
                                    
                                    <div style="margin-top: 0.75rem; font-size: 0.875rem; color: #475569;">
                                        Вместимость: {{ $table->capacity }} чел.
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        @php
                            $selectedTable = \App\Models\Table::find($table_id);
                        @endphp
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); border-radius: 0.5rem; border: 2px solid #3b82f6;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="font-size: 1.5rem; color: #1e40af;">🍽️</div>
                                <div>
                                    <div style="font-size: 1.25rem; font-weight: 600; color: #1e40af;">{{ $selectedTable->name }}</div>
                                    <div style="display: flex; gap: 1rem; font-size: 0.875rem; color: #475569;">
                                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Вместимость: {{ $selectedTable->capacity }} чел.
                                        </span>
                                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Номер: {{ $selectedTable->number }}
                                        </span>
                                        <span>
                                            Статус: 
                                            @if($selectedTable->status === 'free')
                                                <span style="color: #1e40af; font-weight: 500;">Свободен</span>
                                            @elseif($selectedTable->status === 'occupied')
                                                <span style="color: #dc2626; font-weight: 500;">Занят</span>
                                            @else
                                                <span style="color: #d97706; font-weight: 500;">В процессе</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button 
                                type="button"
                                wire:click="$set('table_id', null)"
                                style="padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#dc2626';"
                                onmouseout="this.style.background='#ef4444';"
                            >
                                Изменить стол
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Информация о заказе (только если выбран стол) -->
                @if($table_id)
                    <div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem;">
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e3a8a; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 1.5rem; height: 1.5rem; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            2. Информация о заказе
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
<div>
    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Количество гостей</label>
    <div style="display: flex; gap: 0.5rem; align-items: center;">
        <button 
            type="button"
            wire:click="decrementGuests"
            style="width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center; border: 2px solid #3b82f6; border-radius: 0.375rem; font-size: 1.125rem; cursor: pointer; background: white; color: #3b82f6;"
            onmouseover="this.style.background='#3b82f6'; this.style.color='white';"
            onmouseout="this.style.background='white'; this.style.color='#3b82f6';"
        >
            -
        </button>
        <input 
            type="number" 
            wire:model="guests_count"
            min="1"
            @if($selectedTable) max="{{ $selectedTable->capacity }}" @endif
            style="width: 5rem; text-align: center; padding: 0.5rem; border: 2px solid #3b82f6; border-radius: 0.375rem; font-size: 1.125rem; font-weight: 600; color: #1e40af;"
        />
        <button 
            type="button"
            wire:click="incrementGuests"
            style="width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center; border: 2px solid #3b82f6; border-radius: 0.375rem; font-size: 1.125rem; cursor: pointer; background: white; color: #3b82f6;"
            onmouseover="this.style.background='#3b82f6'; this.style.color='white';"
            onmouseout="this.style.background='white'; this.style.color='#3b82f6';"
        >
            +
        </button>
    </div>
    <div style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">
        @if($selectedTable)
            Максимум: {{ $selectedTable->capacity }} гостей
        @endif
    </div>
</div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Комментарий к заказу</label>
                                <textarea 
                                    wire:model="notes"
                                    placeholder="Особые пожелания к заказу..."
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #3b82f6; border-radius: 0.375rem; min-height: 80px; resize: vertical; color: #1e3a8a;"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Основное содержимое (показывается только если выбран стол) -->
        @if($table_id)
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <!-- Левая колонка: Меню с фильтрами -->
                <div>
                    <!-- Фильтры и поиск -->
                    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #3b82f6; margin-bottom: 1.5rem;">
                        <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                            <!-- Поиск -->
                            <div style="flex: 1;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1e3a8a;">Поиск блюд</label>
                                <div style="position: relative;">
                                    <input 
                                        type="text" 
                                        wire:model.live="search"
                                        placeholder="Введите название блюда..."
                                        style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 2px solid #3b82f6; border-radius: 0.5rem; color: #1e3a8a;"
                                    />
                                    <svg style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Фильтр по категории -->
                            <div style="flex: 1;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1e3a8a;">Категория</label>
                                <select 
                                    wire:model.live="categoryFilter"
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #3b82f6; border-radius: 0.5rem; color: #1e3a8a;"
                                >
                                    <option value="">Все категории</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Статистика -->
                        <div style="display: flex; gap: 2rem; font-size: 0.875rem; color: #475569;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #eff6ff; border-radius: 9999px;">
                                <span style="display: inline-block; width: 0.75rem; height: 0.75rem; background: #3b82f6; border-radius: 9999px;"></span>
                                <span>Найдено блюд: <strong style="color: #1e40af;">{{ count($dishes) }}</strong></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #ecfdf5; border-radius: 9999px;">
                                <span style="display: inline-block; width: 0.75rem; height: 0.75rem; background: #10b981; border-radius: 9999px;"></span>
                                <span>В заказе: <strong style="color: #059669;">{{ $this->getSelectedItemsCount() }}</strong></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #faf5ff; border-radius: 9999px;">
                                <span style="display: inline-block; width: 0.75rem; height: 0.75rem; background: #8b5cf6; border-radius: 9999px;"></span>
                                <span>Общая сумма: <strong style="color: #7c3aed;">{{ number_format($this->getTotalAmount(), 2) }} ₽</strong></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Список блюд -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                        @foreach($dishes as $dish)
                            <div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #dbeafe; transition: all 0.2s;"
                                 onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 25px rgba(59, 130, 246, 0.2)'; this.style.borderColor='#3b82f6';"
                                 onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'; this.style.borderColor='#dbeafe';">
                                
                                <!-- Изображение блюда -->
<div style="height: 160px; background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
    @if($dish->image)
        @php
            $imagePath = null;
            
            if (filter_var($dish->image, FILTER_VALIDATE_URL)) {
                $imagePath = $dish->image;
            } elseif (Storage::exists($dish->image)) {
                $imagePath = Storage::url($dish->image);
            } elseif (Storage::exists('public/' . $dish->image)) {
                $imagePath = Storage::url('public/' . $dish->image);
            } else {
                $imagePath = asset('storage/' . $dish->image);
            }
        @endphp
        
        <img 
            src="{{ $imagePath }}" 
            alt="{{ $dish->name }}"
            style="width: 100%; height: 100%; object-fit: cover;"
            onerror="this.style.display='none';"
        />
    @endif
    
    <!-- Если нет изображения, показываем иконку -->
    @if(!$dish->image || !isset($imagePath))
        <div style="font-size: 3rem; color: #93c5fd; opacity: 0.5;">
            🍽️
        </div>
    @endif
    
    <!-- Категория (верхний правый угол) -->
    <div style="position: absolute; top: 0.75rem; right: 0.75rem; padding: 0.25rem 0.5rem; background: rgba(37, 99, 235, 0.9); backdrop-filter: blur(4px); border-radius: 9999px; font-size: 0.75rem; font-weight: 500; color: white;">
        {{ $dish->category->name }}
    </div>
    
    <!-- Время приготовления (нижний левый угол) -->
    @if($dish->preparation_time)
        <div style="position: absolute; bottom: 0.75rem; left: 0.75rem; padding: 0.25rem 0.5rem; background: rgba(37, 99, 235, 0.9); color: white; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
            ⏱️ {{ $dish->preparation_time }} мин
        </div>
    @endif
    
    <!-- Индикатор модификаторов -->
    @if($dish->modifierGroups->count() > 0)
        <div style="position: absolute; top: 0.75rem; left: 0.75rem; padding: 0.25rem 0.5rem; background: rgba(139, 92, 246, 0.9); color: white; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
            ⚙️ Модификаторы
        </div>
    @endif
</div>
                                
                                <!-- Информация о блюде -->
                                <div style="padding: 1.25rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                        <div>
                                            <h3 style="font-weight: 600; color: #1e3a8a; margin-bottom: 0.25rem; font-size: 1.125rem;">{{ $dish->name }}</h3>
                                            <div style="font-weight: 700; color: #1e40af; font-size: 1.25rem;">
                                                {{ number_format($dish->price, 2) }} ₽
                                            </div>
                                        </div>
                                        
                                        <!-- Кнопка добавления -->
                                        <button 
                                            type="button"
                                            wire:click="addDish({{ $dish->id }})"
                                            style="padding: 0.5rem 1rem; background: linear-gradient(to right, #3b82f6, #1e40af); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;"
                                            onmouseover="this.style.transform='scale(1.05)';"
                                            onmouseout="this.style.transform='';"
                                        >
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            @if($dish->modifierGroups->count() > 0)
                                                Настроить
                                            @else
                                                Добавить
                                            @endif
                                        </button>
                                    </div>
                                    
                                    @if($dish->description)
                                        <p style="font-size: 0.875rem; color: #475569; line-height: 1.4; margin-bottom: 1rem;">
                                            {{ Str::limit($dish->description, 100) }}
                                        </p>
                                    @endif
                                    
                                    <!-- Индикатор если блюдо уже в заказе -->
                                    @if(isset($selectedItems[$dish->id]))
                                        <div style="padding: 0.5rem; background: #dbeafe; border-radius: 0.375rem; font-size: 0.875rem; color: #1e40af; border: 1px solid #93c5fd; display: flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            В заказе: {{ $selectedItems[$dish->id]['quantity'] }} шт.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if(count($dishes) === 0)
                            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #64748b;">
                                <div style="width: 4rem; height: 4rem; margin: 0 auto 1rem; color: #cbd5e1;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <p style="font-size: 1.125rem; font-weight: 500; margin-bottom: 0.5rem; color: #475569;">Блюда не найдены</p>
                                <p style="color: #64748b;">Попробуйте изменить параметры поиска</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Правая колонка: Текущий заказ -->
                <div>
                    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #3b82f6; position: sticky; top: 1rem;">
                        <div style="background: linear-gradient(to right, #1e40af, #3b82f6); padding: 1.25rem 1.5rem;">
                            <h2 style="font-size: 1.25rem; font-weight: bold; color: white; margin: 0; display: flex; align-items: center; justify-content: space-between;">
                                <span>Текущий заказ</span>
                                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                    {{ $this->getSelectedItemsCount() }} {{ Lang::choice('блюдо|блюда|блюд', $this->getSelectedItemsCount()) }}
                                </span>
                            </h2>
                        </div>
                        
                        <div style="padding: 1.5rem;">
                            <!-- Список выбранных блюд -->
                            <div style="margin-bottom: 1.5rem;">
                                @if(empty($selectedItems))
                                    <div style="text-align: center; padding: 2rem 1rem; color: #94a3b8;">
                                        <div style="width: 3rem; height: 3rem; margin: 0 auto 1rem; color: #cbd5e1;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p style="font-weight: 500; margin-bottom: 0.5rem; color: #64748b;">Корзина пуста</p>
                                        <p style="font-size: 0.875rem;">Добавьте блюда из меню</p>
                                    </div>
                                @else
                                    <div style="max-height: 350px; overflow-y: auto; padding-right: 0.5rem;">
                                        @foreach($selectedItems as $dishId => $item)
                                            <div style="border: 2px solid #dbeafe; border-radius: 0.75rem; padding: 1rem; margin-bottom: 0.75rem; background: #f8fafc; transition: all 0.2s;"
                                                 onmouseover="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.1)';"
                                                 onmouseout="this.style.borderColor='#dbeafe'; this.style.boxShadow='none';">
                                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                                    <div>
                                                        <div style="font-weight: 600; color: #1e3a8a; margin-bottom: 0.25rem;">{{ $item['name'] }}</div>
                                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                            <span style="font-weight: 600; color: #1e40af;">
                                                                {{ number_format($item['price'], 2) }} ₽ ×
                                                            </span>
                                                            <div style="display: flex; align-items: center; gap: 0.25rem;">
                                                                <button 
                                                                    type="button"
                                                                    wire:click="updateQuantity({{ $dishId }}, {{ $item['quantity'] - 1 }})"
                                                                    style="width: 1.75rem; height: 1.75rem; display: flex; align-items: center; justify-content: center; border: 2px solid #3b82f6; border-radius: 0.25rem; font-size: 0.875rem; cursor: pointer; background: white; color: #3b82f6;"
                                                                    onmouseover="this.style.background='#3b82f6'; this.style.color='white';"
                                                                    onmouseout="this.style.background='white'; this.style.color='#3b82f6';"
                                                                >
                                                                    -
                                                                </button>
                                                                <span style="min-width: 2rem; text-align: center; font-weight: 600; color: #1e40af;">{{ $item['quantity'] }}</span>
                                                                <button 
                                                                    type="button"
                                                                    wire:click="updateQuantity({{ $dishId }}, {{ $item['quantity'] + 1 }})"
                                                                    style="width: 1.75rem; height: 1.75rem; display: flex; align-items: center; justify-content: center; border: 2px solid #3b82f6; border-radius: 0.25rem; font-size: 0.875rem; cursor: pointer; background: white; color: #3b82f6;"
                                                                    onmouseover="this.style.background='#3b82f6'; this.style.color='white';"
                                                                    onmouseout="this.style.background='white'; this.style.color='#3b82f6';"
                                                                >
                                                                    +
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="text-align: right;">
                                                        <div style="font-weight: 700; color: #1e3a8a; font-size: 1.125rem;">
                                                            {{ number_format($item['price'] * $item['quantity'], 2) }} ₽
                                                        </div>
                                                        <button 
                                                            type="button"
                                                            wire:click="removeDish({{ $dishId }})"
                                                            style="margin-top: 0.25rem; padding: 0.25rem 0.5rem; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                                            onmouseover="this.style.background='#fee2e2';"
                                                            onmouseout="this.style.background='#fef2f2';"
                                                        >
                                                            Удалить
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Отображение модификаторов -->
                                                @if(!empty($item['modifiers']))
                                                    <div style="margin-top: 0.5rem; padding: 0.5rem; background: #eff6ff; border-radius: 0.375rem; border: 1px solid #dbeafe;">
                                                        <div style="font-size: 0.75rem; font-weight: 500; color: #1e40af; margin-bottom: 0.25rem;">
                                                            Модификаторы:
                                                        </div>
                                                        @foreach($item['modifiers'] as $modifier)
                                                            <div style="display: flex; justify-content: space-between; font-size: 0.75rem;">
                                                                <span style="color: #475569;">• {{ $modifier['name'] }}</span>
                                                                @if($modifier['price'] > 0)
                                                                    <span style="color: #10b981; font-weight: 500;">+{{ number_format($modifier['price'], 2) }} ₽</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    
                                                    <!-- Кнопка для изменения модификаторов -->
                                                    <button 
                                                        type="button"
                                                        wire:click="updateItemWithModifiers({{ $dishId }}, '{{ $loop->index }}')"
                                                        style="margin-top: 0.5rem; padding: 0.25rem 0.5rem; background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                                        onmouseover="this.style.background='#93c5fd';"
                                                        onmouseout="this.style.background='#dbeafe';"
                                                    >
                                                        Изменить модификаторы
                                                    </button>
                                                @endif
                                                
                                                <input 
                                                    type="text" 
                                                    wire:change="updateInstructions({{ $dishId }}, $event.target.value)"
                                                    value="{{ $item['special_instructions'] }}"
                                                    placeholder="Особые пожелания к блюду..."
                                                    style="width: 100%; padding: 0.5rem; border: 2px solid #dbeafe; border-radius: 0.375rem; font-size: 0.875rem; color: #475569; margin-top: 0.5rem;"
                                                    onfocus="this.style.borderColor='#3b82f6';"
                                                    onblur="this.style.borderColor='#dbeafe';"
                                                />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Итоговая сумма -->
                            <div style="border-top: 2px solid #e2e8f0; padding-top: 1rem; margin-bottom: 1.5rem;">
                                <div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 1.125rem; margin-bottom: 0.5rem;">
                                    <span style="color: #1e3a8a;">Итого:</span>
                                    <span style="color: #1e40af; font-weight: 700;">{{ number_format($this->getTotalAmount(), 2) }} ₽</span>
                                </div>
                                
                                <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.25rem;">
                                    Стол: <strong style="color: #1e40af;">{{ $selectedTable->name }}</strong>
                                </div>
                                <div style="font-size: 0.875rem; color: #64748b;">
                                    Гостей: <strong style="color: #1e40af;">{{ $guests_count }}</strong>
                                </div>
                            </div>
                            
                            <!-- Кнопки действий -->
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <button 
                                    type="button"
                                    wire:click="createOrder"
                                    @if(empty($selectedItems)) disabled @endif
                                    style="width: 100%; padding: 0.875rem; background: linear-gradient(to right, #3b82f6, #1e40af); color: white; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; @if(empty($selectedItems)) opacity: 0.5; cursor: not-allowed; @endif"
                                    @if(!empty($selectedItems))
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(30, 64, 175, 0.3)';"
                                        onmouseout="this.style.transform=''; this.style.boxShadow='none';"
                                    @endif
                                >
                                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Создать заказ
                                </button>
                                
                                <a 
                                    href="{{ route('filament.admin.pages.waiter-dashboard') }}"
                                    style="width: 100%; padding: 0.875rem; background: white; color: #475569; text-align: center; border-radius: 0.5rem; font-weight: 500; text-decoration: none; transition: all 0.2s; border: 2px solid #3b82f6;"
                                    onmouseover="this.style.background='#eff6ff';"
                                    onmouseout="this.style.background='white';"
                                >
                                    Вернуться на панель
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Модальное окно для выбора модификаторов -->
    @if($showModifiersModal && $currentDishId)
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000;">
            <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                @php $dish = $this->getCurrentDish(); @endphp
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #3b82f6;">
                    <h3 style="font-size: 1.5rem; font-weight: bold; color: #1e3a8a;">
                        {{ $dish->name }}
                    </h3>
                    <button 
                        wire:click="closeModifiersModal"
                        style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; padding: 0.5rem;"
                        onmouseover="this.style.color='#dc2626';"
                        onmouseout="this.style.color='#64748b';"
                    >
                        ✕
                    </button>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <div style="font-size: 1.125rem; font-weight: 600; color: #1e3a8a;">
                            Выберите модификаторы
                        </div>
                        <div style="font-weight: 700; color: #1e40af; font-size: 1.25rem;">
                            {{ number_format($dish->price, 2) }} ₽
                        </div>
                    </div>
                    
                    @foreach($dish->modifierGroups as $group)
                        <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.75rem; border: 1px solid #dbeafe;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <div>
                                    <h4 style="font-weight: 600; color: #1e3a8a; margin-bottom: 0.25rem;">{{ $group->name }}</h4>
                                    <div style="font-size: 0.875rem; color: #64748b;">
                                        @if($group->type === 'single')
                                            Выберите один вариант
                                        @else
                                            Можно выбрать несколько
                                        @endif
                                        @if($group->required)
                                            <span style="color: #ef4444; font-weight: 500;"> (Обязательно)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                @foreach($group->modifiers as $modifier)
                                    <label style="display: flex; align-items: center; padding: 0.75rem; background: white; border-radius: 0.5rem; border: 2px solid #dbeafe; cursor: pointer; transition: all 0.2s;"
                                           onmouseover="this.style.borderColor='#3b82f6';"
                                           onmouseout="this.style.borderColor='#dbeafe';">
                                        @if($group->type === 'single')
                                            <input 
                                                type="radio"
                                                wire:model.live="selectedModifiers.{{ $modifier->id }}"
                                                value="true"
                                                name="modifier_group_{{ $group->id }}"
                                                style="margin-right: 0.75rem; accent-color: #3b82f6;"
                                            />
                                        @else
                                            <input 
                                                type="checkbox"
                                                wire:model.live="selectedModifiers.{{ $modifier->id }}"
                                                style="margin-right: 0.75rem; accent-color: #3b82f6;"
                                            />
                                        @endif
                                        
                                        <div style="flex: 1;">
                                            <div style="font-weight: 500; color: #1e3a8a;">{{ $modifier->name }}</div>
                                        </div>
                                        
                                        <div style="font-weight: 600; color: {{ $modifier->price > 0 ? '#10b981' : '#64748b' }};">
                                            @if($modifier->price > 0)
                                                +{{ number_format($modifier->price, 2) }} ₽
                                            @else
                                                Бесплатно
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button 
                        wire:click="closeModifiersModal"
                        style="padding: 0.75rem 1.5rem; background: #f1f5f9; color: #475569; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='#e2e8f0';"
                        onmouseout="this.style.background='#f1f5f9';"
                    >
                        Отмена
                    </button>
                    
                    @if(isset($itemKeyToUpdate))
                        <button 
                            wire:click="saveUpdatedModifiers"
                            style="padding: 0.75rem 1.5rem; background: linear-gradient(to right, #3b82f6, #1e40af); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';"
                            onmouseout="this.style.transform=''; this.style.boxShadow='none';"
                        >
                            Обновить
                        </button>
                    @else
                        <button 
                            wire:click="saveWithModifiers"
                            style="padding: 0.75rem 1.5rem; background: linear-gradient(to right, #3b82f6, #1e40af); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';"
                            onmouseout="this.style.transform=''; this.style.boxShadow='none';"
                        >
                            Добавить в заказ
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
    
    <script>
        function confirmOrder() {
            if (@json(empty($selectedItems))) {
                alert('Добавьте хотя бы одно блюдо в заказ');
                return false;
            }
            
            if (confirm('Вы уверены, что хотите создать заказ?')) {
                Livewire.dispatch('createOrder');
            }
        }
        
        // Автоматическое обновление списка блюд при изменении фильтров
        document.addEventListener('livewire:init', () => {
            Livewire.on('dish-added', () => {
                // Можно добавить звук или анимацию при добавлении блюда
            });
        });
    </script>
</x-filament-panels::page>