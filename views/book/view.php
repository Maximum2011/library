<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'author',
            'description:ntext',
            'category.name',
            [
                'attribute' => 'previewFile',
                'value' => $model->previewFile->url,
                'format' => ['image', ['width' => '300px']],
            ],
            [
                'attribute' => 'bookFile',
                'format' => 'raw',
                'value' => Html::a($model->bookFile->name, $model->bookFile->url),
            ],
        ],
    ]) ?>

</div>
