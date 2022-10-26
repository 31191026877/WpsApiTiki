<?php
class FormBuilder {

    public $input;

    public $output;

    function __construct(){}

    public function add($name = '', $type = '', $args = [], $value = '') {

        if($type == 'html' && is_string($args)) {
            $temp = $args;
            $args = ['html' => $temp];
        }

        if(is_array($name) && have_posts($name)) {

            foreach ($name as $item) {
                if((isset($item['name']) || $item['field']) && isset($item['type'])) {
                    $this->add( ((isset($item['name'])) ? $item['name'] : $item['field']), $item['type'], $item);
                }
            }

            return $this;
        }

        if(!empty($name)) $args['name'] = Str::clear($name);

        if(!empty($type)) $args['type'] = Str::clear($type);

        $input = new InputBuilder($args, $value);

        $this->input[] = $input;

        return $this;
    }

    public function setValue($name = '', $value = '') {

        foreach ($this->input as $key => $item) {

            if($item->name == $name) {

                $this->input[$key]->setValue($value);

                break;
            }
        }

        return $this;
    }

    public function getInput() {

        return $this->input;
    }

    public function html($result = true) {

        if(have_posts($this->input)) {

            foreach ($this->input as $input) {

                $this->output .= $input->render();
            }

            $this->input = [];
        }

        $output = $this->output;

        $this->output = '';

        if($result) return $output;

        echo $output;
    }

    static public function render($args, $value = '') {

        $inputBuilder = new InputBuilder($args, $value);

        return $inputBuilder->render();
    }
}

class InputBuilder {

    public $value;

    public $options;

    public $name   = '';

    public $type   = '';

    public $label  = '';

    public $id     = '';

    public $class  = '';

    public $note  = '';

    public $start  = null;

    public $end  = null;

    public $attributes  = [];

    public $output  = '';

    public $data    = [];

    function __construct($args = [], $value = '') {

        if(!empty($args['field'])) {
            $this->setName($args['field']);
            $this->setID($args['field']);
            unset($args['field']);
        }

        if(!empty($args['name'])) {
            $this->setName($args['name']);
            $this->setID($args['name']);
            unset($args['name']);
        }

        if(!empty($args['label'])) {
            $this->setLabel($args['label']);
            unset($args['label']);
        }

        if(!empty($args['class'])) {
            $this->setClass($args['class']);
            unset($args['class']);
        }

        if(!empty($args['id'])) {
            $this->setID($args['id']);
            unset($args['id']);
        }

        if(!empty($args['note'])) {
            $this->setNote($args['note']);
            unset($args['note']);
        }

        if(isset($args['after']) && $args['after'] !== null) {
            $args['start'] = $args['after'];
            unset($args['after']);
        }

        if(isset($args['before']) && $args['before'] !== null) {
            $args['end'] = $args['before'];
            unset($args['before']);
        }

        if(isset($args['start']) && $args['start'] !== null) {
            $this->setStart($args['start']);
            unset($args['start']);
        }

        if(isset($args['end']) && $args['end'] !== null) {
            $this->setEnd($args['end']);
            unset($args['end']);
        }

        if(!empty($args['options'])) {
            $this->setOptions($args['options']);
            unset($args['options']);
        }

        if(!empty($args['value'])) {
            $this->setValue($args['value']);
            unset($args['value']);
        }

        if(is_numeric($value) || !empty($value)) {
            $this->setValue($value);
        }

        if(!empty($args['type'])) {
            $this->setType($args['type']);
            unset($args['type']);
        }

        $attr = [];

        if(!empty($args['args'])) {

            if(have_posts($args['args'])) {
                $attr = $args['args'];
            }

            unset($args['args']);

            if(have_posts($args)) {
                $attr = array_merge($attr, $args);
            }
        }

        if(have_posts($args)) {
            $attr = array_merge($attr, $args);
        }

        $this->setAttributes($attr);
    }

    public function setName($name ='') {
        $this->name = $name;
        $this->data['name'] = $name;
        return $this;
    }

    public function setType($type ='') {
        $this->type = $type;
        $this->data['type'] = $type;
        return $this;
    }

    public function setLabel($label ='') {
        $this->label = $label;
        $this->data['label'] = $label;
        return $this;
    }

    public function setClass($class ='') {
        if(have_posts($class)) {
            $class = trim(implode(' ', $class));
        }
        $this->class = 'form-control '.$class;
        $this->data['class'] = $this->class;
        return $this;
    }

    public function setID($id ='') {
        $id = str_replace('[', '_', $id);
        $id = str_replace(']', '', $id);
        $this->id = $id;
        $this->data['id'] = $id;
        return $this;
    }

    public function setNote($note ='') {
        $this->note = '<p style="color:#999;margin:5px 0 5px 0;">'.$note.'</p>';
        return $this;
    }

    public function setStart($start = null) {

        $this->start = $start;

        return $this;
    }

    public function setEnd($end = null) {

        $this->end = $end;

        return $this;
    }

    public function setValue($value ='') {
        $this->value = $value;
        return $this;
    }

    public function setOptions($options) {
        $this->options = $options;
        return $this;
    }

    public function setAttributes($attributes = []) {
        $this->attributes = $attributes;
        return $this;
    }

    public function getAttributes() {

        $attr = '';

        foreach ($this->attributes as $key => $value) {

            if($key == 'field') continue;

            if(!isset($this->data[$key])) $this->data[$key] = $value;

            if(!is_array($key) && !is_array($value)) $attr .= ' '.$key .' ="'.$value.'"';
        }

        $this->data['class'] = $this->class;

        return $attr;
    }

    public function getData() {

        $inputType = [
            'color','code', 'image','file', 'video','textarea','wysiwyg','wysiwyg-short', 'date','datetime', 'range','none','html'
        ];

        foreach ($this->attributes as $key => $value) {
            if(!isset($this->data[$key])) $this->data[$key] = $value;
        }

        $this->data['class'] = $this->class;

        if(isset($this->data['options']) && in_array($this->type, $inputType) !== true) {

            unset($this->data['options']);
        }

        $this->data['name'] = $this->name;

        if(empty($this->data['field'])) $this->data['field'] = $this->name;

        if($this->value != '') $this->data['value'] = set_value($this->name, $this->value);

        return $this->data;
    }

