<?php


class ModeratorRule extends \yii\rbac\Rule
{

    public $name = 'isModerator';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params)
    {
        // TODO: Implement execute() method.
    }
}