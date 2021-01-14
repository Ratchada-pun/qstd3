<?php
namespace homer\widgets\tbcolumn;

use Yii;
use yii\bootstrap\Widget;
use homer\assets\DatatablesAsset;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\i18n\Formatter;
use yii\web\View;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

class DataTable extends Widget{

    public $tableOptions = ['class' => 'table table-hover table-striped table-bordered','width' => '100%'];

    public $columns = [];

    public $headerRowOptions = [];

    public $theadOptions = [];

    public $dataProvider;

    public $emptyText;

    public $emptyCell = '&nbsp;';

    public $formatter;

    public $dataColumnClass;

    public $itemLabelSingle;

    public $emptyTextOptions = ['class' => 'empty'];

    public $pjax = false;

    public $pjaxSettings = [];

    protected $_gridClientFunc = '';

    protected $_hashVar;

    protected $_language = [
        "search" => "ค้นหา: _INPUT_",
        "sProcessing" =>   "กำลังดำเนินการ...",
        "sLengthMenu" =>   "แสดง _MENU_ แถว",
        "sZeroRecords" =>  "ไม่พบข้อมูล",
        "sInfo" =>         "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
        "sInfoEmpty" =>    "แสดง 0 ถึง 0 จาก 0 แถว",
        "sInfoFiltered" => "(กรองข้อมูล _MAX_ ทุกแถว)",
        "sInfoPostFix" =>  "",
        "oPaginate" => [
            "sFirst" =>    "หน้าแรก",
            "sPrevious" => "ก่อนหน้า",
            "sNext" =>     "ถัดไป",
            "sLast" =>     "หน้าสุดท้าย"
        ],
    ];

    public function init()
    {
        parent::init();
        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }

        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }
        if ($this->emptyText === null) {
            $this->emptyText = Yii::t('yii', 'No results found.');
        }
        if (!isset($this->itemLabelSingle)) {
            $this->itemLabelSingle = 'item';
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $this->dataProvider->pagination->pageSize = false;
        $this->tableOptions['id'] = $this->getId();
        $this->initColumns();
        $view = $this->getView();
        $data = Json::htmlEncode($this->renderDataColumns());
        $this->_hashVar = 'dtData' . hash('crc32', $data);
        $view->registerJs("var {$this->_hashVar} = {$data};", View::POS_HEAD);
        DatatablesAsset::register($view);
    }

    public function run()
    {
        $this->initClientOptions();
        $this->registerPlugin('DataTable');
        $this->beginPjax();
        echo implode("\n", [
            Html::beginTag('table', $this->tableOptions),
            $this->renderTableHeader(),
            Html::endTag('table')
        ]) . "\n";
        $this->endPjax();
    }

    protected function initClientOptions(){
        $this->clientOptions['data'] = new JsExpression("{$this->_hashVar}");
    }

    protected function initColumns()
    {
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => $this->dataColumnClass ?: DataColumn::className(),
                    'grid' => $this,
                ], $column));
            }
            $this->columns[$i] = $column;
            $this->clientOptions['columns'][] = array_merge([
                'data' => $column->data,
                'title' => isset($column->title) ? $column->title : $column->data,
            ],$column->options);
        }
    }

    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }
        return Yii::createObject([
            'class' => $this->dataColumnClass ?: DataColumn::className(),
            'grid' => $this,
            'data' => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'text',
            'title' => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        return Html::tag('thead',$content,$this->theadOptions);
    }

    public function renderDataColumns()
    {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            $rows[] = $this->renderDataRow($model, $key, $index);
        }
        if (empty($rows) && $this->emptyText !== false) {
            $colspan = count($this->columns);
            //return "<tbody>\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n</tbody>";
        }
        //$this->getView()->registerJs(Json::encode($rows));
        return $rows;
    }

    public function renderDataRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
            $cells['DT_RowId'] = $key;
            $cells['DT_RowAttr'] = ['data-key' => is_array($key) ? json_encode($key) : (string) $key];
            $cells[trim($column->data)] = $column->renderDataCell($model, $key, $index);
        }
        //$options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;
        return $cells;
    }

    public function renderEmpty()
    {
        if ($this->emptyText === false) {
            return '';
        }
        $options = $this->emptyTextOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        return Html::tag($tag, $this->emptyText, $options);
    }

    protected function beginPjax()
    {
        if (!$this->pjax) {
            return;
        }
        $view = $this->getView();
        if (empty($this->pjaxSettings['options']['id'])) {
            $this->pjaxSettings['options']['id'] = $this->options['id'] . '-pjax';
        }
        Pjax::begin($this->pjaxSettings['options']);
        echo ArrayHelper::getValue($this->pjaxSettings, 'beforeGrid', '');
    }

    protected function endPjax()
    {
        if (!$this->pjax) {
            return;
        }
        echo ArrayHelper::getValue($this->pjaxSettings, 'afterGrid', '');
        Pjax::end();
    }
}