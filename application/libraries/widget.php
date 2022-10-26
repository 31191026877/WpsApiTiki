<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (PHP_MAJOR_VERSION >= 7) {
    set_error_handler(function ($errno, $errstr) { return strpos($errstr, 'Declaration of') === 0; }, E_WARNING);
}
class widget {

    public $config 		= [];

    public $key 		= '';

    public $name 		= '';

    public $tags        = [];

    public $left 		= [];

    public $right 		= [];

    public $after 		= '';

    public $before 		= '';

    public $options     = [];

    public $id 			= 0;

    public $heading 	= true;

    public string $author = '';

    function __construct($key , $name, $config = []) {

        $this->key 		= $key;

        $this->name 	= $name;

        $this->left = new WidgetField($key);

        if(Language::hasMulti()) {
            foreach (Language::list() as $keyLang => $lang) {
                if(Language::default() == $keyLang) continue;
                $this->left->add('title_'.$keyLang, 'text', ['label' => 'Tiêu đề']);
            }
        }

        $this->right = new WidgetField($key);

        $this->config['container'] = [
            'enable'    => false, 'position' => 'left',  'remove' => [],
        ];

        $this->config['grid'] = [
            'enable'    => false, 'position' => 'left',  'remove' => [],
        ];

        if(have_posts($config)) {
            if(isset($config['container']) && is_bool($config['container'])) {
                $this->config['container']['enable']  = $config['container'];
            }
            else {
                if(isset($config['container']['enable'])) {
                    $this->config['container']['enable']  = xss_clean($config['container']['enable']);
                }
                if(!empty($config['container']['position'])) {
                    $this->config['container']['position']  = xss_clean($config['container']['position']);
                }
                if(!empty($config['container']['remove']) && have_posts($config['container']['remove'])) {
                    $this->config['container']['remove']  = $config['container']['remove'];
                }
            }
            if(isset($config['grid']) && is_bool($config['grid'])) {
                $this->config['grid']['enable']  = $config['grid'];
            }
            else {
                if(isset($config['grid']['enable'])) {
                    $this->config['grid']['enable']  = xss_clean($config['grid']['enable']);
                }
                if(!empty($config['grid']['position'])) {
                    $this->config['grid']['position']  = xss_clean($config['grid']['position']);
                }
                if(!empty($config['grid']['remove']) && have_posts($config['grid']['remove'])) {
                    $this->config['grid']['remove']  = $config['grid']['remove'];
                }
            }

            if($this->config['container']['enable']) {
                if(!empty($config['position'])) $this->config['container']['position'] = xss_clean($config['position']);
                if(!empty($config['remove'])) $this->config['container']['remove'] = $config['remove'];
            }
            if($this->config['grid']['enable']) {
                if(!empty($config['position'])) $this->config['container']['position'] = xss_clean($config['position']);
                if(!empty($config['remove']))   $this->config['container']['remove'] = $config['remove'];
            }
        }

        $this->options = (object)[];

        $this->setBefore();

        $this->setAfter();
    }

