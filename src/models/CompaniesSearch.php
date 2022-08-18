<?php

namespace diginova\mhsb\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\conditions\LikeCondition;

/**
 * This is the model class for table "companies".
 *
 * @property int $id
 * @property string $name
 * @property string $domain
 * @property string $email
 */
class CompaniesSearch extends Companies
{
    //public $definitionType;
    public $companiesGroup;
    public $typesGroup;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'domain', 'email', 'type_id', 'group', 'status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return parent::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Companies::find()->joinWith('type');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'def_id' => $this->def_id,
            'type' => $this->type
        ]);


        return $dataProvider;
    }
}
