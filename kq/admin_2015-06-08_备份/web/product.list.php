  <div class="search">
	  <a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>">新增一條資</a><br/> 
	  <form action="index.php" method="get">
	  	  Item No.:<input name="productid" type="text" size="20">
		  productname:<input name="productname" type="text" size="20">
		  <input name="type" type="hidden" value="<?=$_GET['type']?>" size="20">
		  <input name="do" type="hidden" value="list" size="20">
		  <input name="cn" type="hidden" value="<?=$className?>" size="20">
		  <input name="sear" type="hidden" value="sear"/>
		  <input type="submit" value="搜索"> 
	  </form>
  </div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">產品圖片</th>
      <th scope="col" class="T_title">產品名稱</th>
      <th scope="col" class="T_title">產品分類</th>
      <th scope="col" class="T_title">產品编号</th>
      <th scope="col">原價</th>
      <th scope="col">好康</th>
      <th scope="col">有效</th>
      <th scope="col">操作</th>
    </tr>
    <?foreach($list as $val){?>
    <tr class="Ls2">
      <td class="N_title"><img src="<?php echo img($val["small_img"]);?>" width="150"  ></td>
      <td class="N_title"><?=$val['name']?></td>
      <td class="N_title">
      <?php
      $category=$webdb->getValue("select name from _web_product_cate where id=".$val["pid"],"name");
      echo $category?$category:"第一級分";
      ?>
      </td>
      <td><?=$val['ptype']?></td>
      <td><?=$val['price']?></td>
      <td><?=$val['dis_price']?></td>
      <td><?=haveYN($val['showtag'])?></td>
      <td class="E_bd">
      	<a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>&id=<?=$val['id']?>">編輯</a> | 
      	<a href="javascript:;" onclick="delFun('<?=$className?>','<?=$val['id']?>')">刪除</a>
      </td>
    </tr>
    <?}?>
  </table>
