<?php

namespace common\modules\translation;

/**
 * translation module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'common\modules\translation\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @param \yii\i18n\MissingTranslationEvent $event
     */
    public static function missingTranslation($event)
    {
        // do something with missing translation
    }
}
