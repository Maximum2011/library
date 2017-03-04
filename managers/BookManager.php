<?php

namespace app\managers;


use app\components\FileStorage;
use app\forms\BookCreateForm;
use app\models\Book;
use app\models\Category;

class BookManager
{
    private $fileStorage;

    public function __construct(FileStorage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    /**
     * @param BookCreateForm $form
     */
    public function create(BookCreateForm $form)
    {
        $book = Book::create(
            $form->name,
            Category::findOne($form->category_id),
            $this->fileStorage->save($form->previewFile),
            $this->fileStorage->save($form->bookFile),
            $form->description,
            $form->author
        );
        if ($book->save(false)) {
            return $book;
        }
        return false;
    }

}