    public function render() {

        if($this->type != 'checkbox' && $this->type != 'radio') {
            $this->class .= ' form-control';
            $this->data['class'] = $this->class;
        }

        if(is_numeric($this->start) && empty($this->end)) {
            $this->start = '<div class="builder-col-'.$this->start.' col-md-'.$this->start.' form-group group">';
            $this->end = '</div>';
        }

        if($this->start === null && !empty($this->id) && $this->type != 'html') {
            $this->start = '<div class="col-md-12 form-group" id="box_'.$this->id.'">';
            if(!empty($this->label)) {
                if(($this->type == 'checkbox' || $this->type == 'radio') && !have_posts($this->options)) {
                    $this->start .= '';
                }
                else $this->start .= '<label for="'.$this->id.'" class="control-label">'.$this->label.'</label>';
            }

            $this->start .= '<div class="group">';

            if(empty($this->end)) {
                $this->end = '</div></div>';
            }
        }
        else {
            if(!empty($this->label)) {
                if(($this->type == 'checkbox' || $this->type == 'radio') && !have_posts($this->options)) {
                    $this->start .= '';
                }
                else $this->start .= '<label for="'.$this->id.'" class="control-label">'.$this->label.'</label>';
            }
        }

        if($this->type ==  'hidden') {
            $this->end   = '';
            $this->start    = '';
        }

        $this->output = $this->start;

        if(!empty($this->type)) {

            if($this->type == 'radio') {
                $this->radio();
            }
            else if($this->type == 'checkbox') {
                $this->checkbox();
            }
            else if($this->type == 'switch') {
                $this->switch();
            }
            else if($this->type == 'menu') {
                $this->menu();
            }
            else if($this->type == 'color') {
                $this->color();
            }
            else if($this->type == 'background') {
                $this->background();
            }
            else if($this->type == 'code') {
                $this->code();
            }
            else if($this->type == 'image' || $this->type == 'file' || $this->type == 'video') {
                $this->file();
            }
            else if($this->type == 'select' || $this->type == 'select2-multiple') {
                $this->select();
            }
            else if($this->type == 'select-img') {
                $this->selectImg();
            }
            else if($this->type == 'select-dropdown') {
                $this->selectDropdown();
            }
            else if($this->type == 'tab') {
                $this->selectTabs();
            }
            else if($this->type == 'popover-advance') {
                $this->popover_advance();
            }
            else if($this->type == 'gallery') {
                $this->gallery();
            }
            else if($this->type == 'gallery-item') {
                $this->galleryItem();
            }
            else if($this->type == 'popover') {
                $this->popover();
            }
            else if($this->type == 'textarea' || $this->type == 'wysiwyg' || $this->type == 'wysiwyg-short') {
                $this->textarea();
            }
            else if($this->type == 'date' || $this->type == 'datetime') {
                $this->datetime();
            }
            else if($this->type == 'daterange') {
                $this->daterange();
            }
            else if($this->type == 'range') {
                $this->range();
            }
            else if($this->type == 'repeater') {
                $this->repeater();
            }
            else if($this->type == 'widgetHeading') {
                $this->widgetHeading();
            }
            else if($this->type == 'textBuilding') {
                $this->textBuilding();
            }
            else if($this->type == 'page') {
                $this->page();
            }
            else if(str_starts_with($this->type, 'post_')) {
                $this->posts();
            }
            else if(str_starts_with($this->type, 'cate_')) {
                $this->post_category();
            }
            else if($this->type == 'none') {
                $this->output .= '';
            }
            else if($this->type == 'html') {
                $this->html();
            }
            else if($this->type == 'text') {
                $this->output .= form_input($this->getData());
            }
            else if($this->type == 'url') {
                $this->output .= form_input($this->getData());
            }
            else {

                $callback =  explode('::', $this->type);

                if(count($callback) == 2 && method_exists($callback[0], $callback[1])) {
                    $function = $this->type;
                    ob_start();
                    echo $function((object)$this->getData(), set_value($this->name, $this->value));
                    $this->output .= ob_get_contents();
                    ob_end_clean();
                }
                else if(function_exists('_form_'.$this->type)) {
                    $function = '_form_'.$this->type;
                    ob_start();
                    echo $function((object)$this->getData(), set_value($this->name, $this->value));
                    $this->output .= ob_get_contents();
                    ob_end_clean();
                }
                else {

                    $this->output .= form_input($this->getData());
                }
            }
        }

        $this->output .= $this->note;

        $this->output .= $this->end;

        return $this->output;
    }

    public function menu() {

        $menus = ThemeMenu::gets(Qr::set()->select('id', 'name', 'options'));

        $options = [];

        foreach ($menus as $key => $val) {
            $options[$val->id] = $val->name;
        }

        $this->type = 'select';

        $this->options = $options;

        return $this->select();
    }

    public function radio() {

        if(isset($this->attributes['single']) && $this->attributes['single']) {
            unset($this->attributes['single']);
            foreach ($this->options as $key => $data) {
                $this->output .= '<div class="radio">';
                $this->output .= '<label>';
                $this->output .= '<input type="radio" name="'.$this->name.'" id="'.$this->id.'" class="icheck '.$this->class.'" value="'.$key.'"';
                $this->output .= ($key == $this->value) ? 'checked' : '';
                $this->output .= ' '.$this->getAttributes().'>';
                $this->output .= "&nbsp;&nbsp;".$data;
                $this->output .= '</label>';
                $this->output .= '</div>';
            }
        }
        else if(isset($this->options) && is_array($this->options)) {

            foreach ($this->options as $key => $data) {
                $this->output .= '<div class="radio">';
                $this->output .= '<label>';
                $this->output .= '<input type="radio" name="'.$this->name.'[]" id="'.$this->id.'" class="icheck '.$this->class.'" value="'.$key.'"';
                if(is_array($this->value))
                    $this->output .= (in_array($key, $this->value))?'checked':'';
                else
                    $this->output .= ($key == $this->value)?'checked':'';
                $this->output .= ' '.$this->getAttributes().'>';
                $this->output .= "&nbsp;&nbsp;".$data;
                $this->output .= '</label>';
                $this->output .= '</div>';
            }
        }
        else {
            if(!isset($this->options)) $this->options = $this->id;
            $this->output = '<div class="radio">';
            $this->output .= '<label>';
            $this->output .= '<input type="radio" name="'.$this->name.'" id="'.$this->id.'" class="icheck '.$this->class.'" value="'.$this->options.'"';
            $this->output .= ($this->options == set_value($this->name, $this->value))?'checked':'';
            $this->output .= ' '.$this->getAttributes().'>';
            $this->output .= "&nbsp;&nbsp;".$this->label;
            $this->output .= '</label>';
            $this->output .= '</div>';
        }

        return $this;
    }

    public function checkbox() {

        if(isset($this->options) && is_array($this->options)) {

            foreach ($this->options as $key => $data) {
                $this->output .= '<div class="checkbox">';
                $this->output .= '<label>';
                $this->output .= '<input type="checkbox" name="'.$this->name.'[]" id="'.$this->id.'" class="icheck '.$this->class.'" value="'.$key.'"';
                if(isset($_POST[$this->name]) && $_POST[$this->name] != null) {
                    $this->output .= set_checkbox($this->name, $key);
                }
                else if(is_array($this->value))
                    $this->output .= (in_array($key, $this->value))?'checked':'';
                else
                    $this->output .= ($key == $this->value)?'checked':'';
                $this->output .= ' '.$this->getAttributes().'>';
                $this->output .= "&nbsp;&nbsp;".$data;
                $this->output .= '</label>';
                $this->output .= '</div>';
            }
        }
        else {

            if(!isset($this->options)) $this->options = $this->id;

            $this->output .= '<div class="checkbox">';
            $this->output .= '<label>';
            $this->output .= '<input type="checkbox" name="'.$this->name.'" id="'.$this->id.'" class="icheck '.$this->class.'" value="'.$this->options.'"';
            $this->output .= ($this->options == set_value($this->name, $this->value))?'checked':'';
            $this->output .= ' '.$this->getAttributes().'>';
            $this->output .= "&nbsp;&nbsp;".$this->label;
            $this->output .= '</label>';
            $this->output .= '</div>';
        }

        return $this;
    }