    function form($left = [], $right = []) {

        if(!Admin::is()) return false;

        if($this->config['container']['enable']) {
            $listInput['widgetBackground']  = ['field' => 'widgetBackground', 'label'  => 'Nền', 'type' => 'background'];
            $listInput['box']       = ['field' => 'box', 'label' => 'Box', 'type' => 'WidgetInputType::boxContainer'];
            $listInput['box_size']  = ['field' => 'box_size', 'type' => 'WidgetInputType::boxSize'];
            foreach ($left as $item) {

                if($item['field'] == 'bg_color' && isset($listInput['bg_color']))    unset($listInput['bg_color']);

                if($item['field'] == 'bg_image' && isset($listInput['bg_image']))    unset($listInput['bg_image']);

                if($item['field'] == 'widgetBackground')    unset($listInput['widgetBackground']);

                if($item['field'] == 'box')         unset($listInput['box']);

                if($item['field'] == 'box_size')    unset($listInput['box_size']);
            }
            foreach ($right as $item) {
                if($item['field'] == 'bg_color' && isset($listInput['bg_color']))    unset($listInput['bg_color']);
                if($item['field'] == 'bg_image' && isset($listInput['bg_image']))    unset($listInput['bg_image']);
                if($item['field'] == 'widgetBackground')    unset($listInput['widgetBackground']);
                if($item['field'] == 'box')         unset($listInput['box']);
                if($item['field'] == 'box_size')    unset($listInput['box_size']);
            }
            if(have_posts($listInput) && have_posts($this->config['container']['remove'])) {
                foreach ($listInput as $field => $item) {
                    if(in_array($field, $this->config['container']['remove']) !== false) {
                        unset($listInput[$field]);
                    }
                }
            }
            if($this->config['container']['position'] == 'left') {
                $left = array_merge($left, $listInput);
            }
            if($this->config['container']['position'] == 'right') {
                $right  = array_merge($right, $listInput);
            }
        }

        if($this->config['grid']['enable']) {
            $listInput = [];
            $listInput['md']  = array("field"=>"col_md", "label"=>"row desktop",  "type"=>"col",  "value"=>12, 'args' => array('max' => 13));
            $listInput['sm']  = array("field"=>"col_sm", "label"=>"row table",    "type"=>"col",  "value"=>12, 'args' => array('max' => 13));
            $listInput['xs']  = array("field"=>"col_xs", "label"=>"row mobie",    "type"=>"col",  "value"=>12, 'args' => array('max' => 13));
            foreach ($left as $item) {
                if($item['field'] == 'col_md')    unset($listInput['md']);
                if($item['field'] == 'col_sm')    unset($listInput['sm']);
                if($item['field'] == 'col_xs')    unset($listInput['xs']);
            }
            foreach ($right as $item) {
                if($item['field'] == 'col_md')    unset($listInput['md']);
                if($item['field'] == 'col_sm')    unset($listInput['sm']);
                if($item['field'] == 'col_xs')    unset($listInput['xs']);
            }
            if(have_posts($listInput) && have_posts($this->config['grid']['remove'])) {
                foreach ($listInput as $field => $item) {
                    if(in_array($field, $this->config['grid']['remove']) !== false) {
                        unset($listInput[$field]);
                    }
                }
            }
            if($this->config['grid']['position'] == 'left') {
                $left = array_merge($listInput, $left);
            }
            if($this->config['grid']['position'] == 'right') {
                $right  = array_merge($listInput, $right);
            }
        }

        $this->setLeft($left);

        $this->setRight($right);
    }

    function setName($name): void { $this->name = $name; }

    function setLeft($left): void {
        if(have_posts($left)) {
            foreach ($left as $item) {
                $item = array_merge(['value' => ''], $item);
                $this->left->add($item['field'], $item['type'], $item);
            }
        }
    }

    function setRight($right): void {
        if(have_posts($right)) {
            foreach ($right as $item) {
                $item = array_merge(['value' => ''], $item);
                $this->right->add($item['field'], $item['type'], $item);
            }
        }
    }

    function setAfter($after = ''): void {
        $this->after = $after;
    }

    function setBefore($before = ''): void {
        $this->before = $before;
    }

    function setOption($option = []) {

        if(is_array($this->options)) $this->options = (object)$this->options;

        if(Language::hasMulti()) {
            foreach (Language::list() as $keyLang => $lang) {
                if(!isset($this->options->{'title_'.$keyLang})) $this->options->{'title_'.$keyLang} = '';
            }
        }

        if(!isset($this->options->widgetBackground)) $this->options->widgetBackground = [];

        if(!empty($this->options->bg_color)) {
            $this->options->widgetBackground['color'] = $this->options->bg_color;
        }
        if(!empty($this->options->bg_image)) {
            $this->options->widgetBackground['image'] = $this->options->bg_image;
        }

        if($this->left->hasField()) {
            foreach ($this->left->getField() as $item) {
                if(!isset($this->options->{$item['name']})) $this->options->{$item['name']} = $item['value'];
            }
        }
        if($this->right->hasField()) {
            foreach ($this->right->getField() as $item) {
                if(!isset($this->options->{$item['name']})) $this->options->{$item['name']} = $item['value'];
            }
        }
        if(have_posts($option)) {
            if(have_posts($this->options)) {
                foreach ($this->options as $key => $value) {
                    if(isset($option->{$key})) {
                        $this->options->{$key} = $option->{$key};
                    }
                }
            }
            else {
                $this->options = $option;
            }
        }
        return $this->options;
    }

