<?php

namespace tests\components;

use app\components\FileStorage;
use app\models\File;
use Yii;
use yii\web\UploadedFile;

class FileStorageTest extends FileStorageAbstractTest
{
    /**
     * @var FileStorage
     */
    protected $fileStorage;

    public function _before()
    {
        $this->fileStorage = new fileStorage('@tests/uploads');
        parent::_before();
    }

    public function testGetFileInstance()
    {
        $preview_file = UploadedFile::getInstanceByName('BookCreateForm[bookFile]');
        $this->assertTrue($preview_file instanceof UploadedFile);
    }

    public function testSaveFile()
    {

        $file = $this->fileStorage->save(UploadedFile::getInstanceByName('BookCreateForm[bookFile]'));
        $this->assertTrue($file instanceof File);
        $this->assertTrue(file_exists($file->path));
    }

    public function testDeleteFile()
    {
        $file = $this->fileStorage->save(UploadedFile::getInstanceByName('BookCreateForm[previewFile]'));
        $this->assertTrue($this->fileStorage->delete($file->path));
        $this->assertFalse(file_exists($file->path));
    }
}