
Инструкция по развертыванию
=============================================
**1**.Склонировать образ проекта с репозитория к себе на ПК:
>git clone "ссылка с репозитория"

**2**.Установить Composer и установить сами пакеты из текущего каталога:
>composer install

**3**.Установить базу данных MySQL. Править конфигурационный файл можно в config/packages/doctrine.yaml,
где:

`driver:` - 'ваш драйвер' (MySQL или другой),

`server_version` - версия MySQL.

**4**.Создать локальную переменную среду:
>composer dump-env dev

**5**.В созданном файле .env.local.php, в строке DATABASE_URL, указать параметры MySQL, где:

`db_user` - имя локального пользователя (например: root),

`db_password` - пароль пользователя,

`db_name` - имя базы данных.

**6**.Создайте базу данных с помощью команды:
>php bin/console doctrine:database:create

>php bin/console doctrine:schema:update --force

**7**.Запустить сервер:
>php bin/console server:run

Инструкция по тестированию
=============================================

**1**.Установить Postman для тестирования.

**2.**.Для вывода записей обращаться с GET:
>http://127.0.0.1:8000/api/places