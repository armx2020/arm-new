# 📸 Настройка изображений с Production сервера

## ✅ Что настроено:

### 1. Helper функция `storage_url()`
Автоматически подставляет URL production сервера для изображений в development режиме.

**Использование:**
```blade
{{-- Старый способ --}}
<img src="{{ asset('storage/' . $entity->primaryImage->path) }}" />

{{-- Новый способ (работает с production) --}}
<img src="{{ storage_url($entity->primaryImage->path) }}" />
```

### 2. Конфигурация (.env)
```env
PRODUCTION_STORAGE_URL=https://vsearmyne.ru/storage
USE_PRODUCTION_IMAGES=true
```

### 3. Обновленные файлы:
- ✅ `resources/views/components/profile/card.blade.php`
- ✅ `resources/views/dashboard.blade.php`

### 4. Требующие обновления файлы:
Замените `asset('storage/' . $path)` на `storage_url($path)` в:
- `resources/views/livewire/profile/chat-window.blade.php`
- `resources/views/livewire/profile/chat-window-for-entity.blade.php`
- `resources/views/livewire/profile/chat-list.blade.php`
- `resources/views/livewire/profile/chat-list-for-entity.blade.php`

## 🔄 Как это работает:

**В Development (Replit):**
```php
storage_url('uploaded/church/21/21_1.jpg')
// → https://vsearmyne.ru/storage/uploaded/church/21/21_1.jpg
```

**В Production (Timeweb):**
```php
storage_url('uploaded/church/21/21_1.jpg')
// → /storage/uploaded/church/21/21_1.jpg (локальный файл)
```

## 📦 Будущее решение: Timeweb S3

Для production рекомендуется перенести изображения на S3:

1. Создать bucket в Timeweb Cloud
2. Получить Access Key и Secret Key
3. Загрузить изображения в S3
4. Обновить Laravel для работы с S3

**Преимущества S3:**
- ☁️ Облачное хранилище
- 🚀 CDN для быстрой загрузки
- 💾 Не занимает место на сервере
- 🔄 Одно хранилище для dev и production
