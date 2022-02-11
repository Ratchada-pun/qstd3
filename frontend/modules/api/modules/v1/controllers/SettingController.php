<?php

namespace frontend\modules\api\modules\v1\controllers;

use common\models\KeyStorageItem;
use frontend\modules\app\models\TbService;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

/**
 * Service controller for the `v1` module
 */
class SettingController extends ActiveController
{
    public $modelClass = 'common\models\KeyStorageItem';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'messages'];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        return $actions;
    }


    public function actionList()
    {
        $filter = new ActiveDataFilter([
            'searchModel' => 'common\models\search\KeyStorageItemSearch'
        ]);

        $filterCondition = null;

        // You may load filters from any source. For example,
        // if you prefer JSON in request body,
        // use Yii::$app->request->getBodyParams() below:
        if ($filter->load(\Yii::$app->request->get())) {
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                // Serializer would get errors out of it
                return $filter;
            }
        }

        $query = KeyStorageItem::find();
        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionMessages()
    {
        $rows = (new \yii\db\Query())
            ->select([
                'i18n_source_message.category',
                'i18n_source_message.message',
                'i18n_message.id',
                'i18n_message.`language`',
                'i18n_message.translation'
            ])
            ->from('i18n_source_message')
            ->innerJoin('i18n_message', 'i18n_source_message.id = i18n_message.id')
            ->where(['i18n_source_message.category' => 'app.frontend'])
            ->all();
        $groups = ArrayHelper::index($rows, null, 'language');
        $result = [];
        foreach ($groups as $locale => $items) {
            $result[$locale] = [
                'message' => ArrayHelper::map($items, 'message', 'translation')
            ];
        }
        return $result;
    }
}
