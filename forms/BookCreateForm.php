<?php

namespace app\forms;

use app\models\Category;
use app\models\Book;
use yii\base\Model;
use yii\web\UploadedFile;

class BookCreateForm extends Model
{
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
     * @var bool
     */
    public $isCreateForm = true;
    /**
     * @var UploadedFile
     */
    public $previewFile;
    /**
     * @var UploadedFile
     */
    public $bookFile;

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
            $this->name = $this->book->name;
            $this->description = $this->book->description;
            $this->category_id = $this->book->category->id;
            $this->author = $this->book->author;
            $this->isCreateForm = false;
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id', 'previewFile', 'bookFile'], 'required'],
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
            [['previewFile'], 'image', 'skipOnEmpty' => false],
            [
                ['bookFile'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => ['doc', 'docx', 'pdf', 'mobi', 'epub', 'rtf']
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