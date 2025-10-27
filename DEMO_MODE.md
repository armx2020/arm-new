# 🎯 DEMO режим для разработки на Replit

## Что это?

DEMO режим позволяет **мгновенно** загружать сайт на Replit без подключения к медленной MySQL базе на Timeweb.

## Как работает?

```
┌─────────────────────────────────────┐
│  REPLIT (разработка)                │
│  ┌───────────────────────────────┐  │
│  │ 🟢 DEMO режим (по умолчанию)  │  │
│  │ Моковые данные в памяти       │  │
│  │ Скорость: 0.01 сек ⚡⚡⚡      │  │
│  │                               │  │
│  │ [Кнопка переключения]         │  │
│  │                               │  │
│  │ 🔴 БОЕВОЙ режим               │  │
│  │ MySQL Timeweb (реальные)      │  │
│  │ Скорость: 2-3 сек 🐢          │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│  TIMEWEB (продакшн)                 │
│  Всегда MySQL - никаких изменений!  │
└─────────────────────────────────────┘
```

## Использование в коде

### Вариант 1: Через DemoDataService

```php
use App\Services\DemoDataService;

class HomeController extends Controller
{
    public function index(DemoDataService $demo)
    {
        $entities = $demo->isDemoMode()
            ? $demo->getEntities()      // 10 моковых записей
            : Entity::all();             // Реальные из MySQL

        return view('home', compact('entities'));
    }
}
```

### Вариант 2: Напрямую через session

```php
$data = session('demo_mode', true)
    ? collect([...])  // Моковые данные
    : Model::all();   // Реальная БД
```

## Доступные методы DemoDataService

```php
$demo->getEntities()           // 10 сущностей (компании/места)
$demo->getRegions()            // 10 регионов
$demo->getCategories()         // 10 категорий
$demo->getTelegramGroups()     // 6 Telegram групп
$demo->getTelegramMessages()   // 10 Telegram сообщений
$demo->getUsers()              // 5 пользователей
$demo->isDemoMode()            // true/false
```

## Кнопка переключения

Кнопка автоматически появляется в правом нижнем углу **только на Replit**.

На продакшене (Timeweb) кнопка **не отображается**.

## Переменные окружения

### Replit (.env)
```env
APP_ENV=local  # ← Включает DEMO режим
```

### Timeweb (.env)
```env
APP_ENV=production  # ← Отключает DEMO режим навсегда
```

## Файлы системы

```
app/
├── Services/
│   └── DemoDataService.php         # Моковые данные
├── Http/
│   └── Middleware/
│       └── DemoMode.php             # Middleware
├── Console/Commands/
    └── ExportDemoData.php           # Команда экспорта (опционально)

resources/views/components/
└── demo-mode-toggle.blade.php       # UI кнопка

routes/web.php                        # POST /toggle-demo-mode
```

## Скорость загрузки

| Режим    | Источник данных | Скорость    |
|----------|----------------|-------------|
| 🟢 DEMO  | Память PHP     | 0.01 сек ⚡ |
| 🔴 БОЕВОЙ| MySQL Timeweb  | 2-3 сек 🐢  |

**Разница:** В 200 раз быстрее!

## Важно! ⚠️

- DEMO режим работает **только на Replit**
- На продакшене **всё работает как обычно** с MySQL
- Код деплоится **без изменений**
- Переключение работает **только для текущей сессии**
