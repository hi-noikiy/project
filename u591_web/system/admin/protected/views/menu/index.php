<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">
        <p><a href="<?php echo $this->createUrl('menu/add'); ?>" title="新增菜单" class="btn"><i class="icon-plus"></i> 新增菜单</a></p>
        <ul class="nav nav-tabs">
        
        	<?php foreach ($info as $key => $var) { ?>
        	<li menuid="<?php echo $var['id']; ?>"<?php if((empty($menuCookie) && $key=='0') || $menuCookie==$var['controller']) echo 'class="active"'; ?> onclick="$.cookie('systemmeunnavtabs', '<?php echo $var['controller'] ?>');">
        		<a href="#<?php echo $var['controller']; ?>" data-toggle="tab"><?php echo $var['name']; ?></a>
        	</li>
        	<?php } ?>
          
        </ul>
        <div class="tab-content">
            
            <?php foreach ($info as $key => $var) { ?>
            <div class="tab-pane fade in<?php if((empty($menuCookie) && $key=='0') || $menuCookie==$var['controller']) echo ' active'; ?>" id="<?php echo $var['controller'] ?>">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>菜单标题</th>
                            <th>GROUP</th>
                            <th>CONTROLLER</th>
                            <th>ACTION</th>
                            <th>备注</th>
                            <th style="width:50px">显示</th>
                            <th style="width:60px">可关闭</th>
                            <th style="width:50px;text-align:center">新增</th>
                            <th style="width:50px;text-align:center">修改</th>
                            <th style="width:50px;text-align:center">删除</th>
                            <th style="width:50px;text-align:center">排序</th>
                        </tr>
                    </thead>
                    <tr menuid="<?php echo $var['id']; ?>">
                        <td colspan="2"><?php echo $var['name']; ?></td>
                        <td colspan="2"><?php echo $var['controller']; ?></td>
                        <td></td>
                        <td><?php if($var['status']) echo '<i class="icon-ok"></i>'; ?></td>
                        <td></td>
                        <td><a href="<?php echo $this->createUrl('menu/add/m_id/'.$var['id']); ?>" title="新增菜单"><i class="icon-plus"></i> 新增</a></td>
                        <td style="text-align:center"><a href="<?php echo $this->createUrl('menu/update/id/'.$var['id']); ?>"><i class="icon-edit"></i> 修改</a></td>
                        <td style="text-align:center"><a data-trigger="confirm" data-content="你确定要删除吗？删除后无法恢复" href="<?php echo $this->createUrl('menu/del/'.$var['id']); ?>"><i class="icon-remove"></i> 删除</a></td>
                        <td style="text-align:center" onmouseover="onmouseoverdragsort()" onmouseout="onmouseoutdragsort()"><i class="icon-move"></i> 排序</td>
                    </tr>
                    <?php if (isset($var['s'])) { ?>
                    <?php foreach ($var['s'] as $var2) { ?>
                    <tr menuid="<?php echo $var2['id']; ?>">
                        <td></td>
                        <td colspan="3"><?php echo $var2['name']; ?></td>
                        <td></td>
                        <td><?php if($var2['status']) echo '<i class="icon-ok"></i>'; ?></td>
                        <td></td>
                        <td><a href="<?php echo $this->createUrl('menu/add/m_id/'.$var2['m_id'].'/parentid/'.$var2['id']); ?>" title="新增菜单"><i class="icon-plus"></i> 新增</a></td>
                        <td style="text-align:center"><a href="<?php echo $this->createUrl('menu/update/id/'.$var2['id']); ?>"><i class="icon-edit"></i> 修改</a></td>
                        <td style="text-align:center"><a data-trigger="confirm" data-content="你确定要删除吗？删除后无法恢复" href="<?php echo $this->createUrl('menu/del/'.$var2['id']); ?>"><i class="icon-remove"></i> 删除</a></td>
                        <td style="text-align:center" onmouseover="onmouseoverdragsort()" onmouseout="onmouseoutdragsort()"><i class="icon-move"></i> 排序</td>
                    </tr>
                    <?php if (isset($var2['s'])) { ?>
                    <?php foreach ($var2['s'] as $var3) { ?>
                    <tr menuid="<?php echo $var3['id']; ?>">
                        <td><?php echo $var3['name']; ?></td>
                        <td colspan="2"></td>
                        <td><?php echo $var3['action']; ?></td>
                        <td><?php echo $var3['remark']; ?></td>
                        <td><?php if($var3['status']) echo '<i class="icon-ok"></i>'; ?></td>
                        <td><?php if($var3['closeable']) echo '<i class="icon-ok"></i>'; ?></td>
                        <td></td>
                        <td style="text-align:center"><a href="<?php echo $this->createUrl('menu/update/id/'.$var3['id']); ?>"><i class="icon-edit"></i> 修改</a></td>
                        <td style="text-align:center"><a data-trigger="confirm" data-content="你确定要删除吗？删除后无法恢复" href="<?php echo $this->createUrl('menu/del/'.$var3['id']); ?>"><i class="icon-remove"></i> 删除</a></td>
                        <td style="text-align:center" onmouseover="onmouseoverdragsort()" onmouseout="onmouseoutdragsort()"><i class="icon-move"></i> 排序</td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>

                </table>
            </div>
          	<?php } ?>
        </div>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript">
        $(document).ready(function () {
            onmouseoutdragsort();
            $("ul").dragsort("destroy");
            $("ul").dragsort({dragSelector: "li", dragBetween: true, dragEnd: saveOrder2});
        });
        function saveOrder2() {
            var data = $("ul li").map(function () {
                return $(this).attr('menuid');
            }).get();
            ajax_post("{site 'system/menu_sort'}", 'sort=' + data.join("|"));
        }
        function saveOrder() {
            var data = $("table tbody tr").map(function () {
                return $(this).attr('menuid');
            }).get();
            ajax_post("{site 'system/menu_sort'}", 'sort=' + data.join("|"));
        }
        function onmouseoutdragsort() {
            $("table tbody").dragsort("destroy");
        }
        function onmouseoverdragsort() {
            $("table tbody").dragsort({dragSelector: "tr", dragBetween: true, dragEnd: saveOrder});
        }
    </script>   
</body>
</html>