<?php

namespace console\migrations;

use yii\db\Migration;

class m170301_051438_seotag extends Migration
{
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function safeUp()
    {
        $this->createTable('{{%seotag}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
            'small_pict' => $this->string(),
            'large_pict' => $this->string(),
            'description' => $this->text(),
            'full_url' => $this->string(),
            'big_image_url' => $this->string(),
            'small_image_url' => $this->string()
        ], $this->tableOptions);

        $this->createTable('{{%seotag_keywords}}', [
            'id' => $this->primaryKey(),
            'word' => $this->string()
        ], $this->tableOptions);

        $this->createTable('{{%tag_to_keyword}}', [
            'id' => $this->primaryKey(),
            'tag_id' => $this->integer(),
            'word_id' => $this->integer(),
        ], $this->tableOptions);

        $this->addForeignKey('tag_to_word',
            '{{%tag_to_keyword}}', 'tag_id',
            '{{%seotag}}', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('word_to_tag',
            '{{%tag_to_keyword}}', 'word_id',
            '{{%seotag_keywords}}', 'id',
            'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('tag_to_word', '{{%tag_to_keyword}}');
        $this->dropForeignKey('word_to_tag', '{{%tag_to_keyword}}');
        $this->dropTable('{{%tag_to_keyword}}');
        $this->dropTable('{{%seotag}}');
        $this->dropTable('{{%seotag_keywords}}');
    }
}
