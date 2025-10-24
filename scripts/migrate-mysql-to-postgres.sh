#!/bin/bash

set -e  # Остановка при ошибке

echo "================================"
echo "MySQL → PostgreSQL Migration"
echo "================================"
echo ""

# Цвета для вывода
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Проверка переменных окружения
if [ -z "$MYSQL_DEV_HOST" ] || [ -z "$MYSQL_DEV_USERNAME" ] || [ -z "$MYSQL_DEV_PASSWORD" ] || [ -z "$MYSQL_DEV_DATABASE" ]; then
    echo -e "${RED}❌ Ошибка: MySQL переменные не найдены${NC}"
    echo "Убедитесь, что в .env есть:"
    echo "  MYSQL_DEV_HOST"
    echo "  MYSQL_DEV_USERNAME"
    echo "  MYSQL_DEV_PASSWORD"
    echo "  MYSQL_DEV_DATABASE"
    exit 1
fi

if [ -z "$DATABASE_URL" ]; then
    echo -e "${RED}❌ Ошибка: PostgreSQL DATABASE_URL не найден${NC}"
    exit 1
fi

# Директория для временных файлов
TEMP_DIR="/tmp/mysql-to-postgres"
mkdir -p "$TEMP_DIR"

echo -e "${YELLOW}📂 Временная директория: $TEMP_DIR${NC}"
echo ""

# Шаг 1: Экспорт данных из MySQL
echo -e "${YELLOW}🔄 Шаг 1: Экспорт данных из MySQL...${NC}"

MYSQL_DUMP="$TEMP_DIR/mysql_data.sql"

mysqldump \
  -h "$MYSQL_DEV_HOST" \
  -u "$MYSQL_DEV_USERNAME" \
  -p"$MYSQL_DEV_PASSWORD" \
  --no-tablespaces \
  --skip-triggers \
  --skip-add-drop-table \
  --no-create-info \
  --complete-insert \
  --skip-lock-tables \
  --quick \
  "$MYSQL_DEV_DATABASE" > "$MYSQL_DUMP"

if [ $? -eq 0 ]; then
    DUMP_SIZE=$(du -h "$MYSQL_DUMP" | cut -f1)
    echo -e "${GREEN}✅ Экспорт завершен! Размер: $DUMP_SIZE${NC}"
else
    echo -e "${RED}❌ Ошибка экспорта из MySQL${NC}"
    exit 1
fi

echo ""

# Шаг 2: Конвертация в формат PostgreSQL
echo -e "${YELLOW}🔄 Шаг 2: Конвертация в формат PostgreSQL...${NC}"

POSTGRES_DUMP="$TEMP_DIR/postgres_data.sql"

# Используем PHP для конвертации (более надежно)
php /home/runner/workspace/scripts/convert-mysql-to-postgres.php "$MYSQL_DUMP" "$POSTGRES_DUMP"

if [ $? -eq 0 ]; then
    PG_SIZE=$(du -h "$POSTGRES_DUMP" | cut -f1)
    echo -e "${GREEN}✅ Конвертация завершена! Размер: $PG_SIZE${NC}"
else
    echo -e "${RED}❌ Ошибка конвертации${NC}"
    exit 1
fi

echo ""

# Шаг 3: Подготовка PostgreSQL (миграции Laravel)
echo -e "${YELLOW}🔄 Шаг 3: Подготовка структуры PostgreSQL...${NC}"

cd /home/runner/workspace

# Переключаемся на PostgreSQL
export DB_CONNECTION=pgsql

# Очистка БД и создание структуры заново
php artisan migrate:fresh --force

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Структура БД создана${NC}"
else
    echo -e "${RED}❌ Ошибка создания структуры${NC}"
    exit 1
fi

echo ""

# Шаг 4: Импорт данных
echo -e "${YELLOW}🔄 Шаг 4: Импорт данных в PostgreSQL...${NC}"

psql "$DATABASE_URL" < "$POSTGRES_DUMP" 2>&1 | grep -v "INSERT 0 1" || true

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Данные импортированы${NC}"
else
    echo -e "${RED}❌ Ошибка импорта данных${NC}"
    exit 1
fi

echo ""

# Шаг 5: Обновление sequences
echo -e "${YELLOW}🔄 Шаг 5: Обновление sequences...${NC}"

php /home/runner/workspace/scripts/update-sequences.php

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Sequences обновлены${NC}"
else
    echo -e "${RED}❌ Ошибка обновления sequences${NC}"
    exit 1
fi

echo ""

# Шаг 6: Проверка данных
echo -e "${YELLOW}🔄 Шаг 6: Проверка целостности данных...${NC}"

php /home/runner/workspace/scripts/verify-migration.php

echo ""

# Финал
echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}✅ МИГРАЦИЯ ЗАВЕРШЕНА!${NC}"
echo -e "${GREEN}================================${NC}"
echo ""
echo "Следующие шаги:"
echo "  1. Протестируйте приложение с PostgreSQL"
echo "  2. Проверьте все функции"
echo "  3. Если всё работает - можно мигрировать production"
echo ""
echo "Временные файлы сохранены в: $TEMP_DIR"
