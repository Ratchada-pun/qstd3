<?php

namespace homer\widgets;

use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class Panel extends Widget
{
    const TYPE_DANGER = 'danger';
    const TYPE_INFO = 'info';
    const TYPE_PRIMARY = 'primary';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';

    /**
     * @var string tools buttons
     */
    protected $tools;

    /**
     * @var string header text
     */
    public $header;

    /**
     * @var string icon name
     */
    public $icon;

    /**
     * @var string box type
     * You can use one of this class constants
     */
    public $type = '';

    /**
     * @var bool is expandable
     */
    public $expandable = false;

    /**
     * @var bool is collapsable
     */
    public $collapsable = false;

    /**
     * @var bool is removable
     */
    public $removable = false;

    /**
     * @var bool is filled
     */
    public $footer;

    public $headingOptions = ['class' => 'panel-heading'];

    protected function initTools()
    {
        if ($this->expandable || $this->collapsable) {
            $this->tools .= Html::a($this->expandable ? '<i class="fa fa-chevron-up"></i>' : '<i class="fa fa-chevron-down"></i>',false,['class' => 'showhide']);
            if ($this->collapsable) {
                Html::addCssClass($this->options, 'panel-collapse');
            }
        }
        if ($this->removable) {
            $this->tools .= Html::a('<i class="fa fa-times"></i>',false,['class' => 'closebox']);
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initTools();
        Html::addCssClass($this->options, 'hpanel ' . $this->type);
        echo Html::beginTag('div', $this->options);
        if (isset($this->header)) {
            echo Html::beginTag('div', $this->headingOptions);
            if (!empty($this->tools)) {
                echo Html::tag('div', $this->tools, ['class' => 'panel-tools']);
            }
            echo (isset($this->icon) ? $this->icon . '&nbsp;' : '') . $this->header;
            echo Html::endTag('div');
        }
        echo Html::beginTag('div', ['class' => 'panel-body']);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::endTag('div');
        if($this->footer){
            echo Html::tag('div', $this->footer, ['class'=>'panel-footer']);
        }
        echo Html::endTag('div');
        parent::run();
    }
}