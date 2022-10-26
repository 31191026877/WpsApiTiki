<?php
class widget_product_style_1 extends widget {
	function __construct() {
		parent::__construct('widget_product_style_1', 'Sản phẩm (style 1)', ['container' => true, 'position' => 'right']);
		add_action('theme_custom_css', array($this, 'css'), 10);
        add_action('theme_custom_script', array($this, 'script'), 10);
        $this->tags = ['products'];
        $this->author = 'SKDSoftware Dev Team';
	}
    public function form($left = [], $right = []) {
        $this->left
            ->add('pr_cate_id', 'product_categories', ['label' => 'Danh mục sản phẩm'])
            ->add('status', 'select', ['label' =>'Loại sản phẩm', 'options' => ['Sản phẩm mới','Sản phẩm yêu thích','Sản phẩm bán chạy','Sản phẩm nổi bật','Sản phẩm khuyến mãi']])
            ->add('display', 'widget_product_style_1::inputDisplay');
        $this->right
            ->add('limit', 'number', ['value' => 10, 'label'=> 'Số sản phẩm lấy ra', 'note'=>'Để 0 để lấy tất cả (không khuyên dùng)'])
            ->add('numberShow', 'col', ['label' =>'Số item / hàng', 'value' => 4, 'min'=> 1, 'max' => 5])
            ->add('numberShowTablet', 'col', ['label' =>'Số item / hàng (tablet)', 'value' => 3, 'min'=>1, 'max' => 5])
            ->add('numberShowMobile', 'col', ['label' =>'Số item / hàng (mobile)', 'value' => 2, 'min'=>1, 'max' => 5]);
        
		parent::form($left, $right);
	}
	public function widget() {
        $box = $this->container_box('widget_product_style_1 widget_product product-slider-horizontal');
        echo $box['before'];
        ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_'.$this->key.'_'.$this->id);?>
        <div id="product_style_1_content_<?= $this->id;?>" class="js_product_style_1_data" data-run="false" data-id="<?= $this->id;?>" data-category="<?= $this->options->pr_cate_id;?>" data-options="<?php echo htmlentities(json_encode($this->options));?>">
            <div class="box-content">
                <?php $this->loading();?>
                <?php if($this->options->display['type'] == 0) { ?>
                    <div class="arrow_box">
                        <div class="prev arrow"><i class="fal fa-chevron-left"></i></div>
                        <div class="next arrow"><i class="fal fa-chevron-right"></i></div>
                    </div>
                    <div class="swiper"><div class="swiper-wrapper list-product"></div></div>
                <?php }?>
                <?php if($this->options->display['type'] == 1) { ?>
                    <div id="widget_product_list_<?= $this->id;?>" class="list-product row"></div>
                <?php } ?>
            </div>
        </div>
        <style>
            .swiper { width: 100%; height: 100%; }
        </style>
        <?php
        echo $box['after'];
	}
    public function displayHorizontal($products) {
        if($this->options->display['rows'] == 1) {
            foreach ($products as $val):
                echo '<div class="swiper-slide">';
                echo Prd::template('loop/item_product', array('val' => $val));
                echo '</div>';
            endforeach;
        }
        if($this->options->display['rows'] == 2) {
            $rowKey = 0;
            foreach ($products as $val):
                if($rowKey == 0) echo '<div class="swiper-slide">';
                echo Prd::template('loop/item_product', array('val' => $val));
                $rowKey++;
                if($rowKey == 2) { echo '</div>'; $rowKey = 0; }
            endforeach;
            if($rowKey < 2) echo '</div>';
        }
    }
	public function displayList($products) {
        $this->options->numberShowMobile = ($this->options->numberShowMobile == 5)?15:(12/$this->options->numberShowMobile);
        $this->options->numberShowTablet = ($this->options->numberShowTablet == 5)?15:(12/$this->options->numberShowTablet);
        $this->options->numberShow        = ($this->options->numberShow == 5)?15:(12/$this->options->numberShow);
		foreach ($products as $item): ?>
            <div class="col-xs-<?php echo $this->options->numberShowMobile;?> col-sm-<?php echo $this->options->numberShowTablet;?> col-md-<?php echo $this->options->numberShow;?> col-lg-<?php echo $this->options->numberShow;?>">
                <?php echo Prd::template('loop/item_product', ['val' => $item]);?>
            </div>
        <?php endforeach;
	}
	public function loading() {
	    ?>
        <div class="wg-loading text-center">
            <div class="row">
                <div class="col-xs-6 col-sm-4 col-md-15"><?php $this->itemLoading();?></div>
                <div class="col-xs-6 col-sm-4 col-md-15"><?php $this->itemLoading();?></div>
                <div class="col-xs-6 col-sm-4 col-md-15 product--item-load-desktop"><?php $this->itemLoading();?></div>
                <div class="col-xs-6 col-sm-4 col-md-15 product--item-load-tablet"><?php $this->itemLoading();?></div>
                <div class="col-xs-6 col-sm-4 col-md-15 product--item-load-tablet"><?php $this->itemLoading();?></div>
            </div>
        </div>
        <?php
    }
    public function itemLoading() {
        ?>
        <div class="product--item-load">
            <div class="picture"></div>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 big"></div>
                <div class="col-xs-4 col-sm-4 col-md-4 empty big"></div>
                <div class="col-xs-2 col-sm-2 col-md-2 big"></div>
                <div class="col-xs-4 col-sm-4 col-md-4"></div>
                <div class="col-xs-8 col-sm-8 col-md-8 empty"></div>
                <div class="col-xs-6 col-sm-6 col-md-6"></div>
                <div class="col-xs-6 col-sm-6 col-md-6 empty"></div>
                <div class="col-xs-12 col-sm-12 col-md-12"></div>
            </div>
        </div>
        <?php
    }
    public function css() { include_once('assets/product-style-1.css'); }
    public function script() { include_once('assets/product-script-1.js'); }
    public function default() {
        if(!isset($this->options->pr_cate_id))  $this->options->pr_cate_id = 0;
        if(!isset($this->options->status))      $this->options->status = 0;
        if(empty($this->options->display)) $this->options->display = [];
        if(!isset($this->options->display['type'])) $this->options->display['type'] = 0;
        if(!isset($this->options->display['time'])) $this->options->display['time'] = 3;
        if(!isset($this->options->display['rows'])) $this->options->display['rows'] = 1;
        if(!isset($this->options->limit)) $this->options->limit = 10;
        if(!isset($this->options->numberShow)) $this->options->numberShow = 4;
        if(!isset($this->options->numberShowTablet)) $this->options->numberShowTablet = 3;
        if(!isset($this->options->numberShowMobile)) $this->options->numberShowMobile = 2;
        if(!isset($this->options->box))   $this->options->box = 'container';
    }
    static function loadProduct($ci, $model) {
        $result['status']   = 'error';
        $result['message']  = 'Lấy dữ liệu thất bại';
        if(Request::post()) {
            $widgetID       = (int)Request::post('widgetId');
            $categoryID     = (int)Request::post('categoryId');
            $widgetData = Widget::get($widgetID);
            if(have_posts($widgetData)) {
                $slug    =  Url::permalink(URL_PRODUCT);
                $widget = new widget_product_style_1();
                $widget->options = (object)unserialize($widgetData->options);
                $widget->default();
                $args = Qr::set('public',1)->where('trash', 0)->orderBy('order')->orderBy('created', 'desc')->limit((!empty($widget->options->limit)) ? $widget->options->limit : 20);
                if(!empty($widget->options->status)) {
                    if($widget->options->status == 4) {
                        $args->where('price_sale', '<>', 0);
                    }
                    else {
                        $args->where('status'.$widget->options->status, 1);
                    }
                }
                if(!empty($categoryID)) {
                    $category = ProductCategory::get($categoryID);
                    $args->whereByCategory($category);
                    if(have_posts($category)) $slug = Url::permalink($category->slug);
                }
                $products = Product::gets($args);
                $widget->id = $widgetID;
                ob_start();
                if($widget->options->display['type'] == 0) $widget->displayHorizontal($products);
                if($widget->options->display['type'] == 1) $widget->displayList($products);
                $result['item'] = ob_get_contents();
                ob_clean();
                ob_end_flush();
                $result['status']   = 'success';
                $result['slug']     = $slug;
            }
        }
        echo json_encode( $result );
    }
    static function inputDisplay($param, $value = []): bool|string {

        if(!is_array($value)) $value = [];
        if(!isset($value['type']))   $value['type'] = 0;
        if(!isset($value['time']))   $value['time'] = 3;
        if(!isset($value['rows']))   $value['rows'] = 1;

        $Form = new FormBuilder();

        ob_start();
        ?>
        <div class="js_input_tab_display">
            <ul class="input-tabs with-indicator" style="margin-bottom: 5px;">
                <li class="tab <?php echo ($value['type'] == 0) ? 'active' : '';?>" style="width:calc(100%/2)" data-tab="#display_slider">
                    <label for="display_type_0">
                        <input class="display_type" id="display_type_0" type="radio" name="display[type]" value="0" <?php echo ($value['type'] == 0) ? 'checked' : '';?>> Sản phẩm chạy ngang
                    </label>
                </li>
                <li class="tab <?php echo ($value['type'] == 1) ? 'active' : '';?>" style="width:calc(100%/2)" data-tab="#display_list">
                    <label for="display_type_1">
                        <input class="display_type" id="display_type_1" type="radio" name="display[type]" value="1" <?php echo ($value['type'] == 1) ? 'checked' : '';?>> Sản phẩm danh sách
                    </label>
                </li>
                <div class="indicator" style="width:calc(100%/2);"></div>
            </ul>
            <div class="tab-content">
                <div class="<?php echo ($value['type'] == 0) ? 'active in' : '';?> tab-pane fade" id="display_slider">
                    <div class="row m-1">
                        <?php $Form->add('display[time]', 'number', ['label' => 'Thời gian tự động chạy', 'value' => 3, 'step'=> '0.01', 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'], $value['time']);?>
                        <?php $Form->add('display[rows]', 'tab', ['label' => 'Số hàng', 'options' => [1 => '1 hàng', 2 => '2 hàng'], 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'], $value['rows']);?>
                        <?php $Form->html(false);?>
                    </div>
                </div>
                <div class="<?php echo ($value['type'] == 1) ? 'active in' : '';?> tab-pane fade" id="display_list"></div>
            </div>
        </div>
        <script defer>
            $('.js_input_tab_display li .display_type').click(function () {
                let tabID = $(this).closest('li').attr('data-tab');
                let tabBox = $(this).closest('.js_input_tab_display').find('.tab-content .tab-pane');
                tabBox.removeClass('active').removeClass('in');
                $(tabID).addClass('active').addClass('in');
                $('.input-tabs .tab.active').each(function(){
                    let inputBox = $(this).closest('.input-tabs');
                    inputTabsAnimation(inputBox, $(this));
                });
            });

        </script>
        <?php
        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }
}

Widget::add('widget_product_style_1');
Ajax::client('widget_product_style_1::loadProduct');

