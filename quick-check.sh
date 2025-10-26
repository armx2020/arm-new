#!/bin/bash

echo "🔍 Быстрая проверка системы vsearmyne.ru"
echo "========================================"
echo ""

echo "1️⃣ Проверка Laravel сервера..."
if curl -s -o /dev/null -w "%{http_code}" http://0.0.0.0:5000 | grep -q "200\|302"; then
    echo "✅ Сервер работает"
else
    echo "❌ Сервер не отвечает"
fi
echo ""

echo "2️⃣ Проверка конфигурации..."
if php artisan config:show app.key --json | grep -q "app.key"; then
    echo "✅ APP_KEY установлен"
else
    echo "❌ APP_KEY отсутствует"
fi
echo ""

echo "3️⃣ Проверка кеша..."
CACHE_FILES=$(ls -1 storage/framework/cache/data 2>/dev/null | wc -l)
echo "📦 Файлов кеша: $CACHE_FILES"
echo ""

echo "4️⃣ Проверка логов..."
LOG_SIZE=$(du -sh storage/logs/laravel.log 2>/dev/null | cut -f1)
ERROR_COUNT=$(grep -c "ERROR" storage/logs/laravel.log 2>/dev/null || echo "0")
echo "📄 Размер лога: $LOG_SIZE"
echo "🔴 Ошибок в логе: $ERROR_COUNT"
echo ""

echo "5️⃣ Проверка storage permissions..."
if [ -w "storage/logs" ] && [ -w "storage/framework" ]; then
    echo "✅ Права доступа корректны"
else
    echo "❌ Нет прав на запись в storage/"
fi
echo ""

echo "6️⃣ Проверка переменных окружения..."
if [ -f ".env" ]; then
    echo "✅ Файл .env существует"
    
    if grep -q "DB_HOST=" .env; then
        echo "✅ DB_HOST установлен"
    fi
    
    if grep -q "AWS_BUCKET=" .env; then
        echo "✅ AWS_BUCKET установлен"
    fi
else
    echo "❌ Файл .env отсутствует!"
fi
echo ""

echo "7️⃣ Последние 5 ошибок из лога:"
tail -500 storage/logs/laravel.log 2>/dev/null | grep "ERROR" | tail -5 || echo "  (нет ошибок)"
echo ""

echo "========================================"
echo "✅ Быстрая проверка завершена"
echo ""
echo "Для полной проверки запустите:"
echo "  php artisan system:check"
