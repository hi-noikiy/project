<?php
class IndexController extends Controller{
	public $weather;
	public $notice;
	public $news;
	public $workflow;
	public $worktravel;
	public $workovertime;
	public $workout;
	public $taskneed;
	public $result;
	public $losecard;
	
	public function init(){
		parent::init();

	}
    public function actionIndex(){
        $articleModel = new Article;
        $noticeInfo = $articleModel->GetCateArticle('新闻中心', 6, $this->gameId);
        $strategyInfo = $articleModel->GetCateArticle('游戏攻略', 8, $this->gameId);
        $atlasInfo = $articleModel->GetCateArticle('精灵图鉴', 5 , $this->gameId);

        $dataInfo = $articleModel->GetCateArticle('活动', 8, $this->gameId);
        $notice= $articleModel->GetCateArticle('公告', 8, $this->gameId);
        $this->render('index', array(
            'noticeInfo' => $noticeInfo,'strategyInfo' => $strategyInfo,
            'atlasInfo' => $atlasInfo,'dataInfo' =>$dataInfo,'notice' =>$notice
        ));
	}

	public function actionAppdownload(){


		$this->renderPartial('appdownload');
	}

    public function actionPart(){
        session_start();
        //检测是否登录，若没登录则转向登录界面
        if(!isset($_SESSION['username'])){
            header("Location:login");
            exit();
        }
        $username = $_SESSION['username'];
        $data =$username;
        $this->renderPartial('dwz',array(
            'username'=>$data,
        ));
    }
	
	
}