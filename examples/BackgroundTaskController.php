<?php

namespace app\controllers;


// ...


/**
 * Description of ImportController
 *
 * @author demiurg
 */
class BaskgroundTaskController extends Controller {

    // ...

    public function actionIndex($id) {

        // ...

        // Подписываемся на соответствующее событие для запуска задачи после отправки
        // всех данных клиенту
        \Yii::$app->response->on(Response::EVENT_AFTER_SEND, [$this, 'afterSend'], $form);

        // ...

    }

    // ...

    public function afterSend(Event $event) {
        try {
            // Закрываем сессию
            session_write_close();
            // Делаем бессрочным длительность выполнения текущего скрипта
            set_time_limit(0);
            // Игнорируем закрытие соединения клиента
            ignore_user_abort(true);
            // Очищаем буфер
            flush();
            // Сбрасывает все запрошенные данные клиенту (из документации)
            fastcgi_finish_request();
            
            // Ниже код задачи, которая должна исполняться в фоне
            // ...
        }
        catch(\Exception $e) {

            // ...

        }
        finally {

            // ...

        }
    }

    // ...

}
