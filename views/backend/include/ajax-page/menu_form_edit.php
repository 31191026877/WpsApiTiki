<?php
$Form = new FormBuilder();
$Form->add('name', 'text', ['label' => 'Tiêu đề', 'required' => 'required'], $val->name);
$option_all 	= ThemeMenu::getItemOption();
$option_object 	= ThemeMenu::getItemOption($val->type, $val->object_type);
$option 		= array_merge($option_all, $option_object);
if(have_posts($option)) {
	foreach ($option as $key => $input) {
        if(isset($input['level'])) {
            if(is_numeric($input['level']) && $input['level'] != $val->level) continue;
            if(is_array($input['level']) && in_array($val->level, $input['level']) === false) continue;
            unset($input['level']);
        }
		$v = isset($val->data[$key])?$val->data[$key]:'';
        $Form->add($input['field'], $input['type'], $input, $v);
	}
}
if($val->type == 'link') {
    $Form->add('url', 'text', ['label' => 'Url'], $val->slug);
}
$Form->html(false);