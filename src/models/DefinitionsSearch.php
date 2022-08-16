<?php

namespace diginova\mhsb\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\conditions\LikeCondition;

/**
 * This is the model class for table "definitions".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 */
class DefinitionsSearch extends Definitions
{

    public $definitionGroup;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'code'], 'safe'],
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
        $query = Definitions::find();

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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        if (empty($this->type)) {
            $query->andFilterWhere(['or like', 'type', $this->getGroupTypes($this->definitionGroup,true)]);
        }


        return $dataProvider;
    }
}
