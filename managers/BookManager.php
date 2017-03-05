<?php

namespace app\managers;


use app\components\FileStorage;
use app\forms\BookCreateForm;
use app\forms\MoveToCategoryForm;
use app\models\Book;
use app\models\Category;
use yii\web\NotFoundHttpException;

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
            $this->findCategory($form->category_id),
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

    /**
     * @param MoveToCategoryForm $form
     * @return bool
     */
    public function moveToCategory(MoveToCategoryForm $form)
    {
        $category = $this->findCategory($form->category_id);
        $result = true;
        foreach ($form->getBookIds() as $id) {
            $book = $this->findBook($id);
            $book->assignTo($category);
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