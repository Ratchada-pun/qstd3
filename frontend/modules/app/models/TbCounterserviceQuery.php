<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbCounterservice]].
 *
 * @see TbCounterservice
 */
class TbCounterserviceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TbCounterservice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TbCounterservice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
