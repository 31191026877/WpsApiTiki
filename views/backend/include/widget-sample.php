<?php
class widget_data_key extends widget {
    function __construct() {
        parent::__construct('widget_data_key', 'widget_data_name', [ 'widget_data_type' => true, 'position' => 'widget_data_position']);
        add_action('theme_custom_css', array( $this, 'css'), 10);
    }
    function form( $left = [], $right = []) {
        parent::form($left, $right);
    }
    function widget($option) {
        $box = $this->container_box('widget_data_key', $option);
        echo $box['before'];
        if($this->name != ''){?><div class="header-title"><h3 class="header"><?= $this->name;?></h3></div><?php }
        echo $box['after'];
    }
    function update($new_instance, $old_instance) {
        return $new_instance;
    }
    function css() {}
}
Widget::add('widget_data_key');