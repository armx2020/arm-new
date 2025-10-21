# vsearmyne.ru - Армянский справочник

> Информационный справочник для армянского сообщества России и мира

## 🚀 О проекте

Платформа для поиска и публикации информации о:
- 🏢 Компаниях
- 👥 Группах и сообществах  
- 📍 Местах
- 💼 Вакансиях
- 🎯 Проектах

## 🛠 Технологический стек

- **Backend**: Laravel 10, PHP 8.2
- **Frontend**: Blade, Vite, Tailwind CSS, Alpine.js
- **Database**: MySQL 8.0
- **Development**: Replit
- **Deployment**: Automated via GitHub Webhooks

## 📦 Инфраструктура

### Production
- Сервер: Timeweb Cloud (213.109.204.16)
- База данных: ArmBase (212.113.118.69)
- Домен: https://vsearmyne.ru

### Staging/Test
- Сервер: Timeweb Cloud (78.40.219.141)
- База данных: armbase-2 (46.229.214.78)
- Домен: https://test.vsearmyne.ru

## 🔄 Workflow разработки

```
Replit (разработка) → GitHub (push) → Timeweb (автодеплой)
```

## 📝 Статистика

- 10,279 сущностей
- 7,048 изображений
- 251 категория
- 17 пользователей

## 🔐 Безопасность

Проект использует:
- Role-based access control (Spatie Permissions)
- CSRF защиту
- XSS защиту (Blade auto-escape)
- SQL Injection защиту (Eloquent ORM)

## 📄 Лицензия

Proprietary - © 2024 vsearmyne.ru
