# Проект vsearmyne.ru - Армянский справочник

## Обзор проекта
Информационный справочник для армянского сообщества России и мира. Платформа для поиска компаний, групп, мест, вакансий и размещения предложений.

## Цель миграции
Разработка на Replit → push на GitHub → автоматический деплой на Timeweb (production).
- **Разработка**: Replit с PostgreSQL
- **Production**: Timeweb с MySQL (развертывание через Node.js приложение)

## Текущее состояние проекта

### ✅ Выполнено (21.10.2025)
1. **Импорт и настройка**
   - Успешно импортирован Laravel 10 проект с GitHub (https://github.com/armx2020/arm)
   - Установлены PHP 8.2, Node.js 20, PostgreSQL
   - Установлены 189 Composer пакетов и все NPM зависимости
   - Собран frontend (Vite + Tailwind CSS + Alpine.js)

2. **База данных**
   - **PostgreSQL** (development) - создана на Replit
     - Выполнены 26 миграций (адаптированы FULLTEXT индексы для PostgreSQL)
     - Добавлены тестовые данные для разработки
   - **MySQL** (production read-only) - подключена к Timeweb
     - 30 таблиц
     - 10,279 сущностей
     - 251 категория
     - Доступ только для чтения (безопасно)
   - Созданы скрипты для переключения между базами

3. **Маршруты**
   - Добавлены динамические маршруты для всех типов сущностей:
     - companies: index (список), region (по регионам), show (детали)
     - groups: index, region, show
     - places: index, region, show
     - communities: index, region, show
     - jobs: index, region, show
     - projects: index, region, show
     - resumes: index, region, show
   - Исправлен порядок маршрутов (region перед index для правильной маршрутизации)

4. **Исправления**
   - Исправлен FromLocation middleware для работы с null регионами
   - Обновлена транслитерация типов сущностей в базе данных
   - Восстановлен resources/js/app.js с импортом Alpine.js
   - Пересобран frontend - все стили Tailwind CSS работают корректно
   - Настроен TrustProxies middleware для работы с Replit прокси
   - Создан скрипт bootstrap/set-replit-url.php для автоматической настройки APP_URL

5. **Сервер**
   - Laravel сервер успешно запущен на порту 5000
   - Главная страница работает без ошибок с правильным дизайном
   - Все разделы (компании, группы, места) загружаются корректно
   - Frontend соответствует оригинальному дизайну с production
   - CSS и JavaScript файлы корректно загружаются через HTTPS
   - Workflow автоматически настраивает APP_URL при запуске

6. **Авторизация**
   - Временно отключены middleware TrackLastActiveAt и UpdateUserActivity
   - Вход работает с production MySQL (read-only) без ошибок
   - 17 пользователей доступны для тестирования

7. **Изображения**
   - Создан helper `storage_url()` для загрузки изображений с production
   - В dev режиме все изображения загружаются с https://vsearmyne.ru/storage
   - В production работает обычный asset()
   - Обновлены ключевые компоненты для использования helper
   - 7,048 изображений доступны через production сервер

## Технический стек
- **Backend**: Laravel 10, PHP 8.2
- **Frontend**: Blade, Vite, Tailwind CSS, Alpine.js
- **База данных**: PostgreSQL (dev), MySQL (production)
- **Сервер**: Laravel built-in server (dev)

## Структура базы данных
### Основные таблицы
- `users` - пользователи с ролями и разрешениями
- `entity_types` - типы сущностей (компании, группы, места и т.д.)
- `entities` - основная таблица сущностей
- `categories` - категории с поддержкой вложенности
- `offers` - предложения и акции
- `appeals` - сообщения/обращения
- `regions`, `cities` - географические данные
- `images` - изображения для сущностей

## Работа с базами данных

### Две базы данных:
1. **PostgreSQL (dev)** - для разработки, можно изменять данные
2. **MySQL (production)** - read-only доступ к production данным

### Переключение между базами:

**Использовать Production MySQL (просмотр реальных данных):**
```bash
./artisan-use-mysql
```

**Вернуться на PostgreSQL (разработка):**
```bash
./artisan-use-postgres
```

**Проверить текущее подключение:**
```bash
php artisan tinker --execute="echo DB::connection()->getDatabaseName();"
```

## Известные особенности
1. **PostgreSQL vs MySQL**: В dev используется PostgreSQL без поддержки FULLTEXT индексов (заменены на обычные индексы)
2. **Динамические маршруты**: DinamicRouteController обрабатывает множественные формы URL (companies, groups) через inflector
3. **Автоматическая настройка URL**: bootstrap/set-replit-url.php автоматически обновляет APP_URL при запуске сервера
4. **HTTPS через прокси**: TrustProxies middleware настроен для корректной работы с Replit прокси
5. **Read-only доступ к production**: Пользователь `replit_readonly` может только читать данные из MySQL

## Изображения и медиафайлы

### Текущая настройка (Development)
Все изображения загружаются с production сервера через helper функцию.

**Helper функция:**
```php
storage_url($path) // Автоматически подставляет production URL в dev
```

**Конфигурация (.env):**
```env
PRODUCTION_STORAGE_URL=https://vsearmyne.ru/storage
USE_PRODUCTION_IMAGES=true
```

**Статистика:**
- 7,048 изображений в базе данных
- Пути: `uploaded/doctor/2/2.png`, `users/xxx.png`

### Будущее решение: Timeweb S3
Рекомендуется перенести изображения на облачное хранилище Timeweb S3:
- Настроить S3 disk в Laravel
- Загрузить изображения с production в S3
- Использовать CDN для быстрой загрузки
- Единое хранилище для dev и production

## Следующие шаги
- [ ] Обновить остальные view для использования storage_url()
- [ ] Настроить Timeweb S3 для изображений (опционально)
- [ ] Добавить больше тестовых данных для всех категорий
- [ ] Настроить деплой на Timeweb через GitHub Actions
- [ ] Протестировать все CRUD операции

## Важные файлы
- `routes/web.php` - основные маршруты приложения
- `app/Http/Controllers/Pages/DinamicRouteController.php` - контроллер динамических маршрутов
- `app/Http/Middleware/FromLocation.php` - middleware для определения региона
- `app/Http/Middleware/TrustProxies.php` - настройка работы с Replit прокси
- `app/Http/Kernel.php` - регистрация middleware (TrackLastActiveAt отключен)
- `bootstrap/set-replit-url.php` - автоматическая настройка APP_URL для Replit
- `config/database.php` - настройка подключений к БД (PostgreSQL + MySQL)
- `config/filesystems.php` - настройка дисков для файлов
- `app/Helpers/StorageHelper.php` - helper для загрузки изображений с production
- `app/helpers.php` - глобальные helper функции (storage_url)
- `artisan-use-mysql-prod` - скрипт переключения на production MySQL (read-only)
- `artisan-use-mysql` - скрипт переключения на MySQL dev копию (когда будет готова)
- `artisan-use-postgres` - скрипт переключения на development PostgreSQL
- `config/menu.php` - конфигурация меню
- `database/migrations/` - миграции базы данных
- `IMAGES_SETUP.md` - документация по настройке изображений

## Переменные окружения
Доступны следующие секреты:
- **PostgreSQL (development):**
  - `DATABASE_URL` - URL подключения к PostgreSQL
  - `PGDATABASE`, `PGHOST`, `PGPORT`, `PGUSER`, `PGPASSWORD` - параметры PostgreSQL
- **MySQL (production read-only):**
  - `MYSQL_HOST` - IP адрес сервера Timeweb
  - `MYSQL_PORT` - порт (3306)
  - `MYSQL_DATABASE` - имя базы данных
  - `MYSQL_USERNAME` - пользователь (replit_readonly)
  - `MYSQL_PASSWORD` - пароль (хранится в секретах)
- `SESSION_SECRET` - секрет для сессий

## Известные ограничения (READ-ONLY режим)
Поскольку production MySQL подключена в режиме read-only:
- ✅ Можно просматривать все данные
- ✅ Можно входить в систему (middleware отключены)
- ❌ Нельзя создавать/редактировать/удалять данные
- ❌ Нельзя загружать новые изображения

Для полноценной разработки используйте PostgreSQL (`./artisan-use-postgres`)

## Дата последнего обновления
21 октября 2025
