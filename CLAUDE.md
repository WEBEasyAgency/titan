# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Корпоративная WordPress-тема с WooCommerce для компании Titan Project (электроника). Русскоязычный сайт с валютой RUB.

Проект состоит из двух частей:
- **`layout/`** — статичная HTML/CSS/JS вёрстка (Gulp + BrowserSync)
- **`wp-content/themes/titan/`** — WordPress-тема, использующая скомпилированные ассеты из layout

## Commands

### Layout (статичная вёрстка)
```bash
cd layout && npm install         # установка зависимостей
cd layout && npx gulp             # dev-режим: watch + BrowserSync на localhost:3000
cd layout && npm run build        # production-сборка в layout/dist/
```

### WordPress (Docker)
```bash
docker-compose up -d              # запуск WordPress (localhost:8080) + MySQL
docker-compose down               # остановка
```

### Deployment
Push в `main` автоматически деплоит через GitHub Actions (rsync на сервер).

## Architecture

### Двойная структура
Вёрстка создаётся в `layout/app/` (SCSS, JS, HTML-шаблоны с `@@include`), компилируется Gulp'ом в `layout/dist/`. Готовые файлы (`libs.min.css`, `app.min.css`, `libs.min.js`, `app.min.js`) вручную копируются в `wp-content/themes/titan/assets/`.

### WordPress-тема
- Классическая тема (не блочная), PHP 7.4+, WordPress 6.0+
- Шаблоны страниц: `front-page.php`, `page-production.php`, `page-contacts.php`
- Переиспользуемые части: `template-parts/` (burger-menu, popups, cookies, cf7-scripts, wc-scripts)
- WooCommerce-шаблоны переопределены в `woocommerce/` (archive-product, single-product, cart, myaccount)

### WooCommerce-интеграция
Тема **полностью переопределяет** стандартный WooCommerce:
- Все дефолтные хуки удалены в `functions.php` (секция 10)
- Все дефолтные стили отключены
- Галерея, zoom, lightbox отключены
- Кастомные шаблоны с чистой разметкой
- AJAX для корзины, поиска, добавления товаров (секции 12-14)
- Классические cart/checkout (не блочные) принудительно

### Frontend
- jQuery 3.7.1 + Swiper + Inputmask + Fancybox — бандлятся в `libs.min.js`
- Кастомный JS в `app.js` → `app.min.js`
- SCSS: `main.scss` + `blocks.scss` → `app.min.css`

## Conventions

### PHP
- Все функции темы имеют префикс `titan_`
- AJAX actions: `titan_ajax_product_search`, `titan_ajax_update_cart`, `titan_ajax_add_to_cart`
- Nonce: `titan_wc_nonce`
- `functions.php` разделён на пронумерованные секции (1-19)
- WordPress coding standards: пробелы внутри скобок `( $var )`, экранирование вывода через `esc_html()`, `esc_url()`, `esc_attr()`

### CSS
- BEM-подобные классы: `.header__search`, `.catalog-table`, `.t-row`
- CSS custom properties для цветов

### JS-объект для WooCommerce
Глобальный объект `titan_wc` (через `wp_localize_script`): `ajax_url`, `nonce`, `cart_url`.

### Навигация
Тема регистрирует три меню: `primary` (шапка), `footer` (подвал), `mobile` (бургер). Используется кастомный walker `Titan_Nav_Walker` для чистого HTML.
