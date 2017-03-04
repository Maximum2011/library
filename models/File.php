<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property string $path
 * @property string $type
 * @property integer $size
 * @property string $name
 * @property integer $created_at
 *
 * @property Book[] $books
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
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
                'updatedAtAttribute' => false,
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
            [['path'], 'required'],
            [['size'], 'integer'],
            [['path'], 'string', 'max' => 1024],
            [['type', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'type' => 'Type',
            'size' => 'Size',
            'name' => 'Name',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * @param $name string
     * @param $size string
     * @param $type string
     * @param $path string
     * @param $extension string
     * @return File
     */
    public static function create($name, $size, $type, $path)
    {
        $file = new self();
        $file->name = $name;
        $file->size = $size;
        $file->type = $type;
        $file->path = $path;
        return $file;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        if (!$this->size) {
            $this->size = filesize($this->path);
        }
        return $this->size;
    }

}
