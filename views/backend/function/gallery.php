<?php 
if( !function_exists('object_gallery_admin_form') ) {

	function object_gallery_admin_form() {

		$ci =& get_instance();

		extract($ci->data);
		?>
		<div class="col-xs-12 col-md-8 gallery-item" style="padding-left: 0">
  			<div class="panel-group" role="tablist" aria-multiselectable="true">
      			<div class="panel panel-default">
	        		<!-- title data item -->
			        <div class="panel-heading" role="tab" style="padding:5px">
			            <div id="data-gallery-img">
			            	<button class="btn-icon btn-red del-img" data-id="" type="button"><?php echo admin_button_icon('delete');?>Xóa</button>
			            </div>
			        </div>
			        <!-- /title data item -->
			        <div class="panel-body" style="padding:0;">
			          	<div class="ajax-load-qa">&nbsp;</div>
			          	<div id="gallery-item">

			          	</div>
			        </div>
		     	</div>
		  	</div>
		</div>

		<div class="col-xs-12 col-md-4 gallery-form" style="padding:0;">
		  	<form id="form-gallery" data-add="<?= $object->id;?>" data-edit="0">
		    	<div id="ajax_loader" class="ajax-load-qa">&nbsp;</div>
		    	<hr />
		    	<div class="col-xs-12 box-content text-right">
		      		<button type="submit" name="save" class="btn-icon btn-green"><?php echo admin_button_icon('save');?>Lưu</button>
		      		<button type="reset" class="btn btn-default">Hủy</button>
		    	</div>
		  	</form>
		</div>
		<?php
	}
}

add_action( 'before_admin_form_left', 'object_gallery_admin_form');