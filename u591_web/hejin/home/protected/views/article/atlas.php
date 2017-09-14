
<?php if($this->beginCache($id,array('duration'=>3600,'varyByParam'=>array('page')))) { ?>
    <h4 class="data_header"  style="margin-top: 20px;">
        <span><?=$cate; ?></span>
    </h4>
    <div id="handbook" class="wrap clearfix"style="height: 490px;">
        <div class="clear"></div>
        <ul class="scroll_wrapper clearfix">
            <?php
            if(!empty($list)){
                foreach ($list as $k => $v){ ?>
                    <li class="left">
                        <a href="/article/<?=$v->id?>" title="<?=$v->title?>">
                            <img class="scrollLoading" src="<?=IMAGESURL.$v->images?>" alt="<?=$v->title?>" title="<?=$v->title?>图鉴" width="173px">
                            <p class="role_name"><?=$v->title?></p>
                        </a>
                    </li>
                <?php
                }
            }
            ?>

        </ul>

    </div>
        <div class="btn_page_wrapper">
            <?php
            $this->widget('CLinkPager',array(
                    'header'=>'',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '末页',
                    'prevPageLabel' => '',
                    'nextPageLabel' => '',
                    'pages' => $pages,
                    'maxButtonCount'=>13
                )
            );
            ?>
        </div>
    <?php $this->endCache(); } ?>