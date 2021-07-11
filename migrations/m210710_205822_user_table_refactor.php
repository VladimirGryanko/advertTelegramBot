<?php

use app\models\bots\Bots;
use yii\db\Migration;

/**
 * Class m210710_205822_user_table_refactor
 */
class m210710_205822_user_table_refactor extends Migration
{
    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\User::tableName(), 'email', $this->string());
        $this->createTable(Bots::tableName(), [
            'id' => $this->primaryKey(11),
            'path' => $this->string(),
            'token' => $this->string(),
            'title' => $this->string(),
            'webhooks_is_set' => $this->boolean(),
        ]);

    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
       $this->dropTable(Bots::tableName());
       $this->dropColumn(\app\models\User::tableName(), 'email');

       return true;
    }
}
