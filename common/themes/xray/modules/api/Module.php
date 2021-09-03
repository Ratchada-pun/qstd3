<?php

namespace xray\modules\api;

/**
 * api module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'xray\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        
        \Yii::$app->user->enableSession = false;

        $this->modules = [
          'v1' => [
              'class' => 'xray\modules\api\modules\v1\Module',
          ],
        ];
        // custom initialization code goes here
    }
}
