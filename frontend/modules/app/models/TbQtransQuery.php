<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbQtrans]].
 *
 * @see TbQtrans
 */
class TbQtransQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TbQtrans[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TbQtrans|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
