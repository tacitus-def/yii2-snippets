### app\widgets\GridView

![Пример виджета GridView](yii2-snippets/blob/raw?file=docs%2Fimages%2Fapp-widgets-GridView.png&commit=4b28452c9b1f34a7f3c73c482d9fa896d25e2e0f)
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
