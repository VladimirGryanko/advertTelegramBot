<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m210710_232240_add_users_role_field
 */
class m210710_232240_add_users_role_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(User::tableName(), 'role', $this->smallInteger()->after('email')->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(User::tableName(), 'role');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210710_232240_add_users_role_field cannot be reverted.\n";

        return false;
    }
    */
}
