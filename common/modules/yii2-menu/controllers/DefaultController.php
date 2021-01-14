<?php

namespace homer\menu\controllers;

use Yii;
use homer\menu\models\Menu;
use homer\menu\models\MenuAuth;
use homer\menu\models\MenuSearch;
use homer\menu\models\MenuCategory;
use homer\menu\models\MenuCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\icons\Icon;
use yii\filters\AccessControl;
use homer\utils\CoreUtility;
/**
 * DefaultController implements the CRUD actions for Menu model.
 */
class DefaultController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Settings','@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModelCat = new MenuCategorySearch();
        $dataProviderCat = $searchModelCat->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelCat' => $searchModelCat,
            'dataProviderCat' => $dataProviderCat,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*
    public function actionCreate() {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post())) {
            //post
            $post = Yii::$app->request->post();
            $model->created_at = time();
            $model->created_by = Yii::$app->user->id;


            $transaction = \Yii::$app->db->beginTransaction();
            try {

                if ($flag = $model->save(false)) {

                    $title = $post['Menu']['items'];
                    if ($title) {
                        MenuAuth::deleteAll(['menu_id' => $model->id]);
                        foreach ($title as $key => $val) {
                            $menuAuth = new MenuAuth();
                            $menuAuth->menu_id = $model->id;
                            $menuAuth->item_name = $val;

                            if (($flag = $menuAuth->save(false)) === false) {
                                $transaction->rollBack();
                                break;
                            } else {
                                print_r($articleTag->getErrors());
                            }
                        }
                    }
                } else {
                    print_r($model->getError());
                    exit();
                }

                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }*/

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Menu();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $post = Yii::$app->request->post('Menu',[]);
                $model->created_at = time();
                $model->created_by = Yii::$app->user->id;
                $model->auth_items = isset($post['items'][0]) ? Json::encode($post['items']) : null;
                if($model->save()){
                    return [
                        'forceReload'=>'#index-pjax',
                        'title'=> "Create",
                        'content'=>'<span class="text-success">Create success</span>',
                        'footer'=> Html::button('Close',['class'=>'btn btn-default','data-dismiss'=>"modal"]).
                                Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
            
                    ];
                }else{
                    return [
                        'title'=> "Create",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default','data-dismiss'=>"modal"]).
                                    Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];
                }
                         
            }else{           
                return [
                    'title'=> "Create",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->user_id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }


    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->items = Json::decode($model->auth_items);
        $model->route = !empty($model->route) ? Json::decode($model->route) : [];

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $post = Yii::$app->request->post('Menu',[]);
                $model->created_at = time();
                $model->created_by = Yii::$app->user->id;
                $model->auth_items = isset($post['items'][0]) ? Json::encode($post['items']) : null;
                $model->params = $post['params'] ? Json::decode($post['params']) : null;
                if($model->save()){
                    return [
                        'forceReload'=>'#index-pjax',
                        'title'=> "Update",
                        'content'=>'<span class="text-success">Update success</span>',
                        'footer'=> Html::button('Close',['class'=>'btn btn-default','data-dismiss'=>"modal"]).
                                Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
            
                    ];
                }else{
                    return [
                        'title'=> "Update",
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default','data-dismiss'=>"modal"]).
                                    Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];
                }
                         
            }else{           
                return [
                    'title'=> "Update",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->user_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        if(Yii::$app->request->isAjax){
            return 'Deleted!';
        }
        return $this->redirect(['index']);
    }

    public function actionDeleteMenu() {
        $id = Yii::$app->request->post('id');
        $this->findModel($id)->delete();
        if(Yii::$app->request->isAjax){
            return 'Deleted!';
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMenuOrder(){
        $items = [];
        $model = Menu::find()->orderBy(['sort' => SORT_ASC])->all();
        $array = ArrayHelper::index($model,null,'parent_id');
        if(isset($array[""])){
            $items = $this->renderItems($array[""],$array);
        }
        //echo Json::encode($items);
        return $this->render('menu-order',[
            'items' => $items
        ]);
    }

    private function renderItems($items,$itemsAll){
        $result = [];
        foreach($items as $i => $item){
            $result[] = [
                'content' => $item['title'], 
                'id' => $item['id'],
                'icon' => $item['icon'] ? 'fa fa-'.$item['icon'] : '',
                'options' => ['class' => 'dd-item'],
                'contentOptions' => ['class' => 'dd-handle'],
                'children' => isset($itemsAll[$item['id']]) ? $this->renderItems($itemsAll[$item['id']],$itemsAll) : [],
            ];
        }
        return $result;
    }

    public function actionDtData(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $rows = (new \yii\db\Query())
                    ->select(['menu.*', 'menu_category.title as cat_title'])
                    ->from('menu')
                    ->innerJoin('menu_category','menu.menu_category_id = menu_category.id')
                    ->orderBy(['menu.sort' => SORT_ASC])
                    ->all();
            $status = [
                0 => 'ร่าง',
                1 => 'แสดง',
                2 => 'ซ่อน'
            ];
            $items = [];
            $array = ArrayHelper::index($rows, null, 'id');
            foreach($rows as $i => $item){
                $items[] = [
                    'index' => ($i + 1),
                    'DT_RowId' => $item['id'],
                    'DT_RowAttr' => [
                        'data-key' => $item['id']
                    ],
                    'title' => $item['title'],
                    'cat_title' => $item['cat_title'],
                    'parent' => $item['parent_id'] != null ? $array[$item['parent_id']][0]['title'] : '',
                    'sort' => $item['sort'],
                    'status' => ArrayHelper::getValue($status, $item['status'], ''),
                    'actions' => Html::a(Icon::show('pencil'), ['update','id' => $item['id']],['role' => 'modal-remote','class' => 'btn btn-sm btn-default']).' '.
                                Html::a(Icon::show('trash-o text-danger'), 'javascript:void(0);',['onclick' => 'dt.delete('.$item['id'].');','class' => 'btn btn-sm btn-default'])
                ];
            }
            return Json::encode(['data' => $items]);
        }
    }

    public function actionSaveMenusort(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $items = $request->post('items');
            $this->saveMenuItems($items);
            return Json::encode("Completed!");
        }
    }

    protected function saveMenuItems($items,$parent_id = null, $sort = 0){
        foreach($items as $key => $item){
            $sort++;
            $model = $this->findModel($item['id']);
            $model->sort = $sort;
            $model->parent_id = $parent_id;
            $model->save(false);
            if (array_key_exists("children", $item)) {
               $sort = $this->saveMenuItems($item["children"],$model['id'],$sort);
            }
        }
        return $sort;
    }

}
