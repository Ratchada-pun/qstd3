<?php

namespace homer\menu\models;

use Yii;
use yii\helpers\ArrayHelper;
use homer\menu\models\AuthItem;
use yii\icons\Icon;
use homer\utils\CoreUtility;
use mdm\admin\models\Route;
use yii\db\ActiveRecord;
use homer\behaviors\CoreMultiValueBehavior;
use yii\helpers\Json;
/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $menu_category_id
 * @property integer $parent_id
 * @property string $title
 * @property string $router
 * @property string $parameter
 * @property string $icon
 * @property string $status
 * @property string $item_name
 * @property string $target
 * @property string $protocol
 * @property string $home
 * @property integer $sort
 * @property string $language
 * @property string $assoc
 * @property integer $created_at
 * @property integer $created_by
 *
 * @property MenuCategory $menuCategory
 */
class Menu extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'menu';
    }

    public function behaviors()
    {
        return [
            [
				'class' => CoreMultiValueBehavior::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['route'],
					ActiveRecord::EVENT_BEFORE_UPDATE => ['route'],
				],
				'value' => function ($event) {
                    return !empty($event->sender[$event->data]) ? Json::encode($event->sender[$event->data]) : '';
				},
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['menu_category_id', 'title', 'router'], 'required'],
            [['menu_category_id', 'parent_id', 'created_at', 'created_by', 'sort'], 'integer'],
            [['status', 'home', 'params'], 'string'],
            [['title'], 'string', 'max' => 200],
            [['router', 'parameter'], 'string', 'max' => 250],
            [['icon', 'target'], 'string', 'max' => 30],
            [['item_name'], 'string', 'max' => 64],
            [['protocol'], 'string', 'max' => 20],
            [['language'], 'string', 'max' => 7],
            [['assoc'], 'string', 'max' => 12],
            [['items','auth_items','route'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสเมนู'),
            'menu_category_id' => Yii::t('app', 'หมวดเมนู'),
            'parent_id' => Yii::t('app', 'ภายใต้เมนู'),
            'title' => Yii::t('app', 'ชื่อเมนู'),
            'router' => Yii::t('app', 'ลิงค์'),
            'parameter' => Yii::t('app', 'พารามิเตอร์'),
            'icon' => Yii::t('app', 'ไอคอน'),
            'status' => Yii::t('app', 'สถานะ'),
            'item_name' => Yii::t('app', 'บทบาท'),
            'target' => Yii::t('app', 'เป้าหมาย'),
            'protocol' => Yii::t('app', 'โปรโตคอล'),
            'home' => Yii::t('app', 'หน้าแรก'),
            'sort' => Yii::t('app', 'เรียง'),
            'language' => Yii::t('app', 'ภาษา'),
            'params' => Yii::t('app', 'ลักษณะพิเศษ'),
            'assoc' => Yii::t('app', 'ชุดเมนู'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'items' => Yii::t('app', 'บทบาท'),
            'itemsList' => Yii::t('app', 'บทบาท'),
            'auth_items' => 'บทบาท'
        ];
    }

    public $items;

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'ร่าง'),
                1 => Yii::t('app', 'แสดง'),
                2 => Yii::t('app', 'ซ่อน')
            ],
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        return ArrayHelper::getValue($this->getItemStatus(), $this->status);
    }

    public static function getItemStatus() {
        return self::itemsAlias('status');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategory() {
        return $this->hasOne(MenuCategory::className(), ['id' => 'menu_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(Menu::className(), ['id'=>'parent_id']);
    }

    public function getParentTitle() {
        if ($this->parent_id) {
            $parent = self::find()->where(['id' => $this->parent_id])->one();

            //$str = $str?$str->title:null;
            //return $parent->title;
//        echo "<pre>";
//        print_r($parent);
//        echo "</pre>";
            return $parent->title;
        } else {
            return null;
        }
    }

    public static function getList() {
        return ArrayHelper::map(self::find()->orderBy(['menu_category_id' => SORT_ASC, 'parent_id' => SORT_ASC])->all(), 'id', 'title');
    }

    public static function getSortBy($menu_category_id = null, $parent_id = null) {
        $sort = ArrayHelper::merge(['fist' => 'หน้าสุด', 'last' => 'ท้ายสุด'], ArrayHelper::map(self::find()->where(['parent_id' => $parent_id, 'menu_category_id' => $menu_category_id])->orderBy(['sort' => SORT_ASC])->all(), 'sort', 'title'));
        return $sort;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus() {
        return $this->hasMany(Menu::className(), ['parent_id' => 'id']);
    }

    public static function sortLast($menu_category_id = null, $parent_id = null) {
        $sort = self::find()
                        ->where(['parent_id' => $parent_id, 'menu_category_id' => $menu_category_id])
                        ->select('max(sort)')->scalar();
        return $sort;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuAuths() {
        return $this->hasMany(MenuAuth::className(), ['menu_id' => 'id']);
    }
    
    public function getItemAll() {
        $model = $this->menuAuths;
        return $model ? ArrayHelper::map($model, 'item_name', 'item_name') : null;
    }
    
    public function getItemsList() {
        $model = $this->menuAuths;        
        $str = [];
        if ($model) {
            foreach ($model as $val) {
                $str[] = $val->item_name;
            }
            return implode(', ', $str);
        }
        return null;
    }
    
    public static function getItemsListDistinct() {
        //$model = $this->menuAuth->distinct; 
        $model = MenuAuth::find()->select('item_name')->distinct()->all();
        return ArrayHelper::map($model, 'item_name', 'item_name');
        
    }
    
    public static function getRouterDistinct() {
        //$model = $this->menuAuth->distinct; 
        $model = self::find()->select('router')->distinct()->orderBy('router')->all();
        return ArrayHelper::map($model, 'router', 'router');
        
    }
    
    public static function getParentDistinct() {
        //$model = $this->menuAuth->distinct; 
        $model = self::find()->where(['IS NOT','parent_id',NULL])->all();
        $parent = [];
        foreach($model as $k=>$val){
            //echo $val->parent_id;
            if($val->parent_id)
            $parent[$val->parent_id]=['id'=>$val->parent_id,'title'=>$val->parentTitle];
        }
//        print_r($parent);
//        exit();
        return ArrayHelper::map($parent, 'id', 'title');
        
    }
    
    public static function getAuth(){
        return ArrayHelper::map(AuthItem::getAll(), 'name', 'name');
    }

    public function getIconShow() {
        return Icon::show($this->icon);
    }

    public function getAppRoutes(){
        $route = new Route();
        return $route->getAppRoutes();
    }

    public function getRoute(){
        $result = [];
        if(isset($this->route)){
            $items = $this->route;
            if(is_array($items)){
                foreach($items as $r){
                    $result[$r] = $r;
                }
            }
        }
        return $result;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $session = Yii::$app->session;
        $key = 'menus';
        if ($session->has($key)){
            $session->remove($key);
        }
        return true;
    }
    
}
