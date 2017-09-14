  <div class="search">
	  <a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>&ntype=<?php echo $_GET["ntype"];?>">新增一條資料</a>
  </div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">標題</th>
      <th scope="col">有效性</th>
      <th scope="col">新增時間</th>
      <th scope="col">排序</th>
      <th scope="col">操作</th>
    </tr>
    <?foreach($list as $val){?>
    <tr class="Ls2">
      <td class="N_title"><?=$val['title']?></td>
      <td><?=haveYN($val['showtag'])?></td>
      <td><?=$val['newsdt']?></td>
      <td><input postType="<?=$_GET['cn']?>" postId="<?=$val['id']?>" name="descno" type="text" size="4" value="<?=$val['descno']?>"></td>
      <td class="E_bd">
      	<a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>&id=<?=$val['id']?>">編輯</a> | 
      	<a href="javascript:;" onclick="delFun('<?=$className?>','<?=$val['id']?>')">刪除</a>
      </td>
    </tr>
    <?}?>
  </table>