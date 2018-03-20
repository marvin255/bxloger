# Bxlogger

[![Latest Stable Version](https://poser.pugx.org/marvin255/bxlogger/v/stable.png)](https://packagist.org/packages/marvin255/bxlogger)
[![License](https://poser.pugx.org/marvin255/bxlogger/license.svg)](https://packagist.org/packages/marvin255/bxlogger)
[![Build Status](https://travis-ci.org/marvin255/bxlogger.svg?branch=master)](https://travis-ci.org/marvin255/bxlogger)

PSR-3 совместимый логер для 1С-Битрикс "Управление сайтом".



## Оглавление

* [Установка](#Установка).
* [Использование](#Использование).
* [Замена транспорта](#Замена-логера).


## Установка

**С помощью [Composer](https://getcomposer.org/doc/00-intro.md)**

1. Добавьте в ваш composer.json в раздел `require`:

    ```javascript
    "require": {
        "marvin255/bxlogger": "~1.0"
    }
    ```

2. Если требуется автоматическое обновление модуля через composer, то добавьте в раздел `scripts`:

    ```javascript
    "scripts": {
        "post-install-cmd": [
            "\\marvin255\\bxlogger\\installer\\Composer::injectModule"
        ],
        "post-update-cmd": [
            "\\marvin255\\bxlogger\\installer\\Composer::injectModule"
        ]
    }
    ```

3. Выполните в консоли внутри вашего проекта:

    ```
    composer update
    ```

4. Если пункт 2 не выполнен, то скопируйте папку `vendor/marvin255/bxlogger/marvin255.bxlogger` в папку `local/modules` вашего проекта. А папку `vendor/phpmailer/phpmailer` в папку `local/modules/marvin255.bxlogger/phpmailer`.

5. Установите модуль в административном разделе 1С-Битрикс "Управление сайтом".

**Обычная**

1. Скачайте архив с репозиторием.
2. Скопируйте папку `marvin255.bxlogger` в папку `local/modules` вашего проекта. А папку `/vendor/psr/log/Psr/Log` в папку `local/modules/marvin255.bxlogger/psr_log`.
3. Установите модуль в административном разделе 1С-Битрикс "Управление сайтом".



## Использование

Внутри приложения объект лога доступен через контейнер [`marvin255\bxlogger\Log`](https://github.com/marvin255/bxlogger/blob/master/marvin255.bxlogger/lib/Log.php):

```php
use Bitrix\Main\Loader;
use marvin255\bxlogger\Log;

Loader::includeModule('marvin255.bxlogger');

Log::get()->error('Error occured', ['context' => 'some context variable']);
```



## Замена логера

По умолчанию используется запись в лог с помощью `CEventLog`. Для того, чтобы заменить `CEventLog` на любой другой логер можно использовать событие:

```php
use Bitrix\Main\EventManager;
use Bitrix\Main\Event;

EventManager::getInstance()->addEventHandler('marvin255.bxlogger', 'createLogger', 'createLoggerHandler');
function createLoggerHandler(Event $event)
{
    $event->setParameter('logger', new MyAwesomeLogger);
}
```

Для того, чтобы все заработало, класс `MyAwesomeLogger` должен реализовывать интерфейс [`\Psr\Log\LoggerInterface`](https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php).
