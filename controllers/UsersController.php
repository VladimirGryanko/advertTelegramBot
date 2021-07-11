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
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
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
            $model->password = password_hash($model->password, PASSWORD_BCRYPT);
            $model->save();

            $auth = \Yii::$app->authManager;
            $userRole = $auth->getRole('user');
            $auth->assign($userRole, $model->getId());

            $this->redirect('/site/index');
        }

        return $this->render('_create', compact('model'));
    }

    public function actionDelete()
    {

    }

}