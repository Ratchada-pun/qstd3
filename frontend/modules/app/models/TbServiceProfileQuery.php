<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbServiceProfile]].
 *
 * @see TbServiceProfile
 */
class TbServiceProfileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TbServiceProfile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TbServiceProfile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
