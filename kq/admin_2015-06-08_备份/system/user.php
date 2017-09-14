<?php
$admin=new admin();
$admin->setOrder('depId');
$admin->p=$_GET['p'];
$userlist=$admin->getList();
$pageCtrl=$admin->getPageInfoHTML();
?>
 <h1 class="title"><span>用户列表</span></h1>
 <div class="pidding_5">
  <div class="search">
   <a href="index.php?type=system&do=userinfo">添加用户</a>
  </div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">用户名</th>
      <th scope="col">姓名</th>
      <th scope="col">所属部门</th>
      <th scope="col">岗位</th>
      <th scope="col">卡号</th>
      <th scope="col">部门主管</th>
      <th scope="col">查询所有审核</th>
      <th scope="col">权限群组</th>
      <th scope="col">操作</th>
    </tr>
    <?foreach($userlist as $user){?>
    <tr class="Ls2">
      <td class="N_title"><?=$user['login_name']?></td>
      <td><?=$user['real_name']?></td>
      <td><?php
              $dep= new department();
              echo $dep->getInfo($user['depId'],"name",'pass');
              ?>
      </td>
      <td><?php
              $job= new job();
              echo $job->getInfo($user['jobId'],"name",'pass');
          ?>
      </td>
      <td><?=$user['card_id']?></td>
      <td><?php
              echo $user['depMax']?'是':'否';
              ?>
      </td>
      <td><?php
              echo $user['seartag']?'是':'否';
              ?>
      </td>
      <td><?=$user['gp_name']?></td>
      <td class="E_bd"><a target="_blank" href="kaohe_qx.php?admin_user=<?=$user['id']?>">考核权限</a> |<a href="index.php?type=system&do=userinfo&id=<?=$user['id']?>">编辑</a> | <a href="index.php?type=system&do=user_perm&admin_id=<?=$user['id']?>">私有權限</a><?if($user['id']>99){?> | <a href="javascript:;" onclick="delFun('admin','<?=$user['id']?>')">删除</a><?}?></td>
    </tr>
    <?}?>
  </table>
  <div class="news-viewpage"><?=$pageCtrl?></div>
  </div>
