### app\widgets\GridView

![Пример виджета GridView](docs/images/app-widgets-GridView.png)

Добавлена возможность создавать многострочные заголовки. За это отвечает 
атрибут `headers`. Каждый элемент массива атрибута `headers` имеет такую же
конфигурацию, как массив атрибута `columns`, за исключением нового параметра `column`,
ссылающегося на элемент указанного выше атрибута виджета.

Пример:

```
$tableHeaders = [
        [
            ['column' => 0, 'headerOptions' => ['rowspan' => 4, 'class' => 'text-center vertical-middle']],
            ['column' => 1, 'headerOptions' => ['rowspan' => 4, 'class' => 'text-center vertical-middle']],
            ['label' => '', 'headerOptions' => ['colspan' => 2, 'rowspan' => 2, 'class' => 'text-center vertical-middle']],
        ],
        [
        ],
        [
            ['column' => 2, 'headerOptions' => ['class' => 'text-center']],
            ['column' => 3, 'headerOptions' => ['class' => 'text-center']],
        ],
        [
            ['label' => '', 'headerOptions' => []],
            ['label' => $model->discount_buy . '%', 'headerOptions' => ['class' => 'text-center']],
        ]
    ];

// …

GridView::widget([
    'headers' => $tableHeaders,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $tableColumns,
]);
```

### app\helpers\JsHelper

Хелпер для работы с js-кодом
* `JsHelper::setVars($view, $vars)`

  Регистрирует в вьюхе `$view` js-скрипт с созданием переменных в `window.jsVars`, переданных в `$vars`.

### app\components\JsExpression

Компонент выводит javascript-код с подстановкой именованных биндингов

* `JsExpr::__construct(string $jsCode, array $bindings = [], array $config = [])`

