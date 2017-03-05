<?php

use app\models\Category;
use yii\grid\CheckboxColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search */
/* @var $moveToCategoryForm app\forms\MoveToCategoryForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'id' => 'grid_book',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => CheckboxColumn::className()],
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'author',
            'date_create:datetime',
            'date_update:datetime',
            'description:ntext',
            'category.name',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'style' => 'width: 100px; text-align:center;',
                ],
            ],
        ],
    ]); ?>
    <div class="pull-left">
        <?php $form = ActiveForm::begin([
            'id' => 'move_to_category_form',
            'action' => ['index'],
            'method' => 'post',
            'options' => ['class' => 'form-inline']
        ]); ?>
        <?= $form->field($moveToCategoryForm, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(),
            'id', 'name'), ['prompt' => 'Select category']) ?>
        <?= $form->field($moveToCategoryForm, 'selection')->hiddenInput(['id' => 'selection_field'])->label(false) ?>
        <?= Html::submitButton('OK', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php

$js = <<<JS
$('#move_to_category_form').on('beforeSubmit', function (e) {
    var keys = $("#grid_book").yiiGridView('getSelectedRows');
    var value = keys.toString();
    if (!value) return false;
    $("#selection_field").val(value);
    return true;
});
JS;
$this->registerJs($js);
