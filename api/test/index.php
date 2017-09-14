<?php
define('API','http://gwalker.cn');
require_once('../MinPHP/core/function.php');
M();
$sql = "select url,name,parameter from api where aid = '1' and isdel='0' order by num asc, ord desc,id desc";
$list = select($sql);
// print_r($list);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<script type="text/javascript" src="jquery.js"></script>

</head>

<body>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="" valign="top" style="line-height:20px;">
    <?php
      foreach ($list as $key => $value) {
        $value['parameter'] = unserialize($value['parameter']);
        // print_r($value['parameter']);
        $params = str_replace(array(',tokenId', ',sign' , 'tokenId', 'sign'), '', implode(',', $value['parameter']['name']));
        echo '<div style="cursor:pointer" onclick="setUrl(\'' . str_replace('http://testapi.zmaxfilm.com:8181/Api/', '', $value['url']) . '\', \'' . $params . '\');">' . $value['name'] . '</div>';
      }
    ?>
    </td>
    <td valign="top"><table width="1200" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="40" align="right">请求载名：</td>
        <td><input name="textfield" type="text" id="textfield" style="width:300px;" value="http://testapi.zmaxfilm.com:8181/Api/" /></td>
      </tr>
      <tr>
        <td width="100" height="40" align="right">接口名称：</td>
        <td><input name="interfaceName" type="text" id="interfaceName" style="width:300px;" /> <input type="button" name="button" class="addparam" value="添加参数" /></td>
      </tr>
      <tbody id="params">

      </tbody>
      <tr>
        <td width="100" height="40" align="right">会话ID：</td>
        <td><input id="appAccount" type="text" style="width:100px;" value="xmmgf" />
          <input id="appPasswd" type="text" style="width:80px;" value="xmmgf123" />
          <input name="params[tokenId]" type="text" id="tokenId" style="width:200px;" />
          <input type="button" name="button" id="getToken" value="获取" /></td>
      </tr>
      <tr>
        <td width="100" height="40" colspan="2" align="left" style="padding-left:300px;"><input type="button" name="button" id="getPost" value="提交" /></td>
      </tr>
      <tr>
        <td width="100" height="40" align="right">返回值：</td>
        <td ><textarea name="textfield6" id="backValue" style="width:800px; height:500px"></textarea></td>
      </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
<script type="text/javascript">

function setUrl(url, params) {
  var arr = params.split(',');
  $("#params").html('');
  $.each(arr, function(k,v) {
  $("#params").append('<tr><td width="100" height="40" align="right">参数：</td>    <td><input name="params[Name][]" value="'+v+'" type="text" style="width:100px;" />值：<input name="params[Value][]" type="text" style="width:250px;" /> <input type="button" name="button"  value="删除" onclick="delParms(this)" /></td>  </tr>');
  })
  $("#interfaceName").val(url);
}

  $('.addparam').click(function(){
    $("#params").append('<tr><td width="100" height="40" align="right">参数：</td>    <td><input name="params[Name][]" type="text" style="width:100px;" />值：<input name="params[Value][]" type="text" style="width:250px;" /> <input type="button" name="button"  value="删除" onclick="delParms(this)" /></td>  </tr>');
  });

  function delParms (obj) {
    $(obj).parent().parent().html('');
  }

  $("#getToken").click(function(){
      $.ajax({
          url:"http://192.168.10.239/Api/Service/getToken",
          data:{'tokenId':$("#tokenId").val(), 'appAccount':$("#appAccount").val(),'appPasswd':$("#appPasswd").val(),'appVersion':'2.0','appVersion':'2.0','deviceNumber':'test','deviceType':'test'},
          dataType:'jsonp',
          type:'post',
           jsonp: 'jsoncallback',
          success:function(json){
            $("#tokenId").val(json.data.tokenId);
          },
          error:function(msg){

          }
      });
  })


    $("#getPost").click(function(){
      $("#backValue").val('');
      var data = {};
      $("input[name='params[Name][]'").each(function(index,element){
        data[$(this).val()] = $("input[name='params[Value][]'").eq(index).val();
        // alert(index);
      });
      data['tokenId'] = $("#tokenId").val();

      if($("#interfaceName").val() == ''){
            alert('接口名称不能为空！');
            return false;
          }

      $.ajax({
          
          url:"http://192.168.10.239/Api/" + $("#interfaceName").val(),
          data:data,
          dataType:'jsonp',
          type:'post',
           jsonp: 'jsoncallback',
          success:function(json){
            if (json.status == '10001') {
              $("#getToken").click();
            };
            var strJson=JSON.stringify(json);
            $("#backValue").val(format(strJson));
          },
          error:function(msg){

          }
      });
  })



function format(jsonStr){
    var res="";
    for(var i=0,j=0,k=0,ii,ele;i<jsonStr.length;i++)
    {//k:缩进，j:""个数
        ele=jsonStr.charAt(i);
        if(j%2==0&&ele=="}")
        {
            k--;                
            for(ii=0;ii<k;ii++) ele="    "+ele;
            ele="\n"+ele;
        }
        else if(j%2==0&&ele=="{")
        {
            ele+="\n";
            k++;            
            for(ii=0;ii<k;ii++) ele+="    ";
        }
        else if(j%2==0&&ele==",")
        {
            ele+="\n";
            for(ii=0;ii<k;ii++) ele+="    ";
        }
        else if(ele=="\"") j++;
        res+=ele;               
    }
    return res;
}

</script>