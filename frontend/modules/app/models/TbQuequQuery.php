<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbQuequ]].
 *
 * @see TbQuequ
 */
class TbQuequQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TbQuequ[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TbQuequ|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
