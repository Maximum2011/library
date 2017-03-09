<?php
namespace tests\managers;

use app\components\FileStorage;
use app\components\TransactionService;
use app\forms\BookCreateForm;
use app\managers\BookManager;
use app\models\Category;
use app\models\File;
use tests\components\FileStorageAbstractTest;
use yii\web\UploadedFile;
use app\models\Book;

class BookManagerTest extends FileStorageAbstractTest
{
    /**
     * @var BookManager
     */
    protected $bookManager;

    public function _before()
    {
        $this->bookManager = new BookManager(new FileStorage('@tests/uploads'), new TransactionService());
        parent::_before();
    }

    public function testCreateBook()
    {
        $book = $this->createBook();

        $this->assertTrue($book instanceof Book);
        $this->assertEquals($book->name, 'First book');
        $this->assertEquals($book->author, 'Author of first book');
        $this->assertEquals($book->description, 'Description of first book');
        $this->assertTrue($book->category instanceof Category);
        $this->assertTrue($book->previewFile instanceof File);
        $this->assertTrue($book->bookFile instanceof File);
        $this->assertEquals($book->bookFile->name, 'test-book');
    }

    public function testUpdateBook()
    {
        $category = Category::findOne(1);
        $book = $this->createBook();
        $book = $this->bookManager->create(
            'Other book',
            $category->id,
            'Description of other book',
            'Author of other book',
            null,
            UploadedFile::getInstanceByName('BookCreateForm[otherBookFile]'),
            $book
        );

        $this->assertEquals($book->name, 'Other book');
        $this->assertEquals($book->author, 'Author of other book');
        $this->assertEquals($book->description, 'Description of other book');
        $this->assertEquals($book->bookFile->name, 'test-other-book');
        $this->assertEquals($book->previewFile->name, 'test-image');
    }

    /**
     * @return Book
     */
    protected function createBook()
    {
        $category = Category::findOne(1);
        $book = $this->bookManager->create(
            'First book',
            $category->id,
            'Description of first book',
            'Author of first book',
            UploadedFile::getInstanceByName('BookCreateForm[previewFile]'),
            UploadedFile::getInstanceByName('BookCreateForm[bookFile]')
        );
        return $book;
    }
}
