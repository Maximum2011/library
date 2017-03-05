<?php

namespace app\controllers\admin;

use app\forms\BookCreateForm;
use app\forms\MoveToCategoryForm;
use app\managers\BookManager;
use Yii;
use app\models\Book;
use app\models\search\BookSearch;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    private $bookManager;

    public function __construct($id, Module $module, BookManager $bookManager, array $config = [])
    {
        $this->bookManager = $bookManager;
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Book models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $moveToCategoryFor = new MoveToCategoryForm();
        if ($moveToCategoryFor->load(Yii::$app->request->post()) && $moveToCategoryFor->validate()) {
            $this->bookManager->moveToCategory($moveToCategoryFor);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'moveToCategoryForm' => $moveToCategoryFor
        ]);
    }

    /**
     * Displays a single Book model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findBook($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new BookCreateForm();
        if ($form->load(Yii::$app->request->post())) {

            $form->previewFile = UploadedFile::getInstance($form, 'previewFile');
            $form->bookFile = UploadedFile::getInstance($form, 'bookFile');

            if ($form->validate() && $book = $this->bookManager->create($form)) {
                Yii::$app->session->setFlash('success', 'Book is created.');
                return $this->redirect(['view', 'id' => $book->id]);
            };
        };
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $book = $this->findBook($id);
        $form = new BookCreateForm($book);

        if ($form->load(Yii::$app->request->post())) {
            $form->previewFile = UploadedFile::getInstance($form, 'previewFile');
            $form->bookFile = UploadedFile::getInstance($form, 'bookFile');

            if ($form->validate()) {
                $book = $this->bookManager->create($form);
                Yii::$app->session->setFlash('success', 'Book is created.');
                return $this->redirect(['view', 'id' => $book->id]);
            };
        };
        return $this->render('update', [
            'model' => $form,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findBook($id)->delete();

        return $this->redirect(['index']);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
