<?php

namespace app\managers;


use app\components\FileStorage;
use app\models\Book;
use app\models\Category;
use app\components\TransactionService;
use app\models\File;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class BookManager
{
    private $fileStorage;

    private $transactionService;

    public function __construct(FileStorage $fileStorage, TransactionService $transactionService)
    {
        $this->fileStorage = $fileStorage;
        $this->transactionService = $transactionService;
    }

    /**
     * @param $name
     * @param $category_id integer
     * @param $description string
     * @param $author string
     * @param $previewFile UploadedFile
     * @param $bookFile UploadedFile
     * @param Book $book
     * @return Book
     */
    public function create(
        $name,
        $category_id,
        $description,
        $author,
        UploadedFile $previewFile = null,
        UploadedFile $bookFile = null,
        $book = null
    )
    {
        if ($book == null) {
            $book = Book::create(
                $name,
                $this->findCategory($category_id),
                $description,
                $author
            );
        } else {
            $book->name = $name;
            $book->description = $description;
            $book->author = $author;
            $book->assignCategory(Category::findOne($category_id));
        }
        $this->transactionService->execute(function () use ($book, $previewFile, $bookFile) {
            if ($previewFile !== null) {
                $previewFile = $this->fileStorage->save($previewFile, true);
                $book->assignPreviewFile($previewFile);
            }
            if ($bookFile !== null) {
                $bookFile = $this->fileStorage->save($bookFile, true);
                $book->assignBookFile($bookFile);
            }
            $book->save(false);
        });
        return $book;
    }


    /**
     * @param $bookIds array
     * @param $categoryId integer
     * @return bool
     */
    public function moveToCategory(array $bookIds, $categoryId)
    {
        $category = $this->findCategory($categoryId);
        $result = true;
        foreach ($bookIds as $id) {
            $book = $this->findBook($id);
            $book->assignCategory($category);
            $result = $book->save() && $result;
        }
        return $result;
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCategory($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested category does not exist.');
        }
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findBook($id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested book does not exist.');
        }
    }

}