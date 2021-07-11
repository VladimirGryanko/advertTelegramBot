<?php


namespace app\controllers;


use app\models\bots\search\BotsSearch;

class BotsController extends \yii\web\Controller
{

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new BotsSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('index', compact('dataProvider'));
    }

}