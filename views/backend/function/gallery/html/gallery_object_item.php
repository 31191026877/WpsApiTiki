<li class="col-xs-6 col-sm-3 col-md-2 gallery-object-item js_gallery_object_sort_item" data-id="<?= $val->id;?>">
    <div class="radio">
        <input type="checkbox" name="select[]" value="<?= $val->id;?>" class="icheck gallery-item-checkbox">
    </div>
  	<div class="img">
  		<?php if($val->type == 'file') {
        	echo Admin::img('https://lh3.googleusercontent.com/zqfUbCXdb1oGmsNEzNxTjQU5ZlS3x46nQoB83sFbRSlMnpDTZgdVCe_LvCx-rl7sOA%3Dw300');
  		} else if($val->type == 'youtube') {
  			echo Admin::img('https://img.youtube.com/vi/'.getYoutubeID($val->value).'/0.jpg');
  		} else {
  		    echo Admin::img( $val->value, '', [], 'medium' );
  		} ?>
 	</div>
 	<div class="hidden">
	<?php

        $Formbuilder = new FormBuilder();

        $Formbuilder
            ->add('gallery['.$val->id.'][value]','hidden', ['data-name' => 'value'], $val->value)
            ->add('gallery['.$val->id.'][order]','hidden', ['class'     => 'gallery-item-order'], (!empty($val->order)) ? $val->order : 0);

		$option = [];

        $galleryOptions = Gallery::getOption('object');

		if(empty($class)) {
			$class      = Template::getClass();
			$postType  = Request::get('postType');
			$cate_type  = Request::get('cate_type');
		}

        if($class == 'post' && Arr::has($galleryOptions, $class.'.'.$ci->postType)) {
            $option = $galleryOptions[$class][$ci->postType];
        }
        else if($class == 'post_categories' && Arr::has($galleryOptions, $class.'.'.$ci->cateType)) {
            $option = $galleryOptions[$class][$ci->cateType];
        }
        else if(isset($galleryOptions[$class])) {
            $option = $galleryOptions[$class];
        }

        if(have_posts($option)) {

            foreach ($option as $key => $input) {

                if(empty($input['field'])) continue;

                $meta = (!empty($val->options[$input['field']])) ? $val->options[$input['field']] : Gallery::getItemMeta($val->id, $input['field'], true);

                $input['data-name'] = 'option_'.$input['field'];

                $Formbuilder->add('gallery['.$val->id.'][option]['.$input['field'].']', 'hidden', $input, $meta);
            }
        }

        $Formbuilder->html(false);
	?>
	</div>
</li>