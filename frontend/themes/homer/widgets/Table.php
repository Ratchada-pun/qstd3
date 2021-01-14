<?php
namespace homer\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class Table extends Widget
{
    public $tableOptions = [];

    public $options = [];

    public $beforeHeader = [];

    public $beforeFooter = [];

    public $afterHeader = [];

    public $afterFooter = [];

    public $theadOptions = [];

    public $footerOptions = [];

    public $content = [];

    public $showFooter = false;

    public $showHeader = true;

    public $caption;

    public $captionOptions = [];

    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if (!isset($this->tableOptions['id'])) {
            $this->tableOptions['id'] = $this->getId();
        }
    }

    public function run(){
        $caption = $this->renderCaption();
        $tableHeader = $this->showHeader ? $this->renderTableHeader() : false;
        $tableBody = $this->renderTableBody();
        $tableFooter = $this->showFooter ? $this->renderTableFooter() : false;
        $content = array_filter([
            $caption,
            $tableHeader,
            $tableFooter,
            $tableBody,
        ]);
        return Html::tag('table', implode("\n", $content), $this->tableOptions);
    }

    protected function generateRows($data)
    {
        if (empty($data)) {
            return '';
        }
        if (is_string($data)) {
            return $data;
        }
        $rows = '';
        if (is_array($data)) {
            foreach ($data as $row) {
                if (empty($row['columns'])) {
                    continue;
                }
                $rowOptions = ArrayHelper::getValue($row, 'options', []);
                $rows .= Html::beginTag('tr', $rowOptions);
                foreach ($row['columns'] as $col) {
                    $colOptions = ArrayHelper::getValue($col, 'options', []);
                    $colContent = ArrayHelper::getValue($col, 'content', '');
                    $tag = ArrayHelper::getValue($col, 'tag', 'th');
                    $rows .= "\t" . Html::tag($tag, $colContent, $colOptions) . "\n";
                }
                $rows .= Html::endTag('tr') . "\n";
            }
        }
        return $rows;
    }

    public function renderTableHeader()
    {
        return Html::beginTag('thead', $this->theadOptions) . "\n".
            $this->generateRows($this->beforeHeader) . "\n" .
            $this->generateRows($this->afterHeader) . "\n" .
            Html::endTag('thead');
    }

    public function renderTableBody()
    {
        if (count($this->content) == 0) {
            return '<tbody></tbody>';
        }
        return  Html::beginTag('tbody', []) . "\n".
                $this->generateRows($this->content) . "\n".
                Html::endTag('tbody');
    }

    public function renderTableFooter()
    {
        return Html::beginTag('tfoot', $this->footerOptions) . "\n".
        $this->generateRows($this->beforeFooter) . "\n" .
        $this->generateRows($this->afterFooter) . "\n" .
        Html::endTag('tfoot');
    }

    public function renderCaption()
    {
        if (!empty($this->caption)) {
            $tag = ArrayHelper::remove($this->captionOptions, 'tag', 'caption');
            return Html::tag($tag, $this->caption, $this->captionOptions);
        }
        return false;
    }
}