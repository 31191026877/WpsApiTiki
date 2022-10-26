<?php
$FormBuilder->add('widget_heading_form_box_start', 'html', '<div class="m-3"><div class="row">');
if(!empty($widget->options->heading['style'])) {
    if($widget->options->heading['style'] != 'none') {
        $heading = ThemeWidget::registerHeading($widget->options->heading['style']);
        if((!empty($heading)) && class_exists($heading['class']) && method_exists($heading['class'], 'form')) {
            $Form = $heading['class']::form();
            foreach ($Form as $item) {
                $name = (!empty($item['name'])) ? $item['name'] : $item['field'];
                if(isset($item['name'])) { unset($item['name']);}
                if(isset($item['field'])) { unset($item['field']);}
                $type = $item['type']; unset($item['type']);
                $FormBuilder->add('heading['.$name.']', $type, $item, (isset($widget->options->heading[$name])) ? $widget->options->heading[$name] : null);
            }
            if(isset($widgetResult) && $widgetResult == true) {
                echo $FormBuilder->html(true);
            }
        }
    }
}
$FormBuilder->add('widget_heading_form_box_end', 'html', '</div></div>');
?>