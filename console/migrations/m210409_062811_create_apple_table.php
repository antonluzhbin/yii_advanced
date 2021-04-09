<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m210409_062811_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string(32)->notNull()->defaultValue(''),
            'state' => $this->smallInteger()->notNull()->defaultValue(0),
            'date_appearance' => $this->integer()->notNull()->defaultValue(0),
            'date_fall' => $this->integer()->notNull()->defaultValue(0),
            'size' => $this->float()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
