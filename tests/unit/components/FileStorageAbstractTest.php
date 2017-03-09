<?php
namespace yii\web;

/**
 * Mock for the is_uploaded_file() function for web classes.
 * @return boolean
 */
function is_uploaded_file($filename)
{
    return file_exists($filename);
}

/**
 * Mock for the move_uploaded_file() function for web classes.
 * @return boolean
 */
function move_uploaded_file($filename, $destination)
{
    return copy($filename, $destination);
}

namespace tests\components;

use Yii;
use yii\helpers\FileHelper;

abstract class FileStorageAbstractTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $_FILES = [
            'BookCreateForm[previewFile]' => [
                'name' => 'test-image.jpg',
                'type' => 'image/jpeg',
                'size' => 74463,
                'tmp_name' => Yii::getAlias('@tests') . '/files/test-image.jpg',
                'error' => 0,
            ],
            'BookCreateForm[bookFile]' => [
                'name' => 'test-book.txt',
                'type' => 'text/plain',
                'size' => 12,
                'tmp_name' => Yii::getAlias('@tests') . '/files/test-book.txt',
                'error' => 0,
            ],
            'BookCreateForm[otherBookFile]' => [
                'name' => 'test-other-book.txt',
                'type' => 'text/plain',
                'size' => 12,
                'tmp_name' => Yii::getAlias('@tests') . '/files/test-other-book.txt',
                'error' => 0,
            ],

        ];
        FileHelper::createDirectory(Yii::getAlias('@tests/uploads'));

    }

    public function _after()
    {
        FileHelper::removeDirectory(Yii::getAlias('@tests/uploads'));
    }
}