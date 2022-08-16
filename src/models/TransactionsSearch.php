<?php

namespace diginova\mhsb\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\conditions\LikeCondition;

/**
 * This is the model class for table "documents".
 *
 * @property int $id
 * @property string $company_id
 * @property string $category_id
 * @property string $type_id
 */
class TransactionsSearch extends Transactions
{
    public $definitionType;
    public $companyType;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id','amount'], 'safe'],
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
        $query = Transactions::find()->joinWith('type');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'company_id' => $this->company_id,
            'type_id' => $this->type_id,
            'type' => $this->definitionType

        ]);
        
        return $dataProvider;
    }
}
