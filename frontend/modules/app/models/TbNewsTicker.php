<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_news_ticker".
 *
 * @property int $news_ticker_id
 * @property string $news_ticker_detail รายละเอียด
 * @property int $news_ticker_status สถานะ
 */
class TbNewsTicker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_news_ticker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_ticker_detail', 'news_ticker_status'], 'required'],
            [['news_ticker_detail'], 'string'],
            [['news_ticker_status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'news_ticker_id' => 'News Ticker ID',
            'news_ticker_detail' => 'รายละเอียด',
            'news_ticker_status' => 'สถานะ',
        ];
    }
}
