<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbCaller]].
 *
 * @see TbCaller
 */
class TbCallerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TbCaller[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TbCaller|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
