<?php

use app\forms\BookCreateForm;
use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\forms\BookCreateForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <div class="row">
            <?php if ($model->scenario !== BookCreateForm::SCENARIO_CREATE): ?>
                <div class="col-lg-6">
                    <?= Html::img($model->previewFile->url, ['class' => 'img-thumbnail']) ?>
                </div>
            <?php endif; ?>
            <div class="col-lg-6">
                <?= $form->field($model,
                    'previewFile')->fileInput(['accept' => 'image/*'])->label('Upload preview image') ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <?php if ($model->scenario !== BookCreateForm::SCENARIO_CREATE): ?>
                <div class="col-lg-6">
                    <?= Html::a($model->bookFile->name, $model->bookFile->url, ['class' => 'btn btn-default btn-lg']) ?>
                </div>
            <?php endif; ?>
            <div class="col-lg-6">
                <?= $form->field($model, 'bookFile')->fileInput()->label('Upload book file') ?>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->scenario == BookCreateForm::SCENARIO_CREATE ? 'Create' : 'Update',
            ['class' => $model->scenario == BookCreateForm::SCENARIO_CREATE ? 'btn btn-primary' : 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
