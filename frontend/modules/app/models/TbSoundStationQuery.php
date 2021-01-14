<?php

namespace frontend\modules\app\models;

/**
 * This is the ActiveQuery class for [[TbSoundStation]].
 *
 * @see TbSoundStation
 */
class TbSoundStationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TbSoundStation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TbSoundStation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
