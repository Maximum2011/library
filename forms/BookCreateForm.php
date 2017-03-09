<?php

namespace app\forms;

use app\models\Category;
use app\models\Book;
use app\models\File;
use yii\base\Model;
use yii\web\UploadedFile;

class BookCreateForm extends Model
{
    const SCENARIO_CREATE = 'create';
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $author;
    /**
     * @var string
     */
    public $description;
    /**
     * @var integer
     */
    public $category_id;

    /**
     * @var UploadedFile | File
     */
    public $previewFile;
    /**
     * @var UploadedFile | File
     */
    public $bookFile;

    /**
     * @var integer
     */
    public $bookId;

    /**
     * @var Book
     */
    private $book;

    public function __construct(Book $book = null, array $config = [])
    {
        $this->book = $book;
        parent::__construct($config);
    }

    public function init()
    {
        if ($this->book) {
            $this->bookId = $this->book->id;
            $this->name = $this->book->name;
            $this->description = $this->book->description;
            $this->category_id = $this->book->category->id;
            $this->author = $this->book->author;
            $this->previewFile = $this->book->previewFile;
            $this->bookFile = $this->book->bookFile;
        } else {
            $this->scenario = self::SCENARIO_CREATE;
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id'], 'required'],
            [['previewFile', 'bookFile'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['description'], 'string'],
            [['category_id'], 'integer'],
            [['name', 'author'], 'string', 'max' => 255],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => 'id']
            ],
            [['previewFile'], 'image', 'skipOnEmpty' => true],
            [
                ['bookFile'],
                'file',
                'skipOnEmpty' => true,
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
            'description' => 'Description',
            'category_id' => 'Category',
            'previewFile' => 'Preview file',
            'bookFile' => 'Book file'
        ];
    }
}