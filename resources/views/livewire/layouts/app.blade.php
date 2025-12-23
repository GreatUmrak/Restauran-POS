<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Тест стилей</title>
    
    <!-- Подключаем Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Или если используешь CDN -->
    <!--
    <script src="https://cdn.tailwindcss.com"></script>
    -->
</head>
<body class="bg-gray-50">
    {{ $slot }}
    
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>