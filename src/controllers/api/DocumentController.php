<?php

namespace diginova\mhsb\controllers\api;
use Yii;
use portalium\rest\ActiveController as RestActiveController;
use diginova\mhsb\Module;
use diginova\mhsb\models\Documents;
use diginova\mhsb\models\Items;
use portalium\user\models\User;
use yii\data\ActiveDataProvider;
use yii\db\Transaction;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use diginova\mhsb\models\Transactions;

class DocumentController extends RestActiveController implements IdentityInterface
{

    public $modelClass= 'diginova\mhsb\models\Documents';
    public $modelItems;
    public $model;

    
    public function actions()
    {
        $actions = parent::actions();
        unset($actions[ 'create' ], $actions[ 'update' ], $actions[ 'delete' ]);
        return $actions;
    }

    public function actionCreate()
    {
        $model = new Documents();
        $modelUser = new User();
        $postParams = Yii::$app->getRequest()->getBodyParams();
        $items = $postParams['items'];
        $access_token = $postParams['owner_token'];
        $modelUser = User::findIdentityByAccessToken($access_token);
        $postParams['user_id'] = $modelUser->getId();
        unset($postParams['items']);
        unset($postParams['owner_token']);
            $valid = ($model->load($postParams,'') && $model->validate());
            $transaction = Yii::$app->db->beginTransaction(
                Transaction::SERIALIZABLE
            );
            try {
                $valid = $model->save(false) && $valid;
                    foreach ($items as $item) {
                        $modelItem = new Items();
                        $modelItem->load($item,'');
                        $modelItem->document_id = $model->id;
                        if(!$modelItem->save(false)){
                            return $this->modelError($modelItem);
                        }
                       
                    }
                    $transaction->commit();
                    return $model;

            } catch (Exception $e) {
                $transaction->rollBack();
                return $this->modelError($model);
                
            }
    
    }




    public function actionUpdate()
    {
        $postParams = Yii::$app->getRequest()->getBodyParams();
        $items = $postParams['items'];
        unset($postParams['items']);
        $access_token = $postParams['owner_token'];  
        $model = $this->findModel($postParams['id']);
        $this->identityControl($model, $access_token);
        $valid = $model->validate();
        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );
        try {
            $valid = $model->save(false) && $valid;
            Items::deleteAll(['document_id' => $model->id]);
                foreach ($items as $item) {
                    $modelItem = new Items();
                    $modelItem->load($item,'');
                    $modelItem->document_id = $model->id;
                    $modelItem->save(false);
                }
                $model->load($postParams,'');
                $model->save(false);
                $transaction->commit();
                return $model;

        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->modelError($model);
            
        }
            
    } 




    public function actionDelete()
    {
        $request = Yii::$app->request;
        $documentId = $request->get('id');
        $access_token = $request->get('access-token');
        $model = $this->findModel($documentId);
        $this->identityControl($model, $access_token);
        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );
        try {
            if (Items::deleteAll(['document_id' => $documentId]) && $model->delete()) {
                $transaction->commit();
                
            } else {
                $transaction->rollBack();
                return $this->modelError($model);
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->modelError($model);
        }

    }





    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($owner_token, $type = null)
    {
        return static::findOne(['access_token' => $owner_token]);
    }

    public function getId()
    {
        return $this->userId;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    protected function findModel($id)
    {
        if (($model = Documents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function identityControl($model, $access_token)
    {
        $modelUser = User::findIdentityByAccessToken($access_token); 
        if($model->user_id !== $modelUser->getId()){
            throw new ForbiddenHttpException('The requested Item could not be found.');
        }
    }
  
}

?>