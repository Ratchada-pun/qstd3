<?php

namespace homer\menu\models;

use Yii;

/**
 * This is the model class for table "menu_auth".
 *
 * @property integer $menu_id
 * @property string $item_name
 *
 * @property Menu $menu
 */
class MenuAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'item_name'], 'required'],
            [['menu_id'], 'integer'],
            [['item_name'], 'string', 'max' => 64],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => Yii::t('menu', 'Menu ID'),
            'item_name' => Yii::t('menu', 'Item Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }
}
