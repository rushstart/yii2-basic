<?php

use yii\db\Migration;

/**
 * Class m200206_122621_create_table_vocabulary
 */
class m200206_122621_create_table_vocabulary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vocabulary}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text(),
        ]);

        $this->createTable('{{%vocabulary_item}}', [
            'id' => $this->primaryKey(),
            'vocabulary_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'parent_id' => $this->integer(),
            'depth' => $this->tinyInteger()->defaultValue(0)->notNull(),
            'weight' => $this->integer()->defaultValue(0)->notNull(),
            'status' => $this->tinyInteger()->defaultValue(10)->notNull(),
        ]);

        $this->createIndex('name', '{{%vocabulary}}', 'name');
        $this->createIndex('vocabulary_id-name', '{{%vocabulary_item}}', ['vocabulary_id', 'name'],true);

        $this->addForeignKey('fk_vocabulary_item-vocabulary_id', '{{%vocabulary_item}}', 'vocabulary_id', '{{%vocabulary}}', 'id');
        $this->addForeignKey('fk_vocabulary_item-parent_id', '{{%vocabulary_item}}', 'parent_id', '{{%vocabulary_item}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_vocabulary_item-vocabulary_id', '{{%vocabulary_item}}');
        $this->dropForeignKey('fk_vocabulary_item-parent_id', '{{%vocabulary_item}}');
        $this->dropTable('{{%vocabulary}}');
        $this->dropTable('{{%vocabulary_item}}');
    }

}
