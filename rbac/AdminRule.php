<?php


class AdminRule extends \yii\rbac\Rule
{
    public $name = 'isAdmin';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params)
    {
        // TODO: Implement execute() method.
    }
}