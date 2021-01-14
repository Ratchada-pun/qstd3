<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[VnStat]].
 *
 * @see VnStat
 */
class VnStatQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return VnStat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VnStat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
