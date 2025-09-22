# Приложение для обработки XLSX файлов

## Описание

Приложение принимает XLSX файл с указанной в ТЗ структурой, проверяет подпись и обрабатывает данные согласно ТЗ.

Также заложена возможность для расширения функционала:

- Можно легко добавлять пакеты для обработки файлов (либо использовать свои).
- Можно легко добавлять обработчики для других типов файлов (например, CSV).

Приложение работает асинхронно: исходные файлы обрабатываются в джобах, после чего готовые файлы отправляются на указанный в реквесте эндпоинт, а файлы удаляются.

> По ТЗ требуется использовать PostgreSQL, для этого поднят контейнер, но на практике БД не используется, так как для выполнения функций достаточно кэша. В БД могут писаться, например, зафейленные джобы.

Для получения данных карты используется класс-заглушка, который умеет определять тип карты по номеру (банк при этом подставляется как `unknown`).  
Дополнительно реализован альтернативный класс, работающий с API [binlist](https://binlist.net/), но в бесплатной версии доступно лишь несколько запросов в час, поэтому даже для тестов он не подходит.

---

## Запуск для локальной разработки

1. Скопировать `.env.example` в `.env`, указать доступы к БД, порты:

```bash
cp .env.example .env
```
2. Собрать контейнеры:
```bash
   docker compose build

   docker compose up -d
```
3. Установить записимости и выполнить миграции:
```bash
docker compose exec php composer install

docker compose exec php php artisan key:generate

docker compose exec php php artisan migrate
```

## Проверка функционала
1. Сгенерировать серкретный ключ:
```bash
docker compose exec php php artisan key:generate-secret
```
2. Получить валидную подпись:
```bash
docker compose exec php php  artisan key:signature
```
3. Использовать полученные X-Url и X-Signature для отправки запроса через постман или curl
```
<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost:8089/api/cards/process',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('file'=> new CURLFILE('/path-to-file/uploaded.xlsx')),
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Content-Type: multipart/form-data',
    'X-Url: https://back-url.com/webhook/card-handler',
    'X-Signature: k0EvV....'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
echo $response;
```

Если все прошло успешно, в логах будет вывод:
> [2025-09-22 06:10:35] local.DEBUG: App\Support\Clients\BaseClient::post {"method":"https://back-url.com/webhook/card-handler","data":{"message":"Success"}}

Для просмотра файлов нужно закомментировать строки в SendProcessedFileJob.php:
>File::delete($this->path);\
>FileUuidHelper::delete($uuid);
> 
В этом случае исходный и обработанный файлы не будут удаляться.
