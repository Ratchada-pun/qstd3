<?php
namespace homer\widgets;

use Yii;
use  yii\widgets\Menu AS BaseMenu;
use Closure;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use homer\menu\models\Menu as ModelMenu;
use yii\base\InvalidConfigException;
use yii\icons\Icon;

class Menu extends BaseMenu{

    public $iconTemplate = '<i class="menu-icon fa fa-{icon}"></i>';

    public $linkTemplate = '<a href="{url}"><span class="nav-label">{icon} {label}</span>{badge}</a>';
    /**
     * @var string the template used to render the body of a menu which is NOT a link.
     * In this template, the token `{label}` will be replaced with the label of the menu item.
     * This property will be overridden by the `template` option set in individual menu items via [[items]].
     */
    public $labelTemplate = '<span class="nav-label">{icon} {label}</span>{badge}';
    /**
     * @var string the template used to render a list of sub-menus.
     * In this template, the token `{items}` will be replaced with the rendered sub-menu items.
     */
    public $submenuTemplate = "\n<ul class=\"nav nav-second-level\">\n{items}\n</ul>\n";

    public $key;

    public $activeParentCssClass = 'active open';

    public $cachingDuration = 60;

    public function init()
    {
        $session = Yii::$app->session;
        $key = 'menus';
        $this->items = $session->has($key) ? $session->get($key) : false;
        if (Yii::$app->user->isGuest) {
            $session->remove($key);
            $this->items = [];
        }elseif ($this->items === false) {
            $model = ModelMenu::find()
            ->innerJoin('menu_category','menu.menu_category_id = menu_category.id')
            ->where([
                'menu_category.title' => $this->key,
                'menu.status' => '1',
            ])
            ->orderBy(['sort' => SORT_ASC])
            ->all();
            $array = ArrayHelper::index($model,null,'parent_id');
            if(isset($array[""])){
                $this->items = $this->genItems($array[""],$array);
            }else{
                $this->items = [];
            }
            $session->set($key, $this->items);
        }
    }

    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        if (!empty($items)) {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');

            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    protected function renderItem($item)
    {
        if (!empty($item['items'])) {
            $linkTemplate = '<a href="{url}"><span class="nav-label">{icon} {label}</span><span class="fa arrow"></span></a>';
        }else{
            $linkTemplate = $this->linkTemplate;
        }
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $linkTemplate);

            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{label}' => Yii::t('app.menu', $item['label']),
                '{icon}' => isset($item['icon']) ? Icon::show($item['icon']) : '',
                '{badge}' => isset($item['badge-label']) ? Html::tag('span',$item['badge-label'],ArrayHelper::getValue($item,'badgeOptions'),['class' => 'label label-success pull-right']) : '',
            ]);
        }

        $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

        return strtr($template, [
            '{label}' => Yii::t('app.menu', $item['label']),
            '{icon}' => isset($item['icon']) ? Icon::show($item['icon']) : '',
            '{badge}' => isset($item['badge-label']) ? Html::tag('span',$item['badge-label'],ArrayHelper::getValue($item,'badgeOptions'),['class' => 'label label-success pull-right']) : '',
        ]);
    }

    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active'] instanceof Closure) {
                $active = $items[$i]['active'] = call_user_func($item['active'], $item, $hasActiveChild, $this->isItemActive($item), $this);
            } elseif ($item['active']) {
                $active = true;
            }
        }

        return array_values($items);
    }

    protected function genItems($data,$array) {
        $menu = [];
        foreach ($data as $val) {
            $items = isset($array[$val->id]) ? $this->genItems($array[$val->id],$array) : [];
            $visible = false;
            $auth_items = Json::decode($val->auth_items);
            if(count($auth_items) > 0){
                foreach($auth_items as $item){
                    if($visible = Yii::$app->user->can($item)){
                        break;
                    }
                }
            }
            $menu[] = [
                'label' => $val->title,
                'encode' => false,
                'icon' => $val->icon,
                'url' => [$val->router],
                'visible' => $visible,
                'items' => $items,
            ];
        }
        return $menu;
    }

}