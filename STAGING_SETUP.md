# 🚀 Настройка Staging Окружения на VPS "Копия arm"

## 📋 Информация о сервере

- **IP:** 78.40.219.141
- **Доступ:** ssh root@78.40.219.141
- **База данных:** armbase2 (46.229.214.78:3306)
- **Домен:** test.vsearmyne.ru (планируется)

---

## 🔧 Шаг 1: Подготовка VPS

### 1.1 Подключитесь к VPS по SSH

```bash
ssh root@78.40.219.141
```

### 1.2 Обновите систему и установите необходимые пакеты

```bash
# Обновление системы
apt update && apt upgrade -y

# Установка основных пакетов
apt install -y nginx php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-zip php8.3-gd php8.3-curl php8.3-bcmath git composer curl unzip

# Проверка версий
php -v
nginx -v
composer --version
```

---

## 📁 Шаг 2: Клонирование проекта

### 2.1 Создайте директорию для проекта

```bash
mkdir -p /var/www/staging.vsearmyne.ru
cd /var/www/staging.vsearmyne.ru
```

### 2.2 Клонируйте репозиторий

```bash
git clone https://github.com/armx2020/arm-new.git .

# Или используйте SSH ключ для приватного репозитория:
# git clone git@github.com:armx2020/arm-new.git .
```

### 2.3 Скопируйте файлы деплоя

```bash
# Сделайте скрипт деплоя исполняемым
chmod +x deploy-staging.sh
chmod +x webhook-staging.php
```

---

## ⚙️ Шаг 3: Настройка окружения

### 3.1 Создайте файл .env

```bash
cp .env.example .env
nano .env
```

### 3.2 Настройте переменные окружения

```env
APP_NAME="vseArmyne Staging"
APP_ENV=staging
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=true
APP_URL=http://78.40.219.141

# Database (armbase2 на Timeweb)
DB_CONNECTION=mysql
DB_HOST=46.229.214.78
DB_PORT=3306
DB_DATABASE=armbase2
DB_USERNAME=gen_user2
DB_PASSWORD=OpDa3>5yr7%NQL

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Images from production
USE_PRODUCTION_IMAGES=true
PRODUCTION_STORAGE_URL=https://vsearmyne.ru/storage
```

### 3.3 Установите зависимости и настройте Laravel

```bash
# Установка composer зависимостей
composer install --no-dev --optimize-autoloader

# Генерация ключа приложения
php artisan key:generate

# Запуск миграций
php artisan migrate --force

# Кеширование конфигурации
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Установка прав
chown -R www-data:www-data /var/www/staging.vsearmyne.ru
chmod -R 775 storage bootstrap/cache
```

---

## 🌐 Шаг 4: Настройка Nginx

### 4.1 Создайте конфигурацию Nginx

```bash
nano /etc/nginx/sites-available/staging.vsearmyne.ru
```

### 4.2 Добавьте конфигурацию

```nginx
server {
    listen 80;
    server_name 78.40.219.141 test.vsearmyne.ru;
    
    root /var/www/staging.vsearmyne.ru/public;
    index index.php index.html;

    # Логи
    access_log /var/log/nginx/staging-access.log;
    error_log /var/log/nginx/staging-error.log;

    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Webhook endpoint (доступен по HTTP)
    location = /webhook-staging.php {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME /var/www/staging.vsearmyne.ru/webhook-staging.php;
        include fastcgi_params;
    }

    # Запрет доступа к скрытым файлам
    location ~ /\. {
        deny all;
    }

    # Запрет доступа к скриптам деплоя
    location ~ \.(sh|log)$ {
        deny all;
    }
}
```

### 4.3 Активируйте конфигурацию

```bash
# Создайте симлинк
ln -s /etc/nginx/sites-available/staging.vsearmyne.ru /etc/nginx/sites-enabled/

# Проверьте конфигурацию
nginx -t

# Перезапустите Nginx
systemctl restart nginx
```

---

## 🔗 Шаг 5: Настройка GitHub Webhook

### 5.1 Сгенерируйте секретный ключ

```bash
# На сервере
openssl rand -hex 32
# Скопируйте результат
```

### 5.2 Обновите webhook-staging.php

```bash
nano /var/www/staging.vsearmyne.ru/webhook-staging.php
```

Замените `'your-staging-webhook-secret-here'` на сгенерированный ключ.

### 5.3 Настройте webhook в GitHub

1. Откройте https://github.com/armx2020/arm-new/settings/hooks
2. Нажмите "Add webhook"
3. Заполните:
   - **Payload URL:** `http://78.40.219.141/webhook-staging.php`
   - **Content type:** `application/json`
   - **Secret:** ваш сгенерированный ключ
   - **Which events:** Just the push event
   - **Active:** ✅
4. Нажмите "Add webhook"

---

## ✅ Шаг 6: Тестирование

### 6.1 Проверьте сайт

Откройте в браузере:
```
http://78.40.219.141
```

### 6.2 Проверьте webhook

```bash
# Сделайте коммит и push в GitHub
git add .
git commit -m "Test staging webhook"
git push

# Проверьте логи на сервере
tail -f /var/www/staging.vsearmyne.ru/webhook-staging.log
```

### 6.3 Проверьте логи Nginx

```bash
tail -f /var/log/nginx/staging-error.log
tail -f /var/log/nginx/staging-access.log
```

---

## 🔒 Шаг 7: Настройка домена test.vsearmyne.ru (опционально)

### 7.1 Добавьте A-запись в DNS

В панели управления доменом добавьте:
```
Тип: A
Имя: test
Значение: 78.40.219.141
TTL: 3600
```

### 7.2 Обновите .env

```bash
nano /var/www/staging.vsearmyne.ru/.env
```

Измените:
```env
APP_URL=https://test.vsearmyne.ru
```

### 7.3 Установите SSL (Let's Encrypt)

```bash
# Установите certbot
apt install -y certbot python3-certbot-nginx

# Получите сертификат
certbot --nginx -d test.vsearmyne.ru

# Автообновление сертификата уже настроено
```

---

## 📝 Полезные команды

### Ручной деплой
```bash
cd /var/www/staging.vsearmyne.ru
bash deploy-staging.sh
```

### Просмотр логов
```bash
# Логи webhook
tail -f /var/www/staging.vsearmyne.ru/webhook-staging.log

# Логи Laravel
tail -f /var/www/staging.vsearmyne.ru/storage/logs/laravel.log

# Логи Nginx
tail -f /var/log/nginx/staging-error.log
```

### Перезапуск сервисов
```bash
systemctl restart nginx
systemctl restart php8.3-fpm
```

### Очистка кеша
```bash
cd /var/www/staging.vsearmyne.ru
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 🎯 Итого

После выполнения всех шагов у вас будет:

✅ Staging сайт на http://78.40.219.141  
✅ Автоматический деплой при push в GitHub  
✅ Подключение к базе данных armbase2  
✅ Изображения с production сервера  
✅ (Опционально) HTTPS на test.vsearmyne.ru  

**Workflow:** Replit → GitHub → Auto-deploy to VPS Staging → Test → Production
