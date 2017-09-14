<?php
/**
 * 2013-9-24 14:33
 * @author Administrator
 *
 */
class ArticleController extends Controller {
    public function init() {
        parent::init();
    }

    public function beforeAction($action){
        $id = intval($_GET['id']);
        $model = ($action->getId() == 'list') ? new Category : new Article;
        $seo = $model->getSeo($id);

        $this->title = $seo->seo_title;
        $this->keyword = $seo->seo_keyword;
        $this->desc = $seo->seo_desc;
        return parent::beforeAction($action);
    }

    public function actionList($id){
        $model = new Article;
        $cate = $model->getCate($id);
        if (empty($cate->id))
            $this->redirect('site/error');
        $criteria = new CDbCriteria();
        $criteria->addCondition("cate_name='$cate->name'");
        $count = $model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);
        $list = $model->findAll($criteria);
        $view = 'list';
        if ($id == 17 )
            $view = 'news';
        //这里开始
        elseif ($id == 19)
            $view = 'atlas';
        $this->render($view, array('list' => $list,'pages' => $pages,'cate' => $cate->name,'id' => $id));
    }

    public function actionDetail($id)
    {
        $model = new Article;
        $result = $model->SetDataCache($id);

        $this->render('detail', array('model' => $result[0], 'en_name' => $result[1]));
    }

    public function actionHead()
    {
        session_start();
        //检测是否登录，若没登录则转向登录界面
        if (!isset($_SESSION['username'])) {
            header("Location:login");
            exit();
        }
        $username = $_SESSION['username'];
        $this->renderPartial('index', array(
            'username' => $username,
        ));
    }


}