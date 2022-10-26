<?php
$class           = Template::getClass();
$FormBuilder     = new FormBuilder();
$FormBuilder->add('', 'file', ['id' => 'value', 'label' => 'File dữ liệu']);
$option = [];
if(Template::isClass('post')) {

    $option = Gallery::getOption($class, Admin::getPostType());
}
else if(Template::isClass('post_categories')) {

    $option = Gallery::getOption($class, Admin::getCateType());
}
else {

	$option = Gallery::getOption($class);
}

if($option !== false && have_posts($option)) {
	foreach ($option as $key => $input) {
        $FormBuilder->add('option['.$input['field'].']', $input['type'], $input);
	}
}

$FormBuilder->html(false);