    public function selectImg() {
        ob_start();
        ?>
        <div class="select-img">
            <?php foreach ($this->options as $key => $data) { ?>
                <?php
                $checked = '';
                if(is_array($this->value)) {
                    $checked = (in_array($key, $this->value)) ? 'checked' : '';
                } else {
                    $checked = ($key == $this->value) ? 'checked' : '';
                }
                $imgUrl = (Str::isUrl($data['img']) || str_contains('http', $data['img']) || str_contains('https', $data['img'])) ? $data['img'] : Url::base().$data['img'];
                ?>
                <div class="checkbox">
                    <input style="opacity: 0;" id="<?php echo $this->id;?>_<?php echo $key;?>" type="radio" name="<?php echo $this->name;?>" value="<?php echo $key;?>" <?php echo $checked;?>>
                    <label for="<?php echo $this->id;?>_<?php echo $key;?>" class="<?php echo $this->class;?>" <?php echo $this->getAttributes();?>>
                        <span>&nbsp;&nbsp;<?php echo $data['label'];?></span>
                        <?php Template::img($imgUrl, '', ['style' => 'max-width:100px']);?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <?php
        $this->output .= ob_get_contents();
        ob_end_clean();
    }

    public function selectDropdown() {
        $value_name = '';
        if(!empty($this->value)) {
            if(!empty($this->options)) {
                $value_name = Arr::get((array)$this->options, $this->value.'.label');
                if(empty($value_name)) $value_name = Arr::get((array)$this->options, $this->value);
            }
        }
        if(!empty($value_name) && is_string($value_name)) $this->placeholder = $value_name;
        if(empty($this->placeholder_search)) $this->placeholder_search = 'tìm kiếm...';
        ob_start();
        ?>
        <div class="dropdown dropdown-custom-control js-dropdown-<?php echo $this->id;?>">
            <input type="text" name="<?php echo $this->name;?>" class="input-child form-control j-input-data" value="<?php echo $this->value;?>" style="display: none;">
            <button class="j-btn-dropdown btn btn-outline-secondary btn-block dropdown-toggle" type="button" id="<?php echo $this->id;?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $this->placeholder;?>
            </button>
            <div class="dropdown-filter dropdown-menu" aria-labelledby="<?php echo $this->name;?>" x-placement="top-start">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control filter-input" placeholder="<?php echo $this->placeholder_search;?>" <?php echo (!empty($this->ajax)) ? 'data-action="'.$this->ajax.'"' : '';?>>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search">&nbsp;</i></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row list-item">
                    <?php if(!empty($this->options)) {?>
                        <?php foreach ($this->options as $key => $label): ?>
                            <a href="<?php echo $key;?>" class="dropdown-item"><?php echo (is_string($label)) ? $label : $label['label'];?></a>
                        <?php endforeach ?>
                    <?php } ?>
                </div>
                <div class="dropdown-loading" style="display: none"><div class="col-md-12">Đang tìm...</div></div>
            </div>
        </div>
        <style>
            .dropdown-custom-control { min-width: 150px;}
            .dropdown-custom-control>button {
                background: none !important;
                color: #007bff !important;
                border:1px solid #dee2e6 !important;
                margin-left: 0;
                text-align: left;
                height: 37px; line-height: 37px; padding: 0 10px; outline: none;
                overflow: hidden;
            }
            .dropdown-custom-control>button::after {
                position: absolute;
                right: 13px;
                top: 45%;
            }
            .dropdown-custom-control .dropdown-toggle::after {
                display: inline-block;
                margin-left: .255em;
                vertical-align: .255em;
                content: "";
                border-top: .3em solid;
                border-right: .3em solid transparent;
                border-bottom: 0;
                border-left: .3em solid transparent;
            }
            .dropdown-custom-control>button:hover, .dropdown-custom-control>button:active, .dropdown-custom-control>button:focus {
                transform: scale(1);
                -webkit-transform: scale(1);
                -moz-transform: scale(1);
                -ms-transform: scale(1);
                -o-transform: scale(1);
                box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25) !important; outline: none;
            }
            .dropdown-custom-control .dropdown-menu {
                border-radius: 0 0 .25rem .25rem; width: 100%;
            }
            .dropdown-custom-control .dropdown-menu .input-group {
                position: relative;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                -ms-flex-align: stretch;
                align-items: stretch;
                width: 100%;
                padding:10px 0;
            }
            .dropdown-custom-control .dropdown-menu .input-group>.form-control {
                position: relative;
                -ms-flex: 1 1 auto;
                flex: 1 1 auto;
                width: 1%!important;
                margin-bottom: 0;
            }
            .dropdown-custom-control .dropdown-menu .input-group>.form-control:not(:last-child) {
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
            }
            .dropdown-custom-control .dropdown-menu .input-group-append {
                margin-left: -1px;
                display: -ms-flexbox;
                display: flex;
            }
            .dropdown-custom-control .dropdown-menu .input-group-text {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-align: center;
                align-items: center;
                padding: .375rem .75rem;
                margin-bottom: 0;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                text-align: center;
                white-space: nowrap;
                background-color: #e9ecef;
                border: 1px solid #ced4da;
                border-radius: .25rem;
            }
            .dropdown-custom-control .dropdown-menu .input-group>.input-group-append>.input-group-text {
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
            }
            .dropdown-custom-control .dropdown-menu .invalid-feedback {
                position: absolute;
                display: none;
                width: 100%;
                margin-top: .25rem;
                font-size: 80%;
                color: #dc3545;
            }
            .dropdown-custom-control .dropdown-menu .list-item {
                max-height: 170px;
                overflow: auto;
                margin-left: 0!important;
                margin-right: 0!important;
            }
            .dropdown-custom-control .dropdown-menu .list-item .dropdown-item {
                display: block;
                width: 100%;
                padding: .25rem 1.5rem;
                clear: both;
                font-weight: 400;
                color: #212529;
                text-align: inherit;
                /*white-space: nowrap;*/
                background-color: transparent;
                border: 0;
            }
            .dropdown-custom-control .dropdown-menu .list-item .dropdown-item:hover {
                color: #16181b;
                text-decoration: none;
                background-color: #f8f9fa;
            }
        </style>
        <script>
            $(function (){
                let dropdown_typingTimer;
                let dropdown_cache = [];
                let dropdown_result = [];
                function ChangeToSlug(string, key = '-') {

                    let slug;

                    //Đổi chữ hoa thành chữ thường
                    slug = string.toLowerCase();

                    //Đổi ký tự có dấu thành không dấu
                    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
                    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
                    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
                    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
                    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
                    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
                    slug = slug.replace(/đ/gi, 'd');
                    //Xóa các ký tự đặt biệt
                    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
                    //Đổi khoảng trắng thành ký tự gạch ngang
                    slug = slug.replace(/ /gi, key);
                    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
                    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
                    if (key == ' ') {
                        slug = slug.replace(/     /gi, key);
                        slug = slug.replace(/    /gi, key);
                        slug = slug.replace(/   /gi, key);
                        slug = slug.replace(/  /gi, key);
                        slug = '@' + slug + '@';
                        slug = slug.replace(/\@\| \@|\@/gi, '');
                    }
                    else {
                        slug = slug.replace(/\-\-\-\-\-/gi, key);
                        slug = slug.replace(/\-\-\-\-/gi, key);
                        slug = slug.replace(/\-\-\-/gi, key);
                        slug = slug.replace(/\-\-/gi, key);
                        slug = '@' + slug + '@';
                        slug = slug.replace(/\@\|\-\@|\@/gi, '');
                    }
                    slug = slug.replace(/ + /g," ");
                    slug = slug.trim();
                    //In slug ra textbox có id “slug”
                    return slug;
                }
                function dropdown_startToSearch(e) {

                    let keyword = e.val();

                    keyword = keyword.trim();

                    let box         = e.closest('.dropdown-custom-control');

                    let action      = decodeURIComponent(e.data('action'));

                    let id_dropdown = ChangeToSlug(box.find('.j-input-data').attr('name') + action + keyword);

                    let listItem = box.find('.list-item');

                    let loading = box.find('.dropdown-loading');

                    listItem.hide();

                    loading.show();

                    if (keyword.length === 0) {
                        loading.hide();
                        listItem.show();
                        listItem.html('<p>Không có kết quả</p>');
                        return false;
                    }

                    if (typeof dropdown_cache[id_dropdown] != 'undefined') {
                        loading.hide();
                        listItem.show();
                        let html = '';
                        $.each(dropdown_cache[id_dropdown] ,function(index, value){
                            if (typeof value.id == 'undefined') {
                                box.find('.list-item').html('<p class="col-md-12">Không có kết quả</p>');
                                return false;
                            }
                            html += '<a class="dropdown-item col-md-12" href="'+value.id+'">'+value.fullname+'</a>'
                        });
                        listItem.html(html);
                        return false;
                    } else {
                        let data = {
                            'keyword' : keyword,
                            'action'  : action,
                        };
                        $.get(ajax, data, function () { }, 'json').done(function (response) {
                            loading.hide();
                            listItem.show();
                            dropdown_cache[id_dropdown] = response.items;
                            let html = '';
                            $.each(response.items ,function(index, value){
                                if (typeof value.id == 'undefined') {
                                    box.find('.list-item').html('<p class="col-md-12">Không có kết quả</p>');
                                    return false;
                                }
                                html += '<a class="dropdown-item col-md-12" href="'+value.id+'">'+value.fullname+'</a>';
                            });
                            listItem.html(html);
                        });
                        return false;
                    }
                }
                $(document).on('click', '.dropdown-custom-control .list-item .dropdown-item', function () {
                    let dropdown = $(this).closest('.dropdown-custom-control');
                    let val = $(this).attr('href');
                    let txt = $(this).text();
                    dropdown.find('.j-btn-dropdown').text(txt);
                    dropdown.find('.j-input-data').val(val);
                    dropdown.removeClass('open');
                    dropdown.find('.j-input-data').trigger('change');
                    return false;
                });
                $(document).on('keyup', '.dropdown-custom-control input.filter-input', function (event) {

                    let self = $(this);

                    let keyword = self.val();

                    if (event.which === 13) {
                        if (keyword === "") {
                            return false;
                        }
                    } else {

                        let action = self.data('action');

                        if(action == '' || typeof action == 'undefined') {

                            let box  = self.closest('.dropdown-custom-control');

                            keyword = ChangeToSlug(keyword.toLowerCase());

                            $(".list-item a", box).filter(function(){
                                $(this).toggle(ChangeToSlug($(this).text().toLowerCase()).indexOf(keyword)>-1)
                            });
                        }
                        else {
                            let waitTyping = 500;
                            clearTimeout(dropdown_typingTimer);
                            dropdown_typingTimer = setTimeout(function () {
                                if (keyword !== "") {
                                    dropdown_startToSearch(self);
                                }
                            }, waitTyping);
                        }
                    }
                });

            });
        </script>
        <?php
        $this->output .= ob_get_contents();
        ob_end_clean();
    }

