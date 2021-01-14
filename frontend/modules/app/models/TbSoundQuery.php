<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbSound]].
 *
 * @see TbSound
 */
class TbSoundQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TbSound[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TbSound|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
