<?php

use yii\db\Migration;

/**
 * Handles adding url to table `file`.
 */
class m170304_142140_add_url_column_to_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('file', 'base_url', $this->string(1024));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('file', 'base_url');
    }
}
