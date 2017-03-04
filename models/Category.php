<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 *
 * @property Book[] $books
 * @property Category $parent
 * @property Category[] $categories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [
                ['parent_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['parent_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\CategoryQuery(get_called_class());
    }
}
