# Titan WordPress Theme

WordPress тема для сайта titan.realeasystudio.site

## Локальная разработка

### Запуск Docker окружения

Проект использует Docker для локальной разработки WordPress.

**Запуск контейнеров:**
```bash
docker-compose up -d
```

**Остановка контейнеров:**
```bash
docker-compose down
```

**Просмотр логов:**
```bash
docker-compose logs -f
```

### Доступ к сайту

После запуска Docker:
- WordPress: http://localhost:8080
- База данных:
  - Host: `db`
  - User: `wp_user`
  - Password: `wp_password`
  - Database: `wp_database`

### Структура проекта

```
titan/
├── wp-content/
│   └── themes/
│       └── titan/          # Основная тема
├── docker-compose.yml      # Docker конфигурация
└── .github/
    └── workflows/
        └── deploy.yml      # CI/CD автодеплой
```

## Деплой

### Автоматический деплой

При push в ветку `main` автоматически запускается GitHub Actions workflow, который:
1. Собирает тему (исключая dev-файлы)
2. Деплоит на сервер через rsync
3. Очищает WordPress кеш

**Проверить статус деплоя:**
- GitHub → Actions → Deploy Theme to Server

**Запустить деплой вручную:**
- GitHub → Actions → Deploy Theme to Server → Run workflow

## Разработка

1. Запустите Docker окружение
2. Внесите изменения в `wp-content/themes/titan/`
3. Проверьте изменения на http://localhost:8080
4. Закоммитьте и запушьте в `main` для автодеплоя

```bash
git add .
git commit -m "Описание изменений"
git push origin main
```
