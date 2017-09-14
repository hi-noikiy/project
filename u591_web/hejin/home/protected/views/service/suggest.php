<link href="<?=ASSETS_URL; ?>css/problem.css" rel="stylesheet" type="text/css">
<div id="main">
<div id="register2">
    <h2><font size="4">游戏建议反馈</font></h2>
    <p class="linkp" >
        如填写资料过程遇到问题，<a>请联系客服</a>
    </p>
    <form  name="RegForm" method="post" action="suggest" onSubmit="return InputCheck(this)" enctype="multipart/form-data">
        <ul>
            <li>
                <span>电话或QQ<font class="star">*</font></span>
                <input id ="phone" name="phone" type="text"  placeholder="请输入手机号码" required>
            </li>
            <li>
                <span>服务器<font class="star">*</font></span>
                <select name="server_id" required="required">
                    <option value="">选择服务器</option>
                    <?php
                    foreach ($gameServer as $k => $v) {
                        echo "<option value='{$v['server_id']}'>{$v['server_name']}</option>";
                    }
                    ?>
                </select>
            </li>
            <li>
                <span>角色名称<font class="star">*</font></span>
                <input  id ="username" name="username" type="text" required>
            </li>

            <li class="textli">
                <span class="textspan">详细描述</span><font class="stars" style="top:220px;">*</font>
                <textarea  id ="desc" name="desc" id="message" name="message" placeholder="内容" required></textarea>
                <b id="num1" style="bottom: 290px;">1000</b>
            </li>

            <li>
                <span>上传截图</span>
                <input name="image" type="file">
            </li>

            <li id="zhuce">
                <span>&nbsp;</span>
                <input type="submit" name="btn" value="提交">
            </li>
        </ul>
    </form>
</div>
</div>
<script>
    function InputCheck(RegForm)
    {
        if (RegForm.phone.value == "")
        {
            alert("手机号或者QQ不能为空");
            RegForm.phone.focus();
            return (false);
        }
        if (RegForm.username.value == "")
        {
            alert("角色名不可为空!");
            RegForm.username.focus();
            return (false);
        }
        if (RegForm.desc.value == "")
        {
            alert("描述内容不能为空");
            RegForm.desc.focus();
            return (false);
        }
    }
</script>
