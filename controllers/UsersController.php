<?php


namespace app\controllers;


use app\models\User;
use app\models\user\search\UserSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;

class UsersController extends \yii\web\Controller
{
    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [User::ROLE_USER],
                    ],
                    [
                        'actions' => ['create', 'delete', 'update'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                    [
                        'actions' => ['create', 'delete', 'update'],
                        'allow' => false,
                        'roles' => [User::ROLE_USER],
                        'denyCallback' => function ($rule, $action) {
                            return $action->controller->redirect('/users/index');
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $params = \Yii::$app->request->get();

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($params);

        return $this->render('index', compact('dataProvider'));
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionCreate(): string
    {
        $model = new User();
        $request = \Yii::$app->request;

        if ($request->isPost && $model->load($request->post()) && $model->validate()) {
            $model->authKey = hash('sha256', random_bytes(5));
            $model->accessToken = hash('sha256', random_bytes(5));
            $model->password = password_hash($model->password, PASSWORD_ARGON2I);
            $model->save();

            $auth = \Yii::$app->authManager;
            $userRole = $auth->getRole('user');
            $auth->assign($userRole, $model->getId());

            $this->redirect('/users/index');
        }

        return $this->render('_create', compact('model'));
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id): \yii\web\Response
    {
        $user = User::findOne($id);
        $user->delete();

        return $this->redirect('/users/index');
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionUpdate(int $id): string
    {
        $params = \Yii::$app->request->post();
        $user = User::findOne($id);

        if ($user->load($params) && $user->validate()) {
            $user->password = password_hash($user->password, PASSWORD_ARGON2I);
            $user->save();
        }
        return $this->render('_reset_password', compact('user'));
    }

}