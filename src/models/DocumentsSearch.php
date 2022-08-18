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
class DocumentsSearch extends Documents
{
    public $definitionType;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no', 'company_id', 'category_id','type_id'], 'safe'],
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
        $query = Documents::find()->joinWith('type');

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
            'category_id' => $this->category_id,
            'def_id' => $this->def_id,
            'type' => $this->definitionType
        ]);

        return $dataProvider;
    }
}
