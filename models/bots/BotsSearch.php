<?php


namespace app\models\bots\search;


use app\models\bots\Bots;
use yii\data\ActiveDataProvider;

class BotsSearch extends Bots
{

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = self::find();

        return new ActiveDataProvider([
           'query' => $query,
        ]);
    }
}