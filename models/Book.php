<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "book".
 *
 * @property integer $id
 * @property string $name
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $preview_file_id
 * @property string $description
 * @property string $book_file_id
 * @property integer $category_id
 *
 * @property Category $category
 */
class Book extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['name', 'link', 'category_id'], 'required'],
            [['description'], 'string'],
            [['preview_file_id', 'book_file_id', 'category_id'], 'integer'],
            [['name', 'author'], 'string', 'max' => 255],
            [
                ['book_file_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => File::className(),
                'targetAttribute' => ['book_file_id' => 'id']
            ],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['preview_file_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => File::className(),
                'targetAttribute' => ['preview_file_id' => 'id']
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
            'name' => 'Name',
            'author' => 'Author',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'preview_file_id' => 'Preview',
            'book_file_id' => 'File',
            'description' => 'Description',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreviewFile()
    {
        return $this->hasOne(File::className(), ['id' => 'preview_file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookFile()
    {
        return $this->hasOne(File::className(), ['id' => 'book_file_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\BookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\BookQuery(get_called_class());
    }


    /**
     * @param $name string
     * @param Category $category
     * @param File $previewFile
     * @param File $bookFile
     * @param string $description
     * @param string $author
     * @return Book
     */
    public static function create(
        $name,
        Category $category,
        File $previewFile,
        File $bookFile,
        $description = '',
        $author = ''
    ) {
        $book = new self();
        $book->name = $name;
        $book->populateRelation('category', $category);
        $book->populateRelation('previewFile', $previewFile);
        $book->populateRelation('bookFile', $bookFile);
        $book->description = $description;
        $book->author = $author;
        return $book;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $related = $this->getRelatedRecords();
            /** @var Category $category */
            if (isset($related['category']) && $category = $related['category']) {
                $this->category_id = $category->id;
            }
            /** @var File $previewFile */
            if (isset($related['previewFile']) && $previewFile = $related['previewFile']) {
                $this->preview_file_id = $previewFile->id;
            }
            /** @var File $bookFile */
            if (isset($related['bookFile']) && $bookFile = $related['bookFile']) {
                $this->book_file_id = $bookFile->id;
            }
            return true;
        }
        return false;
    }
}
