<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Common
{
    public function index()
    {
        $userTotal = $this->userTotal();                    //用户总数
        $this->assign('userTotal', $userTotal);
        
        $archiveTotal = $this->archiveTotal();              //文章总数
        $this->assign('archiveTotal', $archiveTotal);
        
        $userNear = $this->userNear();                      //最近7天注册用户数
        $this->assign('userNear', $userNear);
        
        $loginLogList = $this->loginLogList();              //最新登录8条信息
        $this->assign('loginLogList', $loginLogList);
        
        $groupPieJson = $this->groupPieJson();              //用户组人数统计
        $this->assign('groupPieJson', $groupPieJson);
        
        return $this->fetch();
    }
    
    /**
     * @Title: userTotal
     * @Description: todo(用户总数)
     * @author 苏晓信
     * @date 2017年8月14日
     * @throws
     */
    private function userTotal()
    {
        $userModel = new \app\admin\model\User;
        return $userTotal = $userModel->count();
    }
    
    /**
     * @Title: userNear
     * @Description: todo(最近7天注册用户)
     * @author 苏晓信
     * @date 2017年8月14日
     * @throws
     */
    private function userNear()
    {
        $userModel = new \app\admin\model\User;
        $nowTime = strtotime(date('Ymd', time())) + 86400;
        $userTime = $nowTime - 604800;
        return $userNear = $userModel->where('create_time', 'egt', $userTime)->count();
    }
    
    /**
     * @Title: archiveTotal
     * @Description: todo(文章总数)
     * @author 苏晓信
     * @date 2017年8月14日
     * @throws
     */
    private function archiveTotal()
    {
        $archiveModel = new \app\admin\model\Archive;
        return $archiveTotal = $archiveModel->count();
    }
    
    
    /**
     * @Title: loginLogList
     * @Description: todo(最新登录8条信息)
     * @author 苏晓信
     * @date 2017年8月14日
     * @throws
     */
    private function loginLogList()
    {
        $loginLogModel = new \app\admin\model\LoginLog;
        return $loginLogList = $loginLogModel->where("uid",session('userId'))->limit(8)->order('id DESC')->select();
    }
    
    
    /**
     * @Title: groupPieJson
     * @Description: todo(用户组人数统计)
     * @author 苏晓信
     * @date 2017年8月14日
     * @throws
     */
    private function groupPieJson()
    {
        $agModel = new \app\admin\model\Role;
        $userModel = new \app\admin\model\User;
        
        $groupPieArr = [];
        
        $agData = $agModel->where('status', 1)->select();
        $noGroup = ['id' => '0', 'title' => '无角色用户', 'pic' => '#666'];
        $agData[count($array)-1] = $noGroup;
        foreach($agData as $k => $v){
            $agData[$k]['count'] = 0;
            $groupPieArr['labels'][] = $v['title'];
        }
        $userData = $userModel->select();
        foreach ($userData as $k=>$v){
            $userGroup = $v->userGroup;
            if(!empty($userGroup)){
                foreach ($userGroup as $k2 => $v2){
                    foreach ($agData as $k3 =>$v3){
                        if ($v3['id'] == $v2['group_id']){
                            $agData[$k3]['count'] += 1;
                            break;
                        }
                    }
                }
            }else{
                $agData[count($array)-1]['count'] += 1;
            }
        }
        foreach($agData as $k=>$v){
            $groupPieArr['datasets'][0]['data'][] = $v['count'];
            $groupPieArr['datasets'][0]['backgroundColor'][] = $v['pic'];
        }
        return $groupPieJson = json_encode($groupPieArr);
    }
    
    
    /**
     * @Title: cleanCache
     * @Description: todo(清除缓存)
     * @author 苏晓信
     * @date 2017年8月14日
     * @throws
     */
    public function cleanCache()
    {
        if (request()->isPost()){
            deldir(RUNTIME_PATH);
            return ajaxReturn(lang('action_success'));
        }else{
            deldir(RUNTIME_PATH);
            return $this->fetch();
        }
    }
    
    
    
    
    public function icons()
    {
        return $this->fetch();
    }
    
    public function forms()
    {
        return $this->fetch();
    }
    
    public function box()
    {
        return $this->fetch();
    }
    
    public function tab()
    {
        return $this->fetch();
    }
    
    public function tables()
    {
        return $this->fetch();
    }
    
    public function question()
    {
        return $this->fetch();
    }
}
