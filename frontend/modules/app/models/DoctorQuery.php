<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[Doctor]].
 *
 * @see Doctor
 */
class DoctorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Doctor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Doctor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
