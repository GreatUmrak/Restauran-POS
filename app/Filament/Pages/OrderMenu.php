<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class OrderMenu extends Page
{
    protected string $view = 'filament.pages.order-menu';
    
    // Данные
    public $table_id;
    public $categories;
    public $dishes;
    public $search = '';
    public $categoryFilter = '';
    public $guests_count = 1;
    public $notes = '';
    public $selectedItems = [];
    public $showModifiersModal = false;
    public $currentDishId = null;
    public $selectedModifiers = [];
    public $itemKeyToUpdate = null;
    
    public function mount(): void
    {
        $this->table_id = request()->query('table_id');
        $this->categories = Category::where('is_active', true)->get();
        $this->loadDishes();
    }
    
    public function loadDishes()
    {
        $query = Dish::where('is_active', true)
            ->with(['category', 'modifierGroups.modifiers']);
            
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }
        
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }
        
        $this->dishes = $query->get();
    }
    
    public function updatedSearch()
    {
        $this->loadDishes();
    }
    
    public function updatedCategoryFilter()
    {
        $this->loadDishes();
    }
    
    public function addDish($dishId)
    {
        $dish = Dish::with('modifierGroups.modifiers')->find($dishId);
        
        // Если у блюда есть модификаторы, показываем модальное окно
        if ($dish->modifierGroups->count() > 0) {
            $this->currentDishId = $dishId;
            $this->selectedModifiers = [];
            $this->showModifiersModal = true;
        } else {
            // Если нет модификаторов, просто добавляем блюдо
            if (isset($this->selectedItems[$dishId])) {
                $this->selectedItems[$dishId]['quantity']++;
            } else {
                $this->selectedItems[$dishId] = [
                    'dish_id' => $dishId,
                    'name' => $dish->name,
                    'price' => $dish->price,
                    'quantity' => 1,
                    'special_instructions' => '',
                    'modifiers' => []
                ];
            }
            
            Notification::make()
                ->title('Блюдо добавлено')
                ->body($dish->name . ' добавлено в заказ')
                ->success()
                ->send();
        }
    }
    
    public function removeDish($dishId)
    {
        unset($this->selectedItems[$dishId]);
    }
    
    public function updateQuantity($dishId, $quantity)
    {
        if (isset($this->selectedItems[$dishId]) && $quantity > 0) {
            $this->selectedItems[$dishId]['quantity'] = $quantity;
        }
    }
    
    public function updateInstructions($dishId, $instructions)
    {
        if (isset($this->selectedItems[$dishId])) {
            $this->selectedItems[$dishId]['special_instructions'] = $instructions;
        }
    }
    
    public function saveWithModifiers()
    {
        $dishId = $this->currentDishId;
        $dish = Dish::find($dishId);
        
        if (!$dish) {
            return;
        }
        
        // Рассчитываем общую цену с модификаторами
        $modifiersPrice = 0;
        $modifiersList = [];
        
        foreach ($this->selectedModifiers as $modifierId => $selected) {
            if ($selected) {
                $modifier = \App\Models\Modifier::find($modifierId);
                if ($modifier) {
                    $modifiersPrice += $modifier->price;
                    $modifiersList[] = [
                        'id' => $modifierId,
                        'name' => $modifier->name,
                        'price' => $modifier->price
                    ];
                }
            }
        }
        
        // Добавляем или обновляем блюдо в заказе
        if (isset($this->selectedItems[$dishId])) {
            $this->selectedItems[$dishId]['quantity']++;
            $this->selectedItems[$dishId]['modifiers'] = $modifiersList;
            $this->selectedItems[$dishId]['price'] = $dish->price + $modifiersPrice;
        } else {
            $this->selectedItems[$dishId] = [
                'dish_id' => $dishId,
                'name' => $dish->name,
                'price' => $dish->price + $modifiersPrice,
                'quantity' => 1,
                'special_instructions' => '',
                'modifiers' => $modifiersList
            ];
        }
        
        $this->showModifiersModal = false;
        $this->currentDishId = null;
        $this->selectedModifiers = [];
        
        Notification::make()
            ->title('Блюдо добавлено')
            ->body($dish->name . ' с выбранными модификаторами добавлено в заказ')
            ->success()
            ->send();
    }
    
    public function closeModifiersModal()
    {
        $this->showModifiersModal = false;
        $this->currentDishId = null;
        $this->selectedModifiers = [];
        $this->itemKeyToUpdate = null;
    }
    
    public function getCurrentDish()
    {
        if ($this->currentDishId) {
            return Dish::with(['modifierGroups.modifiers'])->find($this->currentDishId);
        }
        return null;
    }
    
    public function updateItemWithModifiers($dishId, $itemKey)
    {
        $dish = Dish::with('modifierGroups.modifiers')->find($dishId);
        
        if ($dish && $dish->modifierGroups->count() > 0) {
            $this->currentDishId = $dishId;
            // Загружаем текущие модификаторы
            if (isset($this->selectedItems[$dishId]['modifiers'])) {
                foreach ($this->selectedItems[$dishId]['modifiers'] as $modifier) {
                    $this->selectedModifiers[$modifier['id']] = true;
                }
            }
            $this->itemKeyToUpdate = $itemKey;
            $this->showModifiersModal = true;
        }
    }

    // Добавим эти методы в класс OrderMenu
