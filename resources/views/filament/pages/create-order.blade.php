<x-filament-panels::page>
    <x-filament-panels::header
        heading="Создание нового заказа"
        subheading="Заполните информацию о заказе"
    />
    
    <form wire:submit="createOrder">
        {{ $this->form }}
        
        <x-filament-panels::form.actions
            :actions="$this->getFormActions()"
        />
    </form>
    
    <!-- Каталог блюд для быстрого выбора -->
    <div class="mt-8 rounded-lg bg-gray-50 p-6">
        <h3 class="mb-4 text-lg font-semibold">Каталог блюд</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @php
                $dishes = \App\Models\Dish::with('category')
                    ->where('is_active', true)
                    ->get()
                    ->groupBy('category.name');
            @endphp
            
            @foreach($dishes as $categoryName => $categoryDishes)
                <div class="rounded-lg border bg-white p-4">
                    <h4 class="mb-3 font-semibold text-gray-700">{{ $categoryName }}</h4>
                    <div class="space-y-2">
                        @foreach($categoryDishes as $dish)
                            <button
                                type="button"
                                wire:click="addDish({{ $dish->id }})"
                                class="flex w-full items-center justify-between rounded border border-gray-200 p-3 text-left hover:bg-gray-50">
                                <div>
                                    <p class="font-medium">{{ $dish->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $dish->description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">{{ number_format($dish->price, 2) }} ₽</p>
                                    @if($dish->preparation_time)
                                        <p class="text-xs text-gray-500">{{ $dish->preparation_time }} мин</p>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    @push('scripts')
        <script>
            function addDish(dishId) {
                // Логика добавления блюда в заказ
                // Реализуем позже через Livewire
            }
        </script>
    @endpush
</x-filament-panels::page>