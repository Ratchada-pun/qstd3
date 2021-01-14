<?php
namespace homer\widgets\tbcolumn;

use Closure;
use yii\base\BaseObject;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQueryInterface;
use yii\base\InvalidConfigException;

class DataColumn extends BaseObject
{
    public $grid;

    public $data;

    public $value;

    public $title;

    public $headerOptions = [];

    public $encodeLabel = true;

    public $content;

    public $format = 'text';

    public $options = [];

    public function init()
    {
        parent::init();
        if ($this->data === null) {
            throw new InvalidConfigException('The "data" property must be set.');
        }
    }

    public function getDataCellValue($model, $key, $index)
    {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                return ArrayHelper::getValue($model, $this->value);
            }
            return call_user_func($this->value, $model, $key, $index, $this);
        } elseif ($this->data !== null) {
            return ArrayHelper::getValue($model, $this->data);
        }
        return null;
    }

    public function renderHeaderCell()
    {
        return Html::tag('th', $this->renderHeaderCellContent(), $this->headerOptions);
    }

    protected function renderHeaderCellContent()
    {
        if ($this->title === null) {
            return $this->renderHeaderContent();
        }
        $label = $this->getHeaderCellLabel();
        if ($this->encodeLabel) {
            $label = Html::encode($label);
        }
        return $label;
    }

    protected function getHeaderCellLabel()
    {
        $provider = $this->grid->dataProvider;
        if ($this->title === null) {
            if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQueryInterface) {
                /* @var $modelClass Model */
                $modelClass = $provider->query->modelClass;
                $model = $modelClass::instance();
                $label = $model->getAttributeLabel($this->data);
            } elseif ($provider instanceof ArrayDataProvider && $provider->modelClass !== null) {
                /* @var $modelClass Model */
                $modelClass = $provider->modelClass;
                $model = $modelClass::instance();
                $label = $model->getAttributeLabel($this->data);
            } else {
                $models = $provider->getModels();
                if (($model = reset($models)) instanceof Model) {
                    /* @var $model Model */
                    $label = $model->getAttributeLabel($this->data);
                } else {
                    $label = Inflector::camel2words($this->data);
                }
            }
        } else {
            $label = $this->title;
        }
        return $label;
    }

    public function renderDataCell($model, $key, $index)
    {
        return $this->renderDataCellContent($model, $key, $index);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            return $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        }
        return parent::renderDataCellContent($model, $key, $index);
    }

    protected function renderHeaderContent()
    {
        return trim($this->title) !== '' ? $this->title : $this->getHeaderCellLabel();
    }
}