    function getOption() {
        return $this->setOption();
    }

    function widget() {
        echo "widget hiện chưa có trình hiển thị!";
    }

    function widgetNone($sidebar_id): void {}

    function container_box( $class = '', $option = [], $id = '' ) {
        if(empty($option)) $option = $this->options;
        $color = (!empty($option->widgetBackground['color'])) ? $option->widgetBackground['color'] : ((!empty($option->bg_color)) ? $option->bg_color : '');
        $image = (!empty($option->widgetBackground['image'])) ? $option->widgetBackground['image'] : ((!empty($option->bg_image)) ? $option->bg_image : '');
        $gradientUse = (empty($option->widgetBackground['gradientUse'])) ? 0 : 1;

        //CSS
        $css = '';

        if(!empty($color)) $css .= 'background-color:'.$color.';';

        //background gradient
        if(!empty($gradientUse)) {
            $gradientColor1 = (empty($option->widgetBackground['gradientColor1'])) ? '' : $option->widgetBackground['gradientColor1'];
            $gradientColor2 = (empty($option->widgetBackground['gradientColor2'])) ? '' : $option->widgetBackground['gradientColor2'];
            $gradientType = (empty($option->widgetBackground['gradientType'])) ? 'linear-gradient' : $option->widgetBackground['gradientType'].'-gradient';
            $gradientPositionStart = (empty($option->widgetBackground['gradientPositionStart'])) ? 0 : $option->widgetBackground['gradientPositionStart'];
            if($gradientType == 'linear-gradient') {
                $gradientRadialDirection = (empty($option->widgetBackground['gradientRadialDirection2'])) ? '180deg' : $option->widgetBackground['gradientRadialDirection2'].'deg';
            }
            else {
                $gradientRadialDirection = 'circle at '.((empty($option->widgetBackground['gradientRadialDirection1'])) ? 'center' : $option->widgetBackground['gradientRadialDirection1']);
            }
            $gradientPositionEnd = (empty($option->widgetBackground['gradientPositionEnd'])) ? 100 : $option->widgetBackground['gradientPositionEnd'];
            $gradient = $gradientType.'('.$gradientRadialDirection.','.$gradientColor1.' '.$gradientPositionStart.'%, '.$gradientColor2.' '.$gradientPositionEnd.'%)';
        }

        //background image
        if(!empty($image)) {
            $imageSize = (!empty($option->widgetBackground['imageSize'])) ? $option->widgetBackground['imageSize'] : 'cover';
            $imagePosition = (!empty($option->widgetBackground['imagePosition'])) ? $option->widgetBackground['imagePosition'] : 'center center';
            $imageRepeat = (!empty($option->widgetBackground['imageRepeat'])) ? $option->widgetBackground['imageRepeat'] : 'no-repeat';
            $css .= 'background-image:url(\''.Template::imgLink($image).'\')'.((!empty($gradient)) ? ','.$gradient.';' : ';');
            $css .= 'background-size:'.$imageSize.';background-repeat: '.$imageRepeat.'; background-position: '.$imagePosition.';background-blend-mode: color-burn;';
        }
        else if(!empty($gradient)) {
            $css .= 'background:'.$gradient.';';
        }

        //margin
        if(isset($option->box_size['margin'])) {
            $margin = $option->box_size['margin'];
            foreach ($margin as $position => $value) {
                $unit = 'px';
                if(strpos($value, '%') !== false) { $unit = '%'; $value = (int)str_replace('%', '', $value); }
                if($value != 0) $css .= 'margin-'.$position.':'.$value.$unit.';';
            }
        }

        if(isset($option->box_size['padding'])) {
            $padding = $option->box_size['padding'];
            foreach ($padding as $position => $value) {
                $unit = 'px';
                if(strpos($value, '%') !== false) { $unit = '%'; $value = (int)str_replace('%', '', $value);}
                if($value != 0) $css .= 'padding-'.$position.':'.$value.$unit.';';
            }
        }

        $classRow = '';
        if(isset($option->col_xs)) $classRow .= ($option->col_xs != 0)?'col-xs-'.$option->col_xs:'';
        if(isset($option->col_sm)) $classRow .= " ".(($option->col_sm != 0)?'col-sm-'.$option->col_sm:'');
        if(isset($option->col_md)) $classRow .= " ".(($option->col_md != 0)?'col-md-'.$option->col_md:'');
        //LAYOUT
        $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.'" style="'.$css.'" id="'.$id.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
        $after  = '</div>';
        if(isset($option->box)) {
            if($option->box == 'container') {
                $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.'" style="'.$css.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
                $before .= '<div class="container">';
                $after = '</div>';
                $after .= '</div>';
            }
            if($option->box == 'full') {
                $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.'" style="'.$css.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
                $before .= '<div class="container-fluid">';
                $after = '</div>';
                $after .= '</div>';
            }
            if($option->box == 'in-container') {
                $before = '<div class="container js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
                $before .= '<div class="'.$class.'" style="'.$css.'">';
                $after = '</div>';
                $after .= '</div>';
            }
        }
        if(!empty($classRow)) {
            $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.' '.$classRow.'" style="'.$css.'" id="'.$id.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
            $after  = '</div>';
        }
        return ['before' => $before, 'after' => $after];
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    static function add($key) {
        $ci =& get_instance();
        $ci->widget[$key] = $key;
    }

    static function get($args = []) {
        if(is_array($args)) $args = Qr::convert($args);
        if(is_numeric($args)) $args = Qr::set($args);
        return apply_filters('get_widget', model('widget')->get($args));
    }

    static function gets($args = []) {
        if(is_array($args)) $args = Qr::convert($args);
        if(is_numeric($args)) $args = Qr::set($args);
        return apply_filters('gets_widget', model('widget')->gets($args));
    }

    static function count($args = []) {
        if(is_array($args)) $args = Qr::convert($args);
        if(is_numeric($args)) $args = Qr::set($args);
        return apply_filters('count_widget', model('widget')->count($args));
    }
}

class WidgetField {
    public string $name = '';
    public array $field = [];
    public function __construct($name) {
        $this->name = $name;
    }
    public function add($name, $type, $args = []): WidgetField {

        if(str_starts_with($type, '::')) $type = $this->name.$type;

        $this->field[] = [
            'name' => $name,
            'type' => $type,
            'args' => $args,
            'value'=> (isset($args['value'])) ? $args['value'] : null
        ];

        if(Language::hasMulti()) {
            foreach (Language::list() as $langKey => $langData) {
                if($langKey == Language::default()) continue;
                if($type != 'text' && $type != 'textarea' && $type != 'wysiwyg') continue;
                if(!empty($args['label'])) $args['label'] = $args['label'].' ('.$langData['label'].')';
                $this->field[] = [
                    'name' => $name.'_'.$langKey,
                    'type' => $type,
                    'args' => $args,
                    'value'=> (isset($args['value'])) ? $args['value'] : null
                ];
            }
        }

        return $this;
    }
    public function getField(): array {
        return $this->field;
    }
    public function hasField(): bool {
        return have_posts($this->field);
    }
}

class WidgetInputType {
    static function boxContainer($param, $value = []): string {
        $output = '';
        $data = [
            ['value' => 'full',         'img' => 'box1.png'],
            ['value' => 'container',    'img' => 'box2.png'],
            ['value' => 'in-container', 'img' => 'box3.png'],
        ];
        $output .= '<div class="wg-container-box">';
        foreach ($data as $item) {
            $output .= '<div class="wg-box-item '.(($item['value'] == $value)?'active':'').'" data-value="'.$item['value'].'">';
            $output .= Template::img($item['img'], '', ['class'=>'img-responsive', 'return' => true]);
            $output .= '</div>';
        }

        $input 	= ['field' => $param->field,  'label' =>'', 'type' => 'hidden'];
        $output .= _form($input, $value);
        $output .= '</div>';
        return $output;
    }
    static function boxSize($param, $value = []) {
        $value_default = array(
            'margin'    => array('top' => 0, 'left' => 0, 'right' => 0, 'bottom' => 0),
            'border'    => array('top' => 0, 'left' => 0, 'right' => 0, 'bottom' => 0),
            'padding'   => array('top' => 0, 'left' => 0, 'right' => 0, 'bottom' => 0),
        );
        if(!is_array($value)) $value = [];
        $value = array_merge($value_default, $value);
        ?>
        <div class="col-md-12">
            <div class="inp_size_box">
                <div class="inp_size_box_margin box-property">
                    <div class="title">margin</div>
                    <input type="text" name="<?php echo $param->field.'[margin][top]';?>" value="<?php echo $value['margin']['top'];?>" class="margin-top">
                    <input type="text" name="<?php echo $param->field.'[margin][left]';?>" value="<?php echo $value['margin']['left'];?>" class="margin-left">
                    <input type="text" name="<?php echo $param->field.'[margin][right]';?>" value="<?php echo $value['margin']['right'];?>" class="margin-right">
                    <input type="text" name="<?php echo $param->field.'[margin][bottom]';?>" value="<?php echo $value['margin']['bottom'];?>" class="margin-bottom">
                </div>
                <div class="inp_size_box_padding box-property">
                    <div class="title">padding</div>
                    <input type="text" name="<?php echo $param->field.'[padding][top]';?>" value="<?php echo $value['padding']['top'];?>" class="padding-top">
                    <input type="text" name="<?php echo $param->field.'[padding][left]';?>" value="<?php echo $value['padding']['left'];?>" class="padding-left">
                    <input type="text" name="<?php echo $param->field.'[padding][right]';?>" value="<?php echo $value['padding']['right'];?>" class="padding-right">
                    <input type="text" name="<?php echo $param->field.'[padding][bottom]';?>" value="<?php echo $value['padding']['bottom'];?>" class="padding-bottom">
                </div>
                <div class="inp_size_box_content box-property"><div class="title">content</div></div>
            </div>
        </div>
        <div class="clearfix"> </div>
        <style>
            .inp_size_box {
                position: relative;
                width: 100%; height: 200px;
            }

            [class*='inp_size_box_'] {
                position: absolute;
            }
            .inp_size_box_margin {
                width:100%; height:200px;
                border:1px dashed #000;
                background:#f8cb9c;
            }

            .inp_size_box_padding {
                left:35px; top:35px;
                width:calc(100% - 70px); height:130px;
                border:1px dashed #ccc;
                background-color:#c2ddb6;
            }
            .inp_size_box_content {
                left:70px; top:70px;
                width:calc(100% - 136px);
                height:60px; line-height:60px;
                border:1px solid #000;
                background-color:#9fc4e7;
                text-align:center;
            }
            .inp_size_box_content .title {
                float:none;width:100%; text-align:center!important;
            }
            [class*='inp_size_box_'] > input { position: absolute; width:25px; font-size:10px; text-align: center; }
            [class*='inp_size_box_'] > .title { position: absolute; text-align: left; }
            [class*='inp_size_box_'] > input[class*='-top'] { top:5px; left:50%; margin-left:-13px; }
            [class*='inp_size_box_'] > input[class*='-left'] { left:2px; top:50%; margin-top:-13px;}
            [class*='inp_size_box_'] > input[class*='-right'] { right:2px; top:50%; margin-top:-13px;}
            [class*='inp_size_box_'] > input[class*='-bottom'] { bottom:5px; left:50%; margin-left:-13px;}

            .inp_size_box:hover .box-property { background-color: #fff; }
            .inp_size_box_margin:hover { background:#f8cb9c!important; }
            .inp_size_box_border:hover { background:#feedbb!important; }
            .inp_size_box_padding:hover { background:#c2ddb6!important; }
            .inp_size_box_content:hover { background:#9fc4e7!important; }
        </style>
        <?php
    }
    static function columnNumber($param, $value = []): string
    {
        $args        = [];
        $args['min'] = (!empty($param->min)) ? $param->min : 1;
        $args['max'] = (!empty($param->max)) ? $param->max : 12;
        $output = '';
        $output .='<div class="input-cols">';
        $output .='    <div class="input-col-wrap input-col-'.$value.'">';
        for ( $i = $args['min'];  $i <= $args['max'] ; $i++ ) {
            $output .='<div class="col-item" data-col="'.$i.'">'.$i.'</div>';
        }
        $output .='    </div>';
        $output .='    <input type="range" name="'.$param->field.'" value="'.$value.'" min="'.$args['min'].'" max="'.$args['max'].'" id="'.$param->field.'" class="form-control ">';
        $output .='</div>';
        $output .='<div class="clearfix"></div>';
        return $output;
    }
}