<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Table;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Устанавливаем текущего пользователя как официанта
        $data['user_id'] = auth()->id();
        
        // Если передан table_id, обновляем статус стола
        if (isset($data['table_id']) && $data['table_id']) {
            $table = Table::find($data['table_id']);
            if ($table) {
                $table->status = 'in_process';
                $table->save();
            }
        }
        
        // Генерируем номер заказа
        $data['order_number'] = 'ORD-' . date('Ymd') . '-' . str_pad(
            \App\Models\Order::whereDate('created_at', today())->count() + 1, 
            4, '0', STR_PAD_LEFT
        );
        
        // Статус по умолчанию: "готовится" для официанта, "создан" для админа
        if (Auth::user()->hasRole('waiter')) {
            $data['status'] = 'preparing'; // Готовится
        } else {
            $data['status'] = 'created'; // Создан
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}