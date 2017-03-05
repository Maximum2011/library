<?php

namespace app\forms;

use app\models\Category;
use yii\base\Model;

class MoveToCategoryForm extends Model
{
    /**
     * @var string
     */
    public $selection;

    /**
     * @var integer
     */
    public $category_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'required'],
            [['selection'], 'string'],
            [['category_id'], 'integer'],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Selection move to category',
        ];
    }

    public function getBookIds()
    {
        return explode(',', $this->selection);
    }

}