public function incrementGuests()
{
    if ($this->table_id) {
        $selectedTable = \App\Models\Table::find($this->table_id);
        $this->guests_count = min($selectedTable->capacity, $this->guests_count + 1);
    }
}

public function decrementGuests()
{
    $this->guests_count = max(1, $this->guests_count - 1);
}
    
    public function saveUpdatedModifiers()
    {
        $dishId = $this->currentDishId;
        $dish = Dish::find($dishId);
        
        if (!$dish || !isset($this->selectedItems[$dishId])) {
            return;
        }
        
        // Рассчитываем общую цену с модификаторами
        $modifiersPrice = 0;
        $modifiersList = [];
        
        foreach ($this->selectedModifiers as $modifierId => $selected) {
            if ($selected) {
                $modifier = \App\Models\Modifier::find($modifierId);
                if ($modifier) {
                    $modifiersPrice += $modifier->price;
                    $modifiersList[] = [
                        'id' => $modifierId,
                        'name' => $modifier->name,
                        'price' => $modifier->price
                    ];
                }
            }
        }
        
        // Обновляем блюдо с новыми модификаторами
        $this->selectedItems[$dishId]['modifiers'] = $modifiersList;
        $this->selectedItems[$dishId]['price'] = $dish->price + $modifiersPrice;
        
        $this->showModifiersModal = false;
        $this->currentDishId = null;
        $this->selectedModifiers = [];
        $this->itemKeyToUpdate = null;
        
        Notification::make()
            ->title('Модификаторы обновлены')
            ->body('Модификаторы для ' . $dish->name . ' обновлены')
            ->success()
            ->send();
    }
    
    public function createOrder()
    {
        // Валидация
        if (empty($this->selectedItems)) {
            Notification::make()
                ->title('Ошибка!')
                ->body('Добавьте хотя бы одно блюдо в заказ')
                ->danger()
                ->send();
            return;
        }

        if (!$this->table_id) {
            Notification::make()
                ->title('Ошибка!')
                ->body('Не выбран стол')
                ->danger()
                ->send();
            return;
        }

        // Создаем заказ
        $order = Order::create([
            'table_id' => $this->table_id,
            'user_id' => Auth::id(),
            'guests_count' => $this->guests_count,
            'notes' => $this->notes,
            'status' => 'preparing',
            'total_amount' => $this->getTotalAmount(),
        ]);

        // Добавляем блюда в заказ
        foreach ($this->selectedItems as $itemData) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'dish_id' => $itemData['dish_id'],
                'quantity' => $itemData['quantity'],
                'price' => $itemData['price'],
                'special_instructions' => $itemData['special_instructions'],
                'status' => 'pending',
            ]);
            
            // Сохраняем модификаторы, если они есть
            if (!empty($itemData['modifiers'])) {
                foreach ($itemData['modifiers'] as $modifier) {
                    \App\Models\OrderItemModifier::create([
                        'order_item_id' => $orderItem->id,
                        'modifier_id' => $modifier['id'],
                        'price' => $modifier['price'],
                    ]);
                }
            }
        }

        Notification::make()
            ->title('Заказ создан успешно!')
            ->body('Номер заказа: ' . $order->order_number)
            ->success()
            ->send();

        return redirect()->route('filament.admin.pages.waiter-dashboard');
    }
    
    public function getTotalAmount()
    {
        $total = 0;
        foreach ($this->selectedItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    
    public function getSelectedItemsCount()
    {
        return count($this->selectedItems);
    }
    
    public function getAvailableTables()
    {
        return Table::orderBy('number')->get();
    }
    
    // Ограничиваем доступ
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('waiter') || Auth::user()->hasRole('admin');
    }
    
    // Геттеры вместо свойств
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clipboard-document';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Рабочая зона';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Меню для заказа';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('waiter') || Auth::user()->hasRole('admin');
    }
}