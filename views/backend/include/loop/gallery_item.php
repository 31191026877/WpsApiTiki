
<li class="col-xs-6 col-sm-3 col-md-2 gallery-box js_object_gallery_sort_item" data-id="<?= $val->id;?>">
  	<div class="radio"><input type="checkbox" name="select[]" value="<?= $val->id;?>" class="icheck gallery-item-checkbox"></div>
  	<div class="img">
  		<?php if($val->type == 'file') {
        	echo Admin::img('https://lh3.googleusercontent.com/zqfUbCXdb1oGmsNEzNxTjQU5ZlS3x46nQoB83sFbRSlMnpDTZgdVCe_LvCx-rl7sOA%3Dw300');
  		} else if($val->type == 'youtube') {
  			echo Admin::img('https://img.youtube.com/vi/'.getYoutubeID($val->value).'/0.jpg');
  		} else { echo Template::img( $val->value ); } ?>
 	</div>
</li>
