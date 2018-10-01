<?php
$tuijian_goods=$dd_tao_class->dd_tao_goods(array('num'=>5,'cid'=>'a'));
?>
<div class="shopxiangguan">
            <div class=hot_head_goods>
                <span>精品推荐</span>
            </div>
            <ul>
            <?php foreach($tuijian_goods as $row){?>
                <li>
                    <a target="_blank" href="<?=$row['go_view']?>">
                        <?=html_img($row["pic_url"],2,$row["title"],'',160,160)?>
                    </a>
                    <p><?=$row['title']?></p>
                    <p><span>淘宝价:￥<?=$row['price']?> 元</span></p>
                    <p>返：<b><?=$row['fxje']?></b><?=TBMONEYUNIT?><?=TBMONEY?>
                    </p>
                </li>
                <?php }?>
            </ul>
        </div>