<div class="search">
	<a href="javascript:history.go(-1);">后退</a>
	</div>
	<input type="hidden" name="newsdt" value="<?=date('Y-m-d H:i:s')?>">
    <tr>
      <td class="N_title">產品名稱：</td><td class="N_title" colspan="7">
        <input type="text" name="name" size="60">
      </td>
    </tr>
    
    <tr>
      <td class="N_title">產品分類：</td><td class="N_title" colspan="7">
        <select name="pid">
        	<option value="0">第一級分類</option>
        	<? //print_r(dgAryForProductCate('_web_product_cate',"and id<>'".$info['id']."'"),null,false,false);//exit;?>
        	<?=aryOptionforProduct(dgAryForProductCate('_web_product_cate',"and id<>'".$info['id']."'"),null,false,false)?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="N_title">商品型號：</td><td class="N_title" colspan="7">
        <input type="text" name="ptype" size="60">
      </td>
    </tr>
    <tr>
      <td class="N_title">定價：</td><td class="N_title" colspan="7">
        <input type="text" name="price" size="20">
      </td>
    </tr>
    <tr>
      <td class="N_title">優惠價：</td><td class="N_title" colspan="7">
        <input type="text" name="dis_price" size="20">
      </td>
    </tr>
    <tr>
      <td class="N_title">商品簡介：</td><td class="N_title" colspan="7">
        <?=htmlEdit('describ',$info['describ'])?>
      </td>
    </tr>
    
    <tr>
      <td class="N_title">備註說明：</td><td class="N_title" colspan="7">
        <?=htmlEdit('bak',$info['bak'])?>
      </td>
    </tr>
    <?php 
    if(!empty($info)){
    ?>
    <tr>
      <td class="N_title">已上传图片：</td><td class="N_title" colspan="7">
      <?php 
    if(!empty($info["small_img"])){
    ?>
        <img src="<?php echo img($info["small_img"]);?>">&nbsp;
      <?php 
    }
    ?>
      </td>
    </tr>
    
    <?php 
    }
    ?>
    <tr>
      <td class="N_title">上傳圖片：</td><td class="N_title" colspan="7">
        <input type="file" name="upload_img">
      </td>
    </tr>
    
    <tr>
      <td class="N_title">有效性：</td><td class="N_title" colspan="7">
        <select name="showtag">
        	<?=dicOption('tag',1)?>
        </select>
      </td>
    </tr>
<script>
function checkForm(form){
	var msg='';
	if(msg){
		alert(msg);
		return false;
	}else{
	    return true;
	}
}
</script>
