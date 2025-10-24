# Миграция MySQL → PostgreSQL

## 📋 Обзор

Скрипты для безопасной миграции данных из MySQL (Timeweb) в PostgreSQL (Replit).

## 🎯 Что делают скрипты

### 1. `migrate-mysql-to-postgres.sh` (главный скрипт)
Координирует весь процесс миграции:
- Экспортирует данные из MySQL
- Конвертирует в формат PostgreSQL
- Создает структуру БД (Laravel migrations)
- Импортирует данные
- Обновляет sequences
- Проверяет целостность

### 2. `convert-mysql-to-postgres.php`
Конвертирует MySQL dump в PostgreSQL формат:
- Заменяет backticks на кавычки
- Конвертирует булевы значения (0/1 → FALSE/TRUE)
- Исправляет даты (0000-00-00 → NULL)
- Оптимизирует SQL

### 3. `update-sequences.php`
Обновляет auto-increment счетчики:
- Находит все таблицы с полем `id`
- Устанавливает sequence = MAX(id)
- Гарантирует, что новые записи получат правильные ID

### 4. `verify-migration.php`
Проверяет целостность данных:
- Подсчитывает записи в таблицах
- Проверяет foreign key связи
- Выявляет несвязанные записи

## 🚀 Использование

### Подготовка

1. Убедитесь, что в `.env` есть MySQL credentials:
```env
MYSQL_DEV_HOST=46.229.214.78
MYSQL_DEV_USERNAME=gen_user2
MYSQL_DEV_PASSWORD=ваш_пароль
MYSQL_DEV_DATABASE=armbase-2
```

2. Убедитесь, что PostgreSQL настроен:
```env
DATABASE_URL=postgresql://...
DB_CONNECTION=pgsql
```

### Запуск миграции

```bash
cd /home/runner/workspace
bash scripts/migrate-mysql-to-postgres.sh
```

### Что происходит

```
🔄 Шаг 1: Экспорт из MySQL (Timeweb)
   └─ Создается файл /tmp/mysql-to-postgres/mysql_data.sql
   
🔄 Шаг 2: Конвертация в PostgreSQL формат
   └─ Создается файл /tmp/mysql-to-postgres/postgres_data.sql
   
🔄 Шаг 3: Подготовка PostgreSQL структуры
   └─ Выполняются Laravel migrations
   
🔄 Шаг 4: Импорт данных
   └─ Загрузка в PostgreSQL (Replit)
   
🔄 Шаг 5: Обновление sequences
   └─ Счетчики приводятся в соответствие
   
🔄 Шаг 6: Проверка целостности
   └─ Отчет о количестве записей и связях
```

## ⚠️ Важно

- **MySQL НЕ ТРОГАЕТСЯ** - только чтение
- **PostgreSQL ОЧИЩАЕТСЯ** - старые данные удаляются (migrate:fresh)
- **Временные файлы** сохраняются в `/tmp/mysql-to-postgres/`

## 🔍 После миграции

1. Проверьте данные в PostgreSQL
2. Протестируйте приложение
3. Проверьте все функции

### Переключение на PostgreSQL

В `.env`:
```env
DB_CONNECTION=pgsql  # Было: mysql
```

Перезапустите сервер:
```bash
php artisan config:clear
```

## 📊 Проверка результатов

### Количество записей
```bash
php scripts/verify-migration.php
```

### SQL запросы
```sql
-- Подключитесь к PostgreSQL
psql $DATABASE_URL

-- Проверьте данные
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM entities;
SELECT COUNT(*) FROM images;
```

## 🐛 Отладка

### Ошибки при импорте
Логи сохраняются во время выполнения. Проверьте вывод скрипта.

### Проблемы с sequences
Запустите вручную:
```bash
php scripts/update-sequences.php
```

### Несвязанные записи
Проверьте foreign keys:
```bash
php scripts/verify-migration.php
```

## ✅ Откат

Если что-то пошло не так:

1. Вернитесь на MySQL:
```env
DB_CONNECTION=mysql
```

2. PostgreSQL можно очистить и повторить миграцию:
```bash
php artisan migrate:fresh --force
bash scripts/migrate-mysql-to-postgres.sh
```

MySQL данные остаются нетронутыми!

## 📞 Поддержка

При проблемах проверьте:
- Переменные окружения (`.env`)
- Доступ к MySQL (firewall Timeweb)
- Подключение к PostgreSQL
- Логи Laravel (`storage/logs/laravel.log`)
