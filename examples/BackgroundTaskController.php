<?php

namespace app\controllers;


// ...


/**
 * Description of ImportController
 *
 * @author demiurg
 */
class BaskgroundTaskController extends \yii\web\Controller {

    // ...

    public function actionIndex($id) {

        // ...
        
        $data = [

            // ...
        
        ];
        // Подписываемся на соответствующее событие для запуска задачи после отправки
        // всех данных клиенту
        \Yii::$app->response->on(\yii\web\Response::EVENT_AFTER_SEND, [$this, 'afterSend'], $data);

        // ...

    }

    // ...

    public function afterSend(\yii\base\Event $event) {
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
