<?php

namespace homer\menu\models;

use yii\db\ActiveRecord;
use Yii;

use yii\rbac\Item;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;

/**
 * This is the model class for table "auth_item".
 *
 * @property string  $name
 * @property integer $type
 * @property string  $description
 * @property string  $rule_name
 * @property string  $data
 * @property integer $created_at
 * @property integer $updated_at
 */
class AuthItem extends ActiveRecord {

    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string
     */
    public static function tableName() {
        return '{{%auth_item}}';
    }

    /**
     * Return roles.
     * NOTE: used for updating user role (user/update).
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRoles() {
        // we make sure that only You can see theCreator role in drop down list
        if (Yii::$app->user->can('theCreator')) {
            return static::find()->select('name')->where(['type' => 1])->all();
        }
        // admin can not see theCreator role in drop down list
        else {
            return static::find()->select('name')
                            ->where(['type' => 1])
                            ->andWhere(['!=', 'name', 'theCreator'])
                            ->all();
        }
    }

    public static function getAll() {
        $searchModel = new AuthItemSearch(['type' => Item::TYPE_PERMISSION]);
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
        $name = [];
        foreach ($dataProvider->allModels as $key=>$item){
            $name[] = ['name'=>$item->name];            
        }        
        return $name;
    }

}