    public function switch() {
        $dataTrue = 1;
        $dataFalse = 0;
        if(!empty($this->options) && is_array($this->options) && count($this->options) == 2) {
            $dataTrue = $this->options[1];
            $dataFalse = $this->options[0];
        }
        ob_start();
        ?>
        <div class="toggleWrapper">
            <input name="<?php echo $this->name;?>" class="d-none switch-value <?php echo $this->class;?>" type="checkbox" value="<?php echo ((set_value($this->name, $this->value) == $dataTrue) ? $dataTrue : $dataFalse);?>" checked/>
            <div class="button" id="button-17">
                <input data-true="<?php echo $dataTrue;?>" data-false="<?php echo $dataFalse;?>" class="switch checkbox" type="checkbox" value="<?php echo ((set_value($this->name, $this->value) == $dataTrue) ? $dataTrue : $dataFalse);?>" <?php echo ((set_value($this->name, $this->value) == $dataTrue) ? 'checked' : '');?>/>
                <div class="knobs"><span></span></div>
                <div class="layer"></div>
            </div>
        </div>
        <?php

        $this->output .= ob_get_contents();

        ob_end_clean();

        return $this;
    }

    public function popover() {

        if(!Admin::is()) {
            $this->output .= notice('error', 'Input not support in frontend.');
            return $this;
        }

        if(!empty($this->attributes['module'])) {
            $module = $this->attributes['module'];
            unset($this->attributes['module']);
        }
        else $module = 'post_categories';

        if(!empty($this->attributes['key_type'])) {
            $key_type = $this->attributes['key_type'];
            unset($this->attributes['key_type']);
        }
        else $key_type = 'post_categories';

        if(isset($this->attributes['multiple'])) {
            if($this->attributes['multiple'] == 'true') {
                $multiple = true;
            }
            else if($this->attributes['multiple'] == 'false') {
                $multiple = false;
            }
            else  $multiple = (bool)$this->attributes['multiple'];
            unset($this->attributes['multiple']);
        }
        else $multiple = true;
        $value_tmp      = set_value($this->name, $this->value);
        $data           = array_merge(['options' => $this->options], $this->getData());
        $data['value']  = $value_tmp;
        $this->value    = $value_tmp;
        $value  = apply_filters('input_popover_'.$module.'_value', [], $data);
        if(!have_posts($value) && have_posts($this->value)) {
            foreach ($this->value as $key => $item) {
                $value[$key] = ['label' => '', 'image' => ''];
                if(have_posts($item)) {
                    $value[$key]['label'] = $item['label'];
                    $value[$key]['image'] = $item['image'];
                }
                else {
                    $value[$key]['label'] = $item;
                }
            }
        }
        if(isset($this->attributes['image'])) {

            if($this->attributes['image'] == 'true') {

                $image = true;
            }
            else if($this->attributes['image'] == 'false') {

                $image = false;
            }
            else  $image = (bool)$this->attributes['image'];

            unset($this->attributes['image']);
        }
        else $image = false;
        ob_start();
        ?>
        <div class="group input-popover-group" data-name="<?php echo $this->name;?>" id="<?php echo $this->id;?>" data-module="<?php echo $module;?>" data-key-type="<?php echo $key_type;?>" data-multiple="<?php echo ($multiple) ? 'true' : 'false' ;?>">
            <input type="text" class="form-control input-popover-search" placeholder="Tìm kiếm <?php echo $this->label;?>" />
            <div class="popover-content">
                <div class="popover__tooltip"></div>
                <div class="popover__scroll">
                    <ul class="popover__ul">
                        <?php if(have_posts($this->options)) {?>
                            <?php foreach ($this->options as $key => $label) { ?>
                                <li class="option option-<?php echo $key;?> <?php echo (have_posts($this->value) && in_array($key, $this->value) !== false)?'option--is-active':'';?>" data-key="<?php echo $key;?>">
                                    <a href="#">
                                        <span class="icon"><i class="fal fa-check"></i></span>
                                        <span class="label-option"><?php echo $label;?></span>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php  } ?>
                    </ul>
                    <div class="popover__loading text-center" style="display:none;"> Đang tải… </div>
                </div>
            </div>
            <div class="collections">
                <ul class="collection-list">
                    <?php if(have_posts($value)) {
                        foreach ($value as $key => $item) {
                            ?>
                            <li class="collection-list__li_<?php echo $key;?>">
                                <input type="checkbox" name="<?php echo $this->name;?><?php echo ($multiple) ?'[]':'';?>" value="<?php echo $key;?>" checked>
                                <div class="collection-list__grid">
                                    <div class="collection-list__cell ">
                                        <?php if($image == true) get_img($item['image']);?>
                                        <a href="#"><?php echo $item['label'];?></a>
                                    </div>
                                    <div class="collection-list__cell">
                                        <button class="ui-button collection-list-delete" data-key="<?php echo $key;?>"> <i class="fal fa-times"></i> </button>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    } ?>
                </ul>
            </div>
            <style>
                .input-popover-group, .page-content .box, .page-content .box .box-content {overflow:inherit;}
            </style>
        </div>
        <?php
        $this->output .= ob_get_contents();
        ob_end_clean();
        return $this;
    }

    public function popover_advance() {
        ob_start();
        $image = true;
        $loadData   = [];
        switch ($this->attributes['search']) {
            case 'post' :
                $action = 'Popover_Search::post';
                if(empty($this->attributes['taxonomy'])) $this->attributes['taxonomy'] = 'post';
                break;
            case 'category' :
                $action = 'Popover_Search::category';
                $image  = false;
                if(empty($this->attributes['taxonomy'])) $this->attributes['taxonomy'] = 'post_categories';
                break;
            case 'page' : $action = 'Popover_Search::page'; $image  = false; break;
            case 'user' : $action = 'Popover_Search::user'; $image  = false; break;
            default : $action = apply_filters('popover_advance_search_custom', $this->attributes['search']); break;
        }

        //taxonomy
        if(empty($this->attributes['taxonomy'])) $this->attributes['taxonomy'] = 'post';
        //multiple
        if(isset($this->attributes['multiple'])) {
            if($this->attributes['multiple'] == 'true') {
                $multiple = true;
            }
            else if($this->attributes['multiple'] == 'false') {
                $multiple = false;
            }
            else  $multiple = (bool)$this->attributes['multiple'];
            unset($this->attributes['multiple']);
        }
        else $multiple = true;

        if(!empty($this->value)) {
            if(is_numeric($this->value)) {
                $temp = [$this->value];
                $this->value = $temp;
            }
            $load       = '';
            if(empty($this->attributes['load'])) {
                switch ($this->attributes['search']) {
                    case 'post'     : $load = 'Popover_Search::loadPost'; break;
                    case 'category' : $load = 'Popover_Search::loadCategory'; break;
                    case 'page'     : $load = 'Popover_Search::loadPage'; break;
                    case 'user'     : $load = 'Popover_Search::loadUser'; break;
                    default : $load = apply_filters('popover_advance_load_custom', $this->attributes['search']); break;
                }
            }
            if(!empty($load)) {
                $loadData = call_user_func($load, $this->value, $this->attributes['taxonomy']);
            }
        }

        //Template
        if(empty($this->attributes['template'])) {
            $this->attributes['template'] = ($image == true) ? 'popover_advance_search_template' : 'popover_advance_search_template_not_image';
        }
        if(empty($this->attributes['template_load'])) {
            $this->attributes['template_load'] = ($image == true) ? 'popover_advance_load_template' : 'popover_advance_load_template_not_image';
        }
        ?>
        <div class="popover_advance" id="<?php echo $this->id;?>" data-name="<?php echo $this->name;?>" data-multiple="<?php echo $multiple;?>" data-template="<?php echo $this->attributes['template'];?>" data-template-load="<?php echo $this->attributes['template_load'];?>" data-load="<?php echo htmlspecialchars(json_encode($loadData));?>">
            <div class="popover_advance__box">
                <div>
                    <input type="text" class="form-control popover_advance__search" placeholder="" data-action="<?php echo $action;?>" data-taxonomy="<?php echo $this->attributes['taxonomy'];?>" autocomplete="off">
                </div>
                <div class="panel panel-default">
                    <div class="panel-body popover_advance__search__data scrollbar"></div>
                    <div class="panel-footer">
                        <div class="btn-group float-right">
                            <nav>
                                <ul class="pagination" data-page-current="1">
                                    <li class="pagination__item">
                                        <span class="pagination__link" data-type="prev">« Trước</span>
                                    </li>
                                    <li class="pagination__item">
                                        <a class="pagination__link" href="#" data-type="next">Sau »</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php echo Admin::loading();?>
                </div>
            </div>
            <div class="popover_advance__list"></div>
        </div>
        <?php
        $this->output .= ob_get_contents();
        ob_end_clean();
        return $this;
    }

    public function color() {

        $class = 'item-color';

        $display = 'input';

        if(!empty($this->attributes['display'])) {
            $display = $this->attributes['display'];
            unset($this->attributes['display']);
        }

        if(isset($this->attributes['format']) && $this->attributes['format'] == 'hexa') {
            $class = 'item-color-hexa';
            unset($this->attributes['format']);
        }

        $this->data['type'] = 'text';
        $this->output .= '<div class="input-group color-group '.(($display == 'inline') ? 'color-group-inline ' : ''). $class.'">';
        if($display == 'inline') $this->output .= '<div style="display:none">';
        $this->output .= form_input($this->getData(), set_value($this->name, $this->value));
        if($display == 'inline') {
            $this->output .= '</div>';
        }
        $this->output .= '</div>';

        return $this;
    }

    public function background() {
        $this->setType('text');
        ob_start();
        $color = (empty($this->value['color'])) ? '' : $this->value['color'];
        $gradientUse = (empty($this->value['gradientUse'])) ? 0 : 1;
        $gradientColor1 = (empty($this->value['gradientColor1'])) ? '#2b87da' : $this->value['gradientColor1'];
        $gradientColor2 = (empty($this->value['gradientColor2'])) ? '#29c4a9' : $this->value['gradientColor2'];
        $gradientType = (empty($this->value['gradientType'])) ? 'linear' : $this->value['gradientType'];
        $gradientRadialDirection1 = (empty($this->value['gradientRadialDirection1'])) ? 'center' : $this->value['gradientRadialDirection1'];
        $gradientRadialDirection2 = (empty($this->value['gradientRadialDirection2'])) ? '180' : $this->value['gradientRadialDirection2'];
        $gradientPositionStart = (empty($this->value['gradientPositionStart'])) ? 0 : $this->value['gradientPositionStart'];
        $gradientPositionEnd = (empty($this->value['gradientPositionEnd'])) ? 100 : $this->value['gradientPositionEnd'];

        $image = (empty($this->value['image'])) ? '' : $this->value['image'];
        $imageSize = (empty($this->value['imageSize'])) ? 'cover' : $this->value['imageSize'];
        $imagePosition = (empty($this->value['imagePosition'])) ? 'center center' : $this->value['imagePosition'];
        $imageRepeat = (empty($this->value['imageRepeat'])) ? 'no-repeat' : $this->value['imageRepeat'];
        ?>
        <div class="input-background-tab-box">
            <ul class="input-background-tab-navs">
                <li>
                    <button class="input-background-tab-navs-item active" data-tab="color" name="color">
                        <div class="input-background-tab-navs-item-icon">
                            <svg viewBox="0 0 28 28" preserveAspectRatio="xMidYMid meet" shape-rendering="geometricPrecision"><g><path d="M19.4 14.6c0 0-1.5 3.1-1.5 4.4 0 0.9 0.7 1.6 1.5 1.6 0.8 0 1.5-0.7 1.5-1.6C20.9 17.6 19.4 14.6 19.4 14.6zM19.3 12.8l-4.8-4.8c-0.2-0.2-0.4-0.3-0.6-0.3 -0.3 0-0.5 0.1-0.7 0.3l-1.6 1.6L9.8 7.8c-0.4-0.4-1-0.4-1.4 0C8 8.1 8 8.8 8.4 9.1l1.8 1.8 -2.8 2.8c-0.4 0.4-0.4 1-0.1 1.4l4.6 4.6c0.2 0.2 0.4 0.3 0.6 0.3 0.3 0 0.5-0.1 0.7-0.3l6.1-6.1C19.5 13.4 19.5 13.1 19.3 12.8zM15.6 14.6c-1.7 1.7-4.5 1.7-6.2 0l2.1-2.1 1 1c0.4 0.4 1 0.4 1.4 0 0.4-0.4 0.4-1 0-1.4l-1-1 0.9-0.9 3.1 3.1L15.6 14.6z" fill-rule="evenodd"></path></g></svg>
                        </div>
                    </button>
                </li>
                <li>
                    <button class="input-background-tab-navs-item" data-tab="gradient" name="gradient">
                        <div class="input-background-tab-navs-item-icon">
                            <svg viewBox="0 0 28 28" preserveAspectRatio="xMidYMid meet" shape-rendering="geometricPrecision"><g><path d="M22.9 7.5c-0.1-0.3-0.5-0.6-0.8-0.6H5.9c-0.4 0-0.7 0.2-0.8 0.6C5.1 7.6 5 7.7 5 7.9v12.2c0 0.1 0 0.2 0.1 0.4 0.1 0.3 0.5 0.5 0.8 0.6h16.2c0.4 0 0.7-0.2 0.8-0.6 0-0.1 0.1-0.2 0.1-0.4V7.9C23 7.7 23 7.6 22.9 7.5zM21 18.9L7 8.9h14V18.9z" fill-rule="evenodd"></path></g></svg>
                        </div>
                    </button>
                </li>
                <li>
                    <button class="input-background-tab-navs-item" data-tab="image" name="image">
                        <div class="input-background-tab-navs-item-icon">
                            <svg viewBox="0 0 28 28" preserveAspectRatio="xMidYMid meet" shape-rendering="geometricPrecision"><g><path d="M22.9 7.5c-0.1-0.3-0.5-0.6-0.8-0.6H5.9c-0.4 0-0.7 0.2-0.8 0.6C5.1 7.6 5 7.7 5 7.9v12.2c0 0.1 0 0.2 0.1 0.4 0.1 0.3 0.5 0.5 0.8 0.6h16.2c0.4 0 0.7-0.2 0.8-0.6 0-0.1 0.1-0.2 0.1-0.4V7.9C23 7.7 23 7.6 22.9 7.5zM21 18.9H7v-10h14V18.9z" fill-rule="evenodd"></path><circle cx="10.5" cy="12.4" r="1.5"></circle><polygon points="15 16.9 13 13.9 11 16.9 "></polygon><polygon points="17 10.9 15 16.9 19 16.9 "></polygon></g></svg>
                        </div>
                    </button>
                </li>
            </ul>
            <div class="input-background-tab-content">
                <div class="tab input-background-tab input-background-tab-color active">
                    <?php echo FormBuilder::render(['name' => $this->name.'[color]', 'type' => 'color', 'label' => 'Màu nền'], $color);?>
                </div>
                <div class="tab input-background-tab input-background-tab-gradient">
                    <div class="input-background-tab-gradient--review" style=""></div>
                    <div class="row">
                        <?php echo FormBuilder::render(['name' => $this->name.'[gradientUse]', 'type' => 'switch', 'label' => 'Sử dụng / tắt', 'after' => '<div class="builder-col-4 col-md-4 form-group group">', 'before'=> '</div>'], $gradientUse);
                        echo FormBuilder::render(['name' => $this->name.'[gradientColor1]', 'type' => 'color', 'label' => 'Màu 1', 'class' => 'gradientColor1', 'after' => '<div class="builder-col-4 col-md-4 form-group group">', 'before'=> '</div>'], $gradientColor1);
                        echo FormBuilder::render(['name' => $this->name.'[gradientColor2]', 'type' => 'color', 'label' => 'Màu 2', 'class' => 'gradientColor2', 'after' => '<div class="builder-col-4 col-md-4 form-group group">', 'before'=> '</div>'], $gradientColor2);
                        echo FormBuilder::render(['name' => $this->name.'[gradientType]', 'type' => 'select', 'label' => 'Gradient type', 'class' => 'gradientType', 'options' => ['linear' => 'Linear', 'radial' => 'Radial'], 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'], $gradientType);
                        echo FormBuilder::render(['name' => $this->name.'[gradientRadialDirection1]', 'type' => 'select', 'label' => 'Radial Direction', 'class' => 'gradientRadialDirection gradientRadialDirection1', 'options' => ['center' => 'Center', 'top left' => 'Top Left', 'top' => 'Top', 'top right' => 'Top Right', 'right' => 'Right', 'bottom right' => 'Bottom Right', 'bottom' => 'Bottom', 'bottom left' => 'Bottom Left', 'left' => 'Left'], 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'], $gradientRadialDirection1);
                        echo FormBuilder::render(['name' => $this->name.'[gradientRadialDirection2]', 'type' => 'range', 'label' => 'Radial Direction (dge)', 'class' => 'gradientRadialDirection gradientRadialDirection2', 'min' => 1, 'max' => 360, 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'], $gradientRadialDirection2);
                        echo FormBuilder::render(['name' => $this->name.'[gradientPositionStart]', 'type' => 'range', 'label' => 'Start Position', 'class' => 'gradientPositionStart', 'min' => 0, 'max' => 100, 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'], $gradientPositionStart);
                        echo FormBuilder::render(['name' => $this->name.'[gradientPositionEnd]', 'type' => 'range', 'label' => 'End Position', 'class' => 'gradientPositionEnd', 'min' => 0, 'max' => 100, 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'], $gradientPositionEnd);?>
                    </div>
                </div>
                <div class="tab input-background-tab input-background-tab-image">
                    <?php echo FormBuilder::render(['name' => $this->name.'[image]', 'type' => 'image', 'label' => 'Hình nền'], $image);
                    echo FormBuilder::render(['name' => $this->name.'[imageSize]', 'type' => 'tab', 'label' => 'Background Image Size', 'options' => ['cover' => 'Cover', 'contain' => 'Fit', 'inherit' => 'Actual Size'], 'value' => 'cover'], $imageSize);
                    echo FormBuilder::render(['name' => $this->name.'[imagePosition]', 'type' => 'select', 'label' => 'Background Image Position', 'options' => ['top left' => 'Phía trên bên trái', 'top center' => 'Phí trên chính giữa', 'top right' => 'Phía trên bên phải', 'center left' => 'Chính giữa bên trái', 'center center' => 'Canh giữa', 'center right' => 'Chính giữa bên phải', 'bottom left' => 'Phía dưới bên trái', 'bottom center' => 'Phía dưới chính giữa', 'bottom right' => 'Phía dưới bên phải']], $imagePosition);
                    echo FormBuilder::render(['name' => $this->name.'[imageRepeat]', 'type' => 'select', 'label' => 'Background Image Repeat', 'options' => ['no-repeat' => 'Không lặp lại', 'repeat' => 'Lặp lại', 'repeat-x' => 'Lặp lại (chiều ngang)', 'repeat-y' => 'Lặp lại (chiều dọc)', 'space' => 'Space', 'round' => 'Round']], $imageRepeat);?>
                </div>
            </div>
        </div>
        <?php
        $this->output .= ob_get_contents();
        ob_end_clean();
        return $this;
    }

    public function code() {

        if(!empty($this->attributes['rows'])) {

            $this->attributes['rows'] = 5;
        }

        if(!empty($this->attributes['language'])) {
            $this->class .= ' code-'.$this->attributes['language'];
            unset($this->attributes['language']);
        }

        $this->output .= '<div class="box-text-code-'.$this->id.'">';

        $this->output .= form_textarea($this->getData(), set_value($this->name, $this->value));

        $this->output .= '</div>';

        if(!empty($this->data['height'])) {

            $this->output .= '<style> .box-text-code-'.$this->id.' .CodeMirror { height: '.$this->data['height'].'px }</style>';
        }

        return $this;
    }

    public function file() {

        if($this->type == 'image') {

            $this->data['type'] = 'images';

            $type = 1;
        }
        if($this->type == 'file') {

            $this->data['type'] = 'files';

            $type = 2;
        }
        if($this->type == 'video') {

            $this->data['type'] = 'video';

            $type = 3;
        }
        $url = base_url().PLUGIN.'/rpsfmng/filemanager/dialog.php?type='.$type.'&subfolder=&editor=mce_0&field_id='.$this->id.'&callback=responsive_filemanager_callback';

        if($this->type == 'image' && !empty($this->attributes['display']) && $this->attributes['display'] == 'inline') {
            $this->setType('hidden');
            ob_start();
            ?>
            <div class="group fileupload-image iframe-btn" data-fancybox data-id="<?php echo $this->id;?>" href="<?php echo $url;?>" style="border: 1px dashed #ddd; cursor: pointer; width: 200px;max-width: 100%; overflow: hidden;">
                <div class="text-center">
                    <img src="views/backend/assets/images/image-upload.png" class="result-img" style="max-height: 150px; margin: 10px 0;">
                    <p class="mb-0 mt-2 text-secondary"><?php echo (!empty($this->label)) ? $this->label : 'Chọn ảnh';?></p>
                </div>
                <?php echo form_input($this->getData(), set_value($this->name, $this->value));?>
            </div>
            <?php
            $this->output .= ob_get_contents();
            ob_end_clean();
        }
        else {

            $icon = '<i class="fal fa-image"></i> Chọn Ảnh';

            if($this->type == 'file') $icon = '<i class="fal fa-image"></i> Chọn File';

            $this->output .= '<div class="input-group image-group">';

            $this->output .= form_input($this->getData(), set_value($this->name, $this->value));

            $this->output .= '<span class="input-group-addon input-file-addon iframe-btn" data-fancybox data-type="iframe" data-src="'.$url.'" data-id="'.$this->id.'" href="'.$url.'">'.$icon.'</span>';

            $this->output .= '</div>';
        }

        return $this;
    }

    public function widgetHeading() {
        $this->setType('text');
        ob_start();
        if(version_compare(get_instance()->data['template']->version, '2.7.0') < 0) {
            echo form_input($this->getData(), set_value($this->name, $this->value));
        }
        else {
            ?>
            <div class="input-group">
                <?php echo form_input($this->getData(), set_value($this->name, $this->value)); ?>
                <span class="input-group-addon input-file-addon iframe-btn" id="js_widget_heading_style"><i class="fal fa-heading"></i> Setting</span>
            </div>
            <div class="col-md-12 text-right" id="js_widget_heading_form_setting" style="cursor: pointer;"><i class="fad fa-cog"></i> Cấu hình tiêu đề</div>
            <?php
        }
        $this->output .= ob_get_contents();
        ob_end_clean();
        return $this;
    }

    public function textBuilding() {
        $this->setType('text');
        ob_start();
        if(version_compare(get_instance()->data['template']->version, '2.7.0') < 0) {
            echo form_input($this->getData(), set_value($this->name, $this->value));
        }
        else {
            $name = $this->name;
            $this->name = $this->name.'[txt]';
            $FormBuilder = new FormBuilder();
            $fonts 	= ['Font mặc định'];
            $fonts 	= array_merge($fonts, gets_theme_font());
            $valueTxt = (!empty($this->value['txt'])) ? $this->value['txt'] : '';
            $valueColor = (!empty($this->value['color'])) ? $this->value['color'] : '';
            $valueWeight = (!empty($this->value['fontWeight'])) ? $this->value['fontWeight'] : '';
            $valueSize = (!empty($this->value['fontSize'])) ? $this->value['fontSize'] : '';
            $valueFont = (!empty($this->value['fontFamily'])) ? $this->value['fontFamily'] : '';
            $valueLine = (!empty($this->value['lineHeight'])) ? $this->value['lineHeight'] : 0;
            $this->value = $valueTxt;
            ?>
            <div class="text-builder-container">
                <?php if(isset($this->attributes['txtInput']) && !$this->attributes['txtInput']) {?>
                    <div class="input-group">
                        <button class="btn btn-blue btn-block js_widget_text_style" style="width: 100%;" type="button"><i class="fa-duotone fa-pen-paintbrush"></i>&nbsp;Cấu hình</button>
                    </div>
                <?php } else {?>
                    <div class="input-group">
                        <?php echo form_input($this->getData(), set_value($this->name, $valueTxt)); ?>
                        <span class="input-group-addon input-file-addon iframe-btn js_widget_text_style"><i class="fa-duotone fa-pen-paintbrush"></i>&nbsp;Cấu hình</span>
                    </div>
                <?php } ?>

                <div class="text-builder-model">
                    <div class="row">
                        <?php
                        $FormBuilder->add($name.'[color]', 'color', [
                            'label' => 'Màu chữ',
                            'start' => 4,
                        ], $valueColor);
                        $FormBuilder->add($name.'[fontFamily]', 'select', [
                            'label' => 'Font chữ', 'options' => $fonts,
                            'start' => 8,
                        ], $valueFont);
                        $FormBuilder->add($name.'[lineHeight]', 'number', [
                            'label' => 'Line Height',
                            'start' => 4,
                        ], $valueLine);
                        $FormBuilder->add($name.'[fontWeight]', 'tab', [
                            'label' => 'In đậm', 'options' => [300 => '300', 400 => '400', 700 => '700', 'bold' => 'bold'],
                            'start' => 8,
                        ], $valueWeight);
                        $FormBuilder->add($name.'[fontSize]', 'tab', ['label' => 'Cở chữ',
                            'options' => ['10' => '10', '13' => '13',  '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '20' => '20',  '25' => '25', '30' => '30', '35' => '35',  '42' => '42', '50' => '50'],
                            'start' => 12,
                        ], $valueSize);
                        echo $FormBuilder->html();
                        ?>
                        <button class="btn btn-white js_widget_text_builder_close" type="button">Đóng</button>
                    </div>
                </div>
            </div>
            <style>
                .text-builder-container {
                    position:relative;
                }
                .text-builder-model {
                    display: none;
                    position:absolute;
                    background-color:#fff;
                    box-shadow:0 0 20px 0 #ccc; border-radius:16px;
                    padding:20px;
                    width:100%;
                    left:0;
                    top:50px;
                    z-index:80;
                    min-width:300px;
                }
            </style>
            <?php
        }
        $this->output .= ob_get_contents();
        ob_end_clean();
        return $this;
    }

    public function select() {

        if(!have_posts($this->options)) $this->options = [];

        if($this->type == 'select') {
            $this->output .= form_dropdown($this->name, $this->options, set_value($this->name, $this->value), ' class="'.$this->class.'" id="'.$this->id.'" '.$this->getAttributes());
        }

        if($this->type == 'select2-multiple') {

            $this->output .= form_multiselect($this->name.'[]', $this->options, set_value($this->name, $this->value), ' multiple class="select2-multiple '.$this->class.'" id="'.$this->id.'" '.$this->getAttributes());
        }

        return $this;
    }

    public function selectTabs() {

        if(!have_posts($this->options)) $this->options = [];

        ob_start();
        ?>
        <ul class="input-tabs with-indicator">
            <?php foreach ($this->options as $optionValue => $optionLabel) { ?>
                <?php
                if(is_array($this->value)) {
                    $checked = (in_array($optionValue, $this->value)) ? 'active' : '';
                } else {
                    $checked = ($optionValue == $this->value) ? 'active' : '';
                }
                ?>
                <li class="tab <?php echo $checked;?>" style="width:calc(100%/<?php echo count($this->options);?>)" data-width="<?php echo 100/count($this->options);?>">
                    <label for="<?php echo $this->id;?>_<?php echo Str::slug($optionValue);?>">
                        <input id="<?php echo $this->id;?>_<?php echo Str::slug($optionValue);?>" type="radio" name="<?php echo $this->name;?>" value="<?php echo $optionValue;?>" <?php echo ($checked == 'active') ? 'checked' : '';?>>
                        <?php echo $optionLabel;?>
                    </label>
                </li>
            <?php } ?>
            <div class="indicator" style="width:calc(100%/<?php echo count($this->options);?>)"></div>
        </ul>
        <?php
        $this->output .= ob_get_contents();

        ob_end_clean();

        return $this;
    }

    public function gallery() {

        $this->options = [];

        $gallery = Gallery::gets(Qr::set('object_type', 'gallery'));

        foreach ($gallery as $item) {
            $this->options[$item->id] = $item->name;
        }

        $this->output .= form_dropdown($this->name, $this->options, set_value($this->name, $this->value), ' class="'.$this->class.'" id="'.$this->id.'" '.$this->getAttributes());

        return $this;
    }

    public function galleryItem() {

        $data = $this->getData();

        if(Str::isSerialized($this->value)) $this->value = unserialize($this->value);

        ob_start();
        ?>
        <div class="gallery-object-box js_gallery_input_box" style="overflow:hidden" data-name="<?php echo $data['name'];?>">
            <div class="box-content collapse in">
                <div class="tab-content gallery-object-tabs">
                    <?php include Path::admin('function/gallery/html/gallery-input-tab.php', true);?>
                </div>
            </div>
        </div>
        <?php

        $this->output .= ob_get_contents();

        ob_end_clean();

        return $this;
    }

    public function textarea() {

        if($this->type == 'wysiwyg') {

            $this->class .= ' tinymce';
        }
        if($this->type == 'wysiwyg-short') {

            $this->class .= ' tinymce-shortcut';
        }

        if(empty($this->attributes['rows'])) $this->attributes['rows'] = 4;

        $data = $this->getData();

        if(($this->type == 'wysiwyg' || $this->type == 'wysiwyg-short') && isset($data['value'])) {
            $data['value'] = html_entity_decode($data['value']);
        }

        $this->output  .= form_textarea($this->getData(), set_value($this->name, $this->value));

        return $this;
    }

    public function datetime() {

        if($this->type == 'date') {

            $this->class .= ' datetime';

            $this->setType('text');
        }
        else {

            $this->class .= ' datetime';

            $this->data['data-time-format'] = 'hh:ii aa';

            $this->data['data-timepicker']  = 'true';

            $this->data['data-language']    = 'vi';
        }

        $this->output .= form_input($this->getData(), set_value($this->name, $this->value));

        return $this;
    }

    public function daterange() {
        $this->class .= ' daterange';
        $this->setType('text');
        $this->output .= form_input($this->getData(), set_value($this->name, $this->value));
        return $this;
    }

    public function range() {
        $this->class .= ' range-slider__range';
        $this->output .= '<div class="range-slider">';
        $this->output .= form_input($this->getData());
        $this->output .= '<span class="range-slider__value">0</span>';
        $this->output .= '</div>';
        return $this;
    }

    public function repeater() {

        $data = $this->getData();

        ob_start();

        $form = new FormBuilder();

        foreach ($data['fields'] as $item) {
            $name = $item['name'];
            $item['name'] = $data['name'].'[${id}]['.$item['name'].']';
            if(!empty($item['col'])) {
                $item['after']  = '<div class="col-md-'.$item['col'].'"><div class="group">';
                $item['before'] = '</div></div>';
            }
            $form->add($item['name'], $item['type'], $item);
            if(Language::hasMulti() && !empty($item['language'])) {
                $label = $item['label'];
                foreach (Language::list() as $langK => $langItem) {
                    if($langK == Language::default()) continue;
                    $item['name'] = $data['name'].'[${id}]['.$name.'_'.$langK.']';
                    if(!empty($item['label'])) $item['label'] = $label.'('.$langItem['label'].')';
                    $form->add($item['name'], $item['type'], $item);
                }
            }
        }

        $output = $form->html();
        ?>
        <div class="input_repeater_box" id="repeater_input_<?php echo $data['id'];?>">
            <?php if(have_posts($this->value)) {?>
                <?php foreach ($this->value as $itemID => $itemValue) {
                    ?>
                    <div class="store_wg_item row m-1">
                        <?php
                        $form = new FormBuilder();
                        foreach ($data['fields'] as $item) {
                            $value = '';
                            if(isset($itemValue[$item['name']])) {
                                $value = $itemValue[$item['name']];
                            }
                            $name  = $item['name'];
                            $item['name'] = $data['name'].'['.$itemID.']['.$item['name'].']';
                            if(!empty($item['col'])) {
                                $item['after']  = '<div class="col-md-'.$item['col'].'"><div class="group">';
                                $item['before'] = '</div></div>';
                            }
                            $form->add($item['name'], $item['type'], $item, $value);

                            if(Language::hasMulti() && !empty($item['language'])) {
                                $label = $item['label'];
                                foreach (Language::list() as $langK => $langItem) {
                                    if($langK == Language::default()) continue;
                                    if(isset($itemValue[$name.'_'.$langK])) {
                                        $value = $itemValue[$name.'_'.$langK];
                                    }
                                    $item['name'] = $data['name'].'['.$itemID.']['.$name.'_'.$langK.']';
                                    if(!empty($item['label'])) $item['label'] = $label.'('.$langItem['label'].')';
                                    $form->add($item['name'], $item['type'], $item, $value);
                                }
                            }
                        }
                        $form->html(false);
                        ?>
                        <div class="col-md-12">
                            <div class="mt-1">
                                <button class="btn btn-icon btn-red js_repeater_btn__delete" style="width:auto;"><?php echo Admin::icon('delete');?></button>
                            </div>
                        </div>
                    </div>
                    <?php
                } ?>
            <?php } ?>
        </div>
        <div class="store_wg_item text-right" style="padding:10px;">
            <button class="btn btn-green js_repeater_btn__add" data-id="repeater_input_<?php echo $data['id'];?>">Thêm mới</button>
        </div>
        <style>
            .store_wg_item {
                background-color: #ededed;
                border: 1px #ccc dashed;
                padding: 10px 0;
                clear: both;
                overflow: hidden;
                margin-bottom: 10px;
            }
        </style>

        <script id="template_repeater_input_<?php echo $data['id'];?>" type="text/x-custom-template">
            <div class="store_wg_item row m-1">
                <?php echo $output;?>
                <div class="col-md-12">
                    <div class="mt-1">
                        <button class="btn btn-icon btn-red js_repeater_btn__delete"><?php echo Admin::icon('delete');?></button>
                    </div>
                </div>
            </div>
        </script>
        <?php

        $this->output .= ob_get_contents();

        ob_end_clean();

        return $this;
    }

    public function page() {

        $page  = Pages::gets(Qr::set('trash', 0));

        if(have_posts($page)) {

            $options = [];

            foreach ($page as $key => $val) { $options[$val->id] = $val->title; }

            $this->output .= form_dropdown($this->name, $options, set_value($this->name, $this->value), ' class="'.$this->class.'" id="'.$this->id.'" '.$this->getAttributes());
        }

        return $this;
    }

    public function posts() {

        $postType= substr($this->type, 5);

        if(isset_post_type($postType)) {

            $post  = Posts::gets(Qr::set('post_type', $postType));

            if(have_posts($post)) {

                $options = [];

                foreach ($post as $key => $val) {
                    $options[$val->id] = $val->title;
                }

                $this->output .= form_dropdown($this->name, $options, set_value($this->name, $this->value), ' class="'.$this->class.'" id="'.$this->id.'" '.$this->getAttributes());
            }
        }

        return $this;
    }

    public function post_category() {

        $postType = substr($this->type, 5);

        if(isset_cate_type($postType)) {
            $categories    = PostCategory::gets(Qr::set('cate_type', $postType)->categoryType('options'));
            $categories[0] = 'Tất cả';
            if(have_posts($categories)) {
                $this->output .= form_dropdown($this->name, $categories, set_value($this->name, $this->value), ' class="'.$this->class.'" id="'.$this->id.'" '.$this->getAttributes());
            }
        }

        return $this;
    }

    public function html() {

        if(isset($this->attributes['html'])) $this->output .= $this->attributes['html'];

        return $this;
    }

    static function get($key = '', $args = ['clear' => true]) {
        return Request::get($key, $args);
    }

    static function post($key = '', $args = ['clear' => true]) {
        return Request::post($key, $args);
    }
}