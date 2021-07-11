<?php

namespace app\controllers;

use app\models\user\search\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'login'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?']
                    ]

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search();

        return $this->render('index', compact('dataProvider'));
    }


    /**
     * @return string
     */
    public function actionLogin(): string
    {
        if (!\Yii::$app->user->isGuest) {
            $this->redirect('index');
        }

        $model = new LoginForm();
        $params = Yii::$app->request->post();
        if ($params !== [] && $model->load($params) && $model->validate()) {
            $password = $model->password;
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $model->password = $hash;

            if (password_verify($password, $hash) && $model->login()) {
                $this->redirect('index');
            }

            $model->addError('username', 'Не правильное имя пользователя или пароль');
        }

        return $this->render('login', compact('model'));
    }

    public function actionLogout(): void
    {
        \Yii::$app->user->logout();

        $this->redirect('login');
    }

}
