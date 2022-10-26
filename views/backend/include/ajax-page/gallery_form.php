<?php
$Form = new FormBuilder();
$Form->add('value', 'text', ['id' => 'value', 'note' => 'Đường dẫn hình ảnh hoặc liên kết youtube']);
$Form->add('', 'html', '<div class="clearfix"></div><hr />');
$options = Gallery::getOption('gallery');
if($options !== false && have_posts($options) ) {
	foreach ($options as $key => $input) {
        $Form->add('option['.$input['field'].']', $input['type'], $input);
	}
}
$Form->html(false);