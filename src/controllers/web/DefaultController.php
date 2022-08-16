<?php

namespace diginova\mhsb\controllers\web;

use portalium\web\Controller as WebController;
use Yii;
use DateTime;
use DateInterval;
use diginova\mhsb\models\Companies;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Documents;
use diginova\mhsb\models\Items;
use diginova\mhsb\models\Transactions;

use diginova\mhsb\Module;

use portalium\user\models\User;

class DefaultController extends WebController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDashboard()
    {
        $models = [new User(), new Definitions(), new Companies(), new Documents(), new Transactions()];
        $data['activitiesData'] = self::lastActivities($models);
        $data['lastTransactions'] = Transactions::find()->joinWith('type')->limit(20)->offset(0)->orderBy(['created_at' => SORT_DESC])->all();
        $data['totalUsers'] = User::find()->count();
        $data['totalCompanies'] = Companies::find()->count();
        $data['totalDocuments'] = Documents::find()->count();
        $data['totalProfit'] = Transactions::find()->joinWith('type')->where(['definitions.type' => Definitions::TYPE_COL])->sum('amount') - Transactions::find()->joinWith('type')->where(['definitions.type' => Definitions::TYPE_PAY])->sum('amount');
        $data['totalCol'] = Transactions::find()->joinWith('type')->where(['definitions.type' => Definitions::TYPE_COL])->sum('amount');
        $data['totalPay'] = Transactions::find()->joinWith('type')->where(['definitions.type' => Definitions::TYPE_PAY])->sum('amount');

        return $this->render('dashboard', $data);
    }

    public static function lastActivities($models = [])
    {
        if (empty($models)) {
            return ['No activation'];
        }

        $modelsData = [];

        foreach ($models as $model) {
            $modelData = $model->find()
                ->limit(5)
                ->offset(0)
                ->orderBy(['updated_at' => SORT_DESC, 'created_at' => SORT_DESC])
                ->all();

            foreach ($modelData as $row) {
                $activityStatus = (method_exists($row->className(), 'activityStatus')) ? $row->activityStatus() : false;
                if (!$activityStatus) {
                    if ($row->created_at == $row->updated_at)
                        $activityStatus = Module::t('New {name} added',['name' => Module::t($row->formName() . ' record')]);
                    else
                        $activityStatus = Module::t('{name} updated',['name' => Module::t($row->formName() . ' record')]);
                }

                $timespan = time() - $row->updated_at;
                $d1 = new DateTime();
                $d2 = new DateTime();
                $d2->add(new DateInterval('PT'.$timespan.'S'));
                $diff = $d2->diff($d1);

                $dateLabel  =
                    ($diff->y > 0) ? Module::t('{0} years', [$diff->y]) :
                        (($diff->m > 0) ? Module::t('{0} months', [$diff->m]) :
                            (($diff->d > 0) ? Module::t('{0} days', [$diff->d]) :
                                (($diff->h > 0) ? Module::t('{0} hours', [$diff->h]) :
                                    (($diff->i > 0) ? Module::t('{0} mins', [$diff->i]) :
                                        Module::t('Just Now')))));
                
                $modelName = strtolower($row->formName());
                $modelIcon = '';
                $modelColor = '';
                switch ($modelName){
                    case 'users':
                        $modelIcon = 'fa fa-bar-chart-o';
                        $modelColor = 'label-danger"';
                        break;
                    case 'companies':
                        $modelIcon = 'fa fa-bar-chart-o';
                        $modelColor = 'label-warning';
                        break;
                    case 'definitions':
                        $modelIcon = 'fa fa-bar-chart-o';
                        $modelColor = 'label-info';
                        break;
                    case 'transaction':
                        $modelIcon = 'fa fa-bar-chart-o';
                        $modelColor = 'label-success';
                        break;
                    case 'documents':
                        $modelIcon = 'fa fa-bar-chart-o';
                        $modelColor = 'label-primary';
                        break;
                    default:
                        $modelIcon = 'fa fa-check';
                        $modelColor = 'label-default';
                        break;
                }

                $modelsData[$row->updated_at] = ['model' => $modelName,'model-icon' => $modelIcon,'model-color'=> $modelColor, 'label' => $activityStatus, 'date' => $row->updated_at,'date-label' => $dateLabel];
            }
        }
        krsort($modelsData);
        return $modelsData;
    }
}
