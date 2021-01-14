<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbCounterserviceType]].
 *
 * @see TbCounterserviceType
 */
class TbCounterserviceTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TbCounterserviceType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TbCounterserviceType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
