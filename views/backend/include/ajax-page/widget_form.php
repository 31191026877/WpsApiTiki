<?php
$before     ='<div class="col-md-8" style="padding:0;"><div class="row m-1">';
$center     = '</div></div><div class="col-md-4" style="padding:0"><div class="row m-1">';
$after      = '</div></div>';

if(!$widget->left->hasField() || !$widget->right->hasField()) {
    $before  ='<div class="col-md-12" style="padding:0;">';
    $center = '';
}

$FormBuilder = new FormBuilder();

$FormBuilder->add('before', 'html', ['html' => $before]);

if($widget->heading) {
    $FormBuilder->add('name', 'widgetHeading', ['label'=> 'Tiêu đề'], $widget->name);
    $FormBuilder->add('heading[style]', 'hidden', ['label'=> 'Tiêu đề'], (!empty($widget->options->heading['style'])) ? $widget->options->heading['style'] : 'none');
    $FormBuilder->add('widget_heading_form_start', 'html', '<div class="clearfix"></div><div id="widget_heading_form" class="widget_heading_editor_box" style="display: none;">');
    include_once 'widget_form_heading.php';
    $FormBuilder->add('widget_heading_form_end', 'html', '<div class="clearfix"></div></div>');
}
else {
    $FormBuilder->add('name', 'text', ['label'=> 'Tiêu đề'], $widget->name);
}

foreach (Language::list() as $langKey => $langData) {
    if($langKey == Language::default()) continue;
    $FormBuilder->add('title_'.$langKey, 'text', ['label' => 'Tiêu đề ('.$langData['label'].')'], (isset($widget->options->{'title_'.$langKey})) ? $widget->options->{'title_'.$langKey} : '');
}

if($widget->left->hasField()) {
    foreach ($widget->left->getField() as $key => $item) {
        $FormBuilder->add($item['name'], $item['type'], $item['args'], $widget->options->{$item['name']});
    }
}

$FormBuilder->add('center', 'html', ['html' => $center]);

if($widget->right->hasField()) {
    foreach ($widget->right->getField() as $key => $item) {
        $FormBuilder->add($item['name'], $item['type'], $item['args'], $widget->options->{$item['name']});
    }
}

$FormBuilder->add('after', 'html', ['html' => $after]);
?>
<div id="<?php echo $widget->key;?>">
    <div class="row m-1">
        <?php echo $FormBuilder->html(); ?>
    </div>
</div>