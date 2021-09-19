# Laravel Daily - Listings

Приложение для поиска и добавления товаров. Пользователи могут регистрироваться, искать предложения других пользователей и публиковать собственные.
Подробности в [документации](docs/README.md).

### Установка

Для запуска приложения требуется **Docker** и **Docker Compose**.

Для инициализации приложения выполнить команду:
```
make init
```

### Управление

Запуск:
```
make up
```

Остановка приложения:

```
make down
```

### Интерфейсы

Приложение - http://localhost:8080

Почта (MailHog) - http://localhost:8025

### Тесты

```
make backend-test
```

### Цель проекта

Код написан в образовательных целях в рамках курса [Laravel Jetstream+Livewire: Real Mini-Project](https://laraveldaily.teachable.com/p/laravel-jetstream-livewire-project).
