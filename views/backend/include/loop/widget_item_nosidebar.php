<?php $ci =& get_instance(); ?>
<li class="col-xs-12 col-sm-12 col-md-6 col-lg-6 widget_list_item">
	<div class="box widget_item_nosidebar" style="overflow: inherit;" data-key="<?php echo $val->key;?>">
		<div class="header pull-left" style="border-bottom:0;">
    		<h3 style="margin:0;" class="widget-name"><?php echo $val->name;?></h3>
    		<p style="margin:0" class="widget-key"><?php echo $val->key;?></p>
    		<p style="margin:0" class="widget-key">
				<?php foreach ($val->tags as $tag): ?>
					<span class="label label-info" style="background: #000;"><?php echo $tag;?></span>
				<?php endforeach ?>
    		</p>
  		</div>

  		<div class="header-action pull-right" style="padding:5px;">
      		<a class="btn btn-green btn-show-add-widget"><i class="fa fa-plus"></i></a>
  		</div>

  		<div class="col-md-12 location highlights">
      		<?php $i = 0;?>
      		<?php foreach ($ci->sidebar as $side_key => $side): ?>
      		<div class="checkbox"><input name="slider" type="radio" value="<?php echo $side_key;?>" class="icheck" <?php echo ($i==0)?'checked':''; $i++;?>> <?= $side['name'];?></div>
      		<?php endforeach;?>
      		<div class="text-center ">
        		<button type="button" class="btn btn-default widget_add_cancel">Hủy</button>
        		<button type="button" data-id="<?php echo $val->key;?>" class="btn btn-blue widget_add_sidebar">Thêm</button>
      		</div>
    	</div>

    	<div class="clearfix"></div>
	</div>
</li>