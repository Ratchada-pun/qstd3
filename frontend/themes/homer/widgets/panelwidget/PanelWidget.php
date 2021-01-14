<?php
namespace homer\widgets\panelwidget;

use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class PanelWidget extends Widget
{
    public $options = [];
    /** @var $headerTitle string the panel-title */
    public $headerTitle;
    /** @var $header bool showing header */
    public $header = true;
    /** @var $content mixed */
    public $content;
    /** @var $footer bool showing footer*/
    public $footer = false;
    /** @var $footerTitle string the panel-footer title */
    public $footerTitle;
    /** @var $type string Bootstrap Contextual Color Type default */
    public $type = '';

    public $collapsed = false;

    public $bordered = false;

    public $headerOptions = [];

    public $buttons = [];

    public $icon = '';

    public $bodyOptions = [];
    /**
     * Bootstrap Contextual Color Types
     */
    const TYPE_DEFAULT = '';
    const TYPE_PRIMARY = 'primary';
    const TYPE_INFO = 'info';
    const TYPE_DANGER = 'danger';
    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_initOptions();
        echo Html::beginTag('div',$this->options);
        $this->_initHeader();
        echo Html::beginTag('div',array_merge(['class' => 'widget-body'],$this->bodyOptions));
        echo $this->content;
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::endTag('div');
        $this->_initFooter();
        echo Html::endTag('div');
    }
    /**
     * Initialize bootstrap Panel styling
     */
    private function _initOptions()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if (!isset($this->options['class'])) {
            if($this->bordered){
                $this->options['class'] = $this->collapsed  ? 'widget radius-bordered collapsed' : 'widget radius-bordered';
            }else{
                $this->options['class'] = $this->collapsed  ? 'widget collapsed' : 'widget';
            }
            
        }
    }
    /**
     * Initialize Panel header
     */
    private function _initHeader(){
        $title = Html::tag('span',$this->headerTitle, ['class' => 'widget-caption']);
        $icon = $this->icon ? Html::tag('i','',['class' => 'widget-icon '.$this->icon]) : '';
        $class = ArrayHelper::getValue($this->headerOptions,'class',['widget-header '.$this->type]);
        $this->headerOptions['class'] = $class;
        $buttons = $this->_initButtons();
        echo Html::tag('div', $icon.$title.$buttons, $this->headerOptions);
    }
    /**
     * Initialize Panel header
     */
    private function _initFooter(){
        if($this->footer)
            Html::tag('div', $this->footerTitle, ['class' => 'panel-footer']);
    }

    private function _initButtons(){
        if($this->buttons){
            $buttons = [];
            foreach($this->buttons as $btn){
                $buttons[] = $btn;
            }
            return Html::tag('div',implode("\n", $buttons),[
                'class' => $this->bordered ? 'widget-buttons buttons-bordered' : 'widget-buttons',
            ]);
        }
        return '';
    }
}