<?php

namespace xray\modules\queue;

/**
 * queue module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'xray\modules\queue\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->layout = '@xray/views/layouts/main-kiosk.php';

        // custom initialization code goes here
    }
}
