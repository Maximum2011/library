<?php

namespace app\components;


use app\events\UploadEvent;
use app\models\File;
use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;

class FileStorage extends Component
{
    const EVENT_AFTER_SAVE = 'afterSave';

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var string
     */
    private $baseUrl;


    public function __construct($basePath = '@webroot/uploads', $baseUrl = '@web/uploads', array $config = [])
    {
        $this->basePath = $basePath;

        if ($baseUrl !== null) {
            $this->baseUrl = \Yii::getAlias($baseUrl);
        }
        parent::__construct($config);
    }


    /**
     * @param $uploadedFile \yii\web\UploadedFile
     * @param bool $preserveFileName
     * @return bool|File
     */
    public function save($uploadedFile, $preserveFileName = false)
    {
        if ($preserveFileName === false) {
            $filename = implode('.', [\Yii::$app->security->generateRandomString(), $uploadedFile->getExtension()]);
        } else {
            $filename = $uploadedFile->baseName;
        }

        $path = $this->getUploadPath($filename);

        if ($uploadedFile->saveAs($path)) {
            $file = File::create(
                $filename,
                $uploadedFile->size,
                $uploadedFile->type,
                $path,
                $this->baseUrl
            );
            if ($file->save()) {
                return $file;
            }
        }
        return false;
    }


    /**
     * @param $path string
     * @return bool
     */
    public function delete($path)
    {
        if (is_file($path)) {
            unlink($path);
            return true;
        }
        return false;
    }

    /**
     * @param $files
     */
    public function deleteAll($files)
    {
        foreach ($files as $file) {
            $this->delete($file);
        }

    }

    private function getUploadPath($fileName)
    {
        $path = FileHelper::normalizePath($this->basePath);
        return $fileName ? Yii::getAlias($path) . '/' . $fileName : null;
    }

    /**
     * @param $path
     */
    public function afterSave($file)
    {
        $this->trigger(self::EVENT_AFTER_SAVE, new UploadEvent([
            'file' => $file
        ]));
    }

}