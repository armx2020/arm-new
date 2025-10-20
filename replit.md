# Проект vsearmyne.ru - Армянский справочник

## Обзор проекта
Информационный справочник для армянского сообщества России и мира. Платформа для поиска компаний, групп, мест, вакансий и размещения предложений.

## Цель миграции
Разработка на Replit → push на GitHub → автоматический деплой на Timeweb (production).
- **Разработка**: Replit с PostgreSQL
- **Production**: Timeweb с MySQL (развертывание через Node.js приложение)

## Текущее состояние проекта

### ✅ Выполнено (20.10.2025)
1. **Импорт и настройка**
   - Успешно импортирован Laravel 10 проект с GitHub (https://github.com/armx2020/arm)
   - Установлены PHP 8.2, Node.js 20, PostgreSQL
   - Установлены 189 Composer пакетов и все NPM зависимости
   - Собран frontend (Vite + Tailwind CSS + Alpine.js)

2. **База данных**
   - Создана PostgreSQL база данных на Replit
   - Выполнены 26 миграций (адаптированы FULLTEXT индексы для PostgreSQL)
   - Добавлены тестовые данные:
     - 1 страна (Россия)
     - 1 регион (Московская область)
     - 1 город (Москва)
     - 5 типов сущностей с транслитерацией (companies, groups, places, jobs, projects, resumes)
     - 8 категорий (компании, IT, образование, медицина, строительство, группы, спорт, культура)
     - 1 тестовый пользователь
     - 6 тестовых сущностей (3 компании, 2 группы, 1 место)
     - 2 предложения

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

## Известные особенности
1. **PostgreSQL vs MySQL**: В dev используется PostgreSQL без поддержки FULLTEXT индексов (заменены на обычные индексы)
2. **Динамические маршруты**: DinamicRouteController обрабатывает множественные формы URL (companies, groups) через inflector
3. **Автоматическая настройка URL**: bootstrap/set-replit-url.php автоматически обновляет APP_URL при запуске сервера
4. **HTTPS через прокси**: TrustProxies middleware настроен для корректной работы с Replit прокси

## Следующие шаги
- [ ] Добавить больше тестовых данных для всех категорий
- [ ] Настроить автоматическую синхронизацию с production базой (опционально)
- [ ] Настроить деплой на Timeweb через GitHub Actions
- [ ] Протестировать все CRUD операции

## Важные файлы
- `routes/web.php` - основные маршруты приложения
- `app/Http/Controllers/Pages/DinamicRouteController.php` - контроллер динамических маршрутов
- `app/Http/Middleware/FromLocation.php` - middleware для определения региона
- `app/Http/Middleware/TrustProxies.php` - настройка работы с Replit прокси
- `bootstrap/set-replit-url.php` - автоматическая настройка APP_URL для Replit
- `config/menu.php` - конфигурация меню
- `database/migrations/` - миграции базы данных

## Переменные окружения
Доступны следующие секреты:
- `DATABASE_URL` - URL подключения к PostgreSQL
- `PGDATABASE`, `PGHOST`, `PGPORT`, `PGUSER`, `PGPASSWORD` - параметры PostgreSQL
- `SESSION_SECRET` - секрет для сессий

## Дата последнего обновления
20 октября 2025
