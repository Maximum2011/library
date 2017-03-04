<?php

use yii\db\Migration;

class m170302_095605_create_library_tables extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'author' => $this->string(),
            'date_create' => $this->date(),
            'date_update' => $this->date(),
            'preview_file_id' => $this->integer(),
            'description' => $this->text(),
            'book_file_id' => $this->integer(),
            'category_id' => $this->integer()->notNull()->defaultValue(1)
        ], $tableOptions);

        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->notNull()->defaultValue(1),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'path' => $this->string(1024)->notNull(),
            'type' => $this->string(),
            'size' => $this->integer(),
            'name' => $this->string(),
            'date_create' => $this->date(),
        ], $tableOptions);

        $this->insert('category', ['id' => '1', 'name' => 'root']);

        $this->addForeignKey('fk_book_category_id', 'book', 'category_id', 'category', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_category_parent_id', 'category', 'parent_id', 'category', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk_book_preview_id', 'book', 'preview_file_id', 'file', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_book_file_id', 'book', 'book_file_id', 'file', 'id', 'RESTRICT', 'CASCADE');;
    }

    public function down()
    {
        $this->dropForeignKey('fk_book_category_id', 'book');
        $this->dropForeignKey('fk_category_parent_id', 'category');
        $this->dropForeignKey('fk_book_preview_id', 'book');
        $this->dropForeignKey('fk_book_file_id', 'book');

        $this->dropTable('file');
        $this->dropTable('category');
        $this->dropTable('book');
    }

}
