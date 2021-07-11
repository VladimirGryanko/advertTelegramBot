<?php


namespace app\commands;


class RbacController extends \yii\console\Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;

        $user = $auth->createRole('user');
        $auth->add($user);


        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);

        // добавляем роль "admin" и даём роли разрешение "updatePost"
        // а также все разрешения роли "author"
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели User.
        $auth->assign($admin, 2);
        $auth->assign($admin, 1);

        $auth->addChild($moderator, $user);
        $auth->addChild($admin, $moderator);
        $auth->addChild($admin, $user);
    }
}