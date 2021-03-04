<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbDoctor]].
 *
 * @see TbDoctor
 */
class TbDoctorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TbDoctor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TbDoctor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
