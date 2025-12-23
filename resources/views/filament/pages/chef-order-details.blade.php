<div style="padding: 1rem;">
    <!-- Информация о заказе -->
    <div style="margin-bottom: 1.5rem;">
        <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Информация о заказе</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            <div>
                <div style="font-size: 0.875rem; color: #6b7280;">Номер заказа</div>
                <div style="font-weight: 500;">{{ $order->order_number }}</div>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: #6b7280;">Стол</div>
                <div style="font-weight: 500;">{{ $order->table->name }}</div>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: #6b7280;">Время создания</div>
                <div style="font-weight: 500;">{{ $order->created_at->format('H:i') }}</div>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: #6b7280;">Статус</div>
                <div>
                    <span style="display: inline-block; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; 
                        @if($order->status == 'created') background-color: #f3f4f6; color: #374151;
                        @elseif($order->status == 'preparing') background-color: #fef3c7; color: #92400e;
                        @elseif($order->status == 'ready') background-color: #d1fae5; color: #065f46;
                        @endif">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>
        </div>
        
        @if($order->notes)
            <div style="margin-top: 1rem;">
                <div style="font-size: 0.875rem; color: #6b7280;">Комментарий</div>
                <div style="padding: 0.75rem; background: #f3f4f6; border-radius: 0.375rem; margin-top: 0.25rem;">
                    {{ $order->notes }}
                </div>
            </div>
        @endif
    </div>
    
    <!-- Список блюд -->
    <div>
        <h3 style="font-weight: 600; margin-bottom: 1rem;">Состав заказа</h3>
        <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
            @foreach($order->items as $item)
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; @if($loop->last) border-bottom: none @endif">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <div>
                            <div style="font-weight: 500;">{{ $item->dish->name }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Количество: {{ $item->quantity }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 600;">{{ number_format($item->price * $item->quantity, 2) }} ₽</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">{{ number_format($item->price, 2) }} ₽/шт</div>
                        </div>
                    </div>
                    
                    @if($item->special_instructions)
                        <div style="margin-top: 0.5rem; padding: 0.5rem; background: #fef3c7; border-radius: 0.25rem;">
                            <div style="font-size: 0.75rem; font-weight: 500; color: #92400e; margin-bottom: 0.25rem;">Особые пожелания:</div>
                            <div style="font-size: 0.875rem;">{{ $item->special_instructions }}</div>
                        </div>
                    @endif
                    
                    <!-- Статус блюда (можно добавить если нужно) -->
                    @if(false) <!-- Заглушка для будущего функционала -->
                        <div style="margin-top: 0.5rem;">
                            <span style="display: inline-block; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; 
                                @if($item->status == 'pending') background-color: #f3f4f6; color: #374151;
                                @elseif($item->status == 'preparing') background-color: #fef3c7; color: #92400e;
                                @elseif($item->status == 'ready') background-color: #d1fae5; color: #065f46;
                                @endif">
                                {{ $item->status }}
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Итоговая сумма -->
    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid #e5e7eb;">
        <div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 1.125rem;">
            <span>Итого:</span>
            <span>{{ number_format($order->total_amount, 2) }} ₽</span>
        </div>
    </div>
</div>