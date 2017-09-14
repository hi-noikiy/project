  <div class="search">
	  <a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>">添加部门</a>
	  
<!--	  <form action="index.php" method="get">
		  name:<input name="name" type="text" size="20">
		  <input name="type" type="hidden" value="<?=$_GET['type']?>" size="20">
		  <input name="do" type="hidden" value="list" size="20">
		  <input name="cn" type="hidden" value="<?=$className?>" size="20">
		  <input name="sear" type="hidden" value="sear"/>
		  <input type="submit" value="搜索"> 
	  </form>
-->
  </div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">部门名称</th>
      <th scope="col">上级部门</th>
      <th scope="col">操作</th>
    </tr>
    <?foreach($list as $val){?>
    <tr class="Ls2">
      <td class="N_title"><?=$val['name']?></td>
      <td >
      <?php
      $category=$webdb->getValue("select pid,name from _web_department where id=".$val["pid"]);
      
      echo $category?$category['name']:"第一級分类";
      ?>
      </td>
      <td class="E_bd">
      	<a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>&id=<?=$val['id']?>">编辑</a> |
      	<a href="javascript:;" onclick="delFun('<?=$className?>','<?=$val['id']?>')">删除</a>
      </td>
    </tr>
    <?}?>
  </table>
