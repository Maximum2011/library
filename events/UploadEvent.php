<?php
namespace app\events;

use app\models\File;
use yii\base\Event;

/**
 * Class UploadEvent
 */
class UploadEvent extends Event
{

    /**
     * @var File
     */
    public $file;
}
