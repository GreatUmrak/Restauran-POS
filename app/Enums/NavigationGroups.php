<?php

namespace App\Enums;

enum NavigationGroups: string
{
    case Menu = 'Меню';
    case Restaurant = 'Ресторан';
    case Orders = 'Заказы';
    case Users = 'Пользователи';
    case Settings = 'Настройки';
}