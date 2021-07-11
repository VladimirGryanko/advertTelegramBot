<?php

namespace app\models\user\search;

use app\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'email'], 'string'],
            [['email'], 'email'],
            ['id', 'integer'],
        ];
    }

    /**
     * @param ?array $params
     * @return ActiveDataProvider
     */
    public function search(array $params = null): ActiveDataProvider
    {
        $query = self::find();

        if ($params !== null && $this->load($params) && $this->validate()) {
            $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['id' => $this->id])
                ->andFilterWhere(['like', 'email', $this->email]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
    }

}