# Инструкция по настройке деплоя

## 1. Добавление SSH-ключа на сервер

Подключитесь к серверу:
```bash
ssh abrobe14_titan@titan.realeasystudio.site
```

Пароль: `BobrKurwa228!`

После подключения выполните следующие команды:

```bash
# Создайте директорию для SSH-ключей
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Добавьте публичный ключ в authorized_keys
echo "ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIG7q80zfw3hU2cwV+FKIMGa2SnAuQ2ds+oHTRILc8ZIF github-actions-deploy" >> ~/.ssh/authorized_keys

# Установите правильные права доступа
chmod 600 ~/.ssh/authorized_keys
```

## 2. Определение пути к WordPress теме

Находясь на сервере, выполните:

```bash
# Найдите путь к WordPress
find /home -name "wp-content" -type d 2>/dev/null

# Или проверьте типичные пути
ls -la ~/public_html/wp-content/themes/
# или
ls -la ~/www/wp-content/themes/
```

Запишите полный путь к директории `wp-content/themes/titan` (если она уже существует) или к `wp-content/themes/` (если нужно создать папку titan).

## 3. Настройка GitHub Secrets

Перейдите в ваш GitHub репозиторий:
```
Settings → Secrets and variables → Actions → New repository secret
```

Добавьте следующие secrets:

### DEPLOY_SSH_KEY
Содержимое файла `deploy_key` (приватный ключ):
```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACBu6vNM38N4VNnMFfhSiDBmtkpwLkNnbPqB00SC3PGSBQAAAJgwSlfOMEpX
zgAAAAtzc2gtZWQyNTUxOQAAACBu6vNM38N4VNnMFfhSiDBmtkpwLkNnbPqB00SC3PGSBQ
AAAEAavTMq5uUO/CITQ56IlPNgkYyDgN2QOnVn7hxMbq5bWW7q80zfw3hU2cwV+FKIMGa2
SnAuQ2ds+oHTRILc8ZIFAAAAFWdpdGh1Yi1hY3Rpb25zLWRlcGxveQ==
-----END OPENSSH PRIVATE KEY-----
```

### DEPLOY_HOST
```
titan.realeasystudio.site
```

### DEPLOY_USER
```
abrobe14_titan
```

### DEPLOY_PATH
```
/home/abrobe14_titan/public_html/wp-content/themes/titan
```
⚠️ **ВАЖНО**: Путь может отличаться! Используйте путь, который вы определили на шаге 2.

## 4. Проверка GitHub репозитория

Убедитесь, что workflow файл находится в правильной директории:
```
.github/workflows/deploy.yml
```

В вашем проекте он находится в `wp-content/.github/workflows/deploy.yml`, что неправильно.

Нужно переместить его в корень репозитория.

## 5. Тестирование

После настройки всех secrets:

1. Сделайте коммит в ветку `master`
2. Push в GitHub
3. Проверьте выполнение workflow в разделе Actions

## Безопасность

⚠️ **ВАЖНО**:
- Удалите файлы `deploy_key` и `deploy_key.pub` из локального репозитория после настройки
- Добавьте их в `.gitignore`, чтобы случайно не закоммитить
- Никогда не коммитьте приватные ключи в репозиторий!
