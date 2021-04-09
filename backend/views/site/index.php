<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use \common\models\AppleNew;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Apple test';

ActiveForm::begin([
    'id' => 'add-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>

<div class="form-group">
    <div class="col-lg-11">
        <?= Html::input('number', 'cnt', 1) ?>
        <?= Html::submitButton('Генерация', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end();

$dataProvider = new ActiveDataProvider([
    'query' => AppleNew::find(),
    'pagination' => [
        'pageSize' => 20,
    ],
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'color',
            'label' => 'Цвет',
        ],
        [
            'attribute' => 'state',
            'label' => 'Статус',
            'value' => function ($data) {
                return AppleNew::STATES[$data->state];
            },
        ],
        [
            'attribute' => 'date_appearance',
            'label' => 'Дата появления',
            'format' => ['datetime']
        ],
        [
            'attribute' => 'date_fall',
            'label' => 'Дата падения',
            'value' => function ($data) {
                return empty($data->date_fall) ? '-' : date('Y-m-d H:i:s', $data->date_fall);
            },
        ],
        [
            'attribute' => 'size',
            'label' => 'Сколько не съедено',
            'value' => function ($data) {
                return ceil(($data->size) * 100) . ' %';
            },
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => 'Съесть',
            'headerOptions' => [ 'width' => '120' ],
            'content' => function ($data) {
                ob_start();
                    ActiveForm::begin([
                        'id' => 'eat-form' . $data->id,
                        'options' => ['class' => 'form-horizontal'],
                        'action' => '/site/eat?id=' . $data->id
                    ]) ?>
                    <?= Html::input('number', 'cnt', 1,
                        [
                            'style' => 'width: 50px',
                            'max' => ceil(($data->size) * 100),
                            'min' => 1
                        ]) ?>
                    <?= Html::submitButton('+', [ 'class' => 'btn-xs btn-primary' ]) ?>
                    <?php ActiveForm::end();
                $out = ob_get_contents();
                ob_end_clean();

                return $out;
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => 'Уронить',
            'headerOptions' => ['width' => '60'],
            'content' => function ($data) {
                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-arrow-down"]);
                return Html::a($icon, Url::to(['site/down', 'id' => $data->id]), ['data-method' => 'POST']);
            }
        ],
    ],
]);
?>

