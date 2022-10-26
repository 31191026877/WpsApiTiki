<?php
include 'include/active.php';
include 'include/helper-product.php';
include 'include/helper-suppliers.php';
include 'include/helper-brands.php';
include 'include/helper-old-version.php';
Class Prd {
    static function itemStyle($key = '') {
        $style = [
            'border' => ['style' => 'none', 'width' => '0', 'color' => '#fff', 'radius' => '0'],
            'shadow' => ['style' => 'none', 'horizontal' => '0', 'vertical' => '0', 'blur' => '0', 'spread' => '0', 'color' => ''],
            'shadowHover' => ['style' => 'none', 'horizontal' => '0', 'vertical' => '0', 'blur' => '0', 'spread' => '0', 'color' => ''],
            'img' => ['ratio_w' => '1', 'ratio_h' => '1', 'style' => 'cover', 'effect' => 'zoom'],
            'title' => [
                'position' => 10,
                'desktop' => ['show' => '1', 'color' => '#000', 'align' => 'center', 'font' => '','weight' => 'bold', 'size' => '16'],
                'mobile' => ['weight' => 'bold', 'size' => '14'],
            ],
            'price' => [
                'position' => 20,
                'desktop' => ['show' => '1', 'color' => '#fe0000', 'align' => 'center', 'font' => '','weight' => 'bold', 'size' => '16'],
                'mobile' => ['weight' => 'bold', 'size' => '14'],
            ]
        ];

        #border box
        $border = Option::get('productBoxBorder');
        if(isset($border['s'])) $style['border']['style']   = $border['s'];
        if(isset($border['w'])) $style['border']['width']   = (int)$border['w'];
        if(!empty($border['c'])) $style['border']['color']  = $border['c'];
        if(!empty($border['r'])) $style['border']['radius'] = $border['r'];

        #shadow
        $shadow = Option::get('productBoxShadow');
        if(isset($shadow['s'])) $style['shadow']['style']       = $shadow['s'];
        if(isset($shadow['h'])) $style['shadow']['horizontal']  = (int)$shadow['h'];
        if(isset($shadow['v'])) $style['shadow']['vertical']    = (int)$shadow['v'];
        if(isset($shadow['b'])) $style['shadow']['blur']        = (int)$shadow['b'];
        if(isset($shadow['sp'])) $style['shadow']['spread']      = (int)$shadow['sp'];
        if(!empty($shadow['c'])) $style['shadow']['color']      = $shadow['c'];

        #shadow
        $shadow = Option::get('productBoxShadowHover');
        if(isset($shadow['s'])) $style['shadowHover']['style']       = $shadow['s'];
        if(isset($shadow['h'])) $style['shadowHover']['horizontal']  = (int)$shadow['h'];
        if(isset($shadow['v'])) $style['shadowHover']['vertical']    = (int)$shadow['v'];
        if(isset($shadow['b'])) $style['shadowHover']['blur']        = (int)$shadow['b'];
        if(isset($shadow['sp'])) $style['shadowHover']['spread']     = (int)$shadow['sp'];
        if(!empty($shadow['c'])) $style['shadowHover']['color']      = $shadow['c'];

        #img
        $img = Option::get('productImg');
        if(isset($img['ratio_w']))   $style['img']['ratio_w'] = $img['ratio_w'];
        if(isset($img['ratio_h']))  $style['img']['ratio_h']  = $img['ratio_h'];
        if(isset($img['s']))   $style['img']['style']         = $img['s'];
        if(isset($img['e'])) $style['img']['effect']          = $img['e'];

        #title product
        $title = Option::get('productTitle');
        if(isset($title['position'])) $style['title']['position']             = $title['position'];
        if(isset($title['show']))     $style['title']['desktop']['show']      = $title['show'];
        if(isset($title['color']))    $style['title']['desktop']['color']     = $title['color'];
        if(isset($title['align']))    $style['title']['desktop']['align']     = $title['align'];
        if(isset($title['font']))     $style['title']['desktop']['font']      = $title['font'];
        if(isset($title['weight']))   $style['title']['desktop']['weight']    = $title['weight'];
        if(isset($title['size']))     $style['title']['desktop']['size']      = $title['size'];
        $title = Option::get('productTitleMobile');
        if(isset($title['weight']))   $style['title']['mobile']['weight']    = $title['weight'];
        if(isset($title['size']))     $style['title']['mobile']['size']      = $title['size'];

        #price
        $price = Option::get('productPrice');
        if(isset($price['position'])) $style['price']['position']      = $price['position'];
        if(isset($price['show']))   $style['price']['desktop']['show']      = $price['show'];
        if(isset($price['color']))  $style['price']['desktop']['color']     = $price['color'];
        if(isset($price['align']))  $style['price']['desktop']['align']     = $price['align'];
        if(isset($price['font']))   $style['price']['desktop']['font']      = $price['font'];
        if(isset($price['weight'])) $style['price']['desktop']['weight']    = $price['weight'];
        if(isset($price['size']))   $style['price']['desktop']['size']      = $price['size'];
        $price = Option::get('productPriceMobile');
        if(isset($price['weight'])) $style['price']['mobile']['weight']    = $price['weight'];
        if(isset($price['size']))   $style['price']['mobile']['size']      = $price['size'];

        $style = apply_filters('productItemStyle', $style);

        if(empty($key)) return $style;

        return Arr::get($style, $key);
    }
    static function priceUnit() {
        $productCurrency = Option::get('product_currency');
        return apply_filters('_price_currency', (isset($productCurrency[Language::current()]['unit'])) ? $productCurrency[Language::current()]['unit'] : 'đ');
    }
    static function priceNone() {
        $productPriceContact = Option::get('product_price_contact');
        return apply_filters('_price_none', (isset($productPriceContact[Language::current()])) ? $productPriceContact[Language::current()] : __('Liên hệ'));
    }
    static function price($price) {
        if(is_numeric($price)) $price = number_format($price);
        $productCurrency = Option::get('product_currency');
        $position = (isset($productCurrency[Language::current()]['position'])) ? $productCurrency[Language::current()]['position'] : 'after';
        if($position == 'after') $price = $price.self::priceUnit();
        if($position == 'before') $price = self::priceUnit().$price;
        return apply_filters('_price_render', $price);
    }
    static function template($templatePath = '' , $args = '', $return = false) {
        if($return) return Plugin::partial(PRODUCT_NAME, $templatePath, $args, $return);
        Plugin::partial(PRODUCT_NAME, $templatePath, $args, $return);
    }
    static function templateInclude($templatePath = '' , $args = '', $return = false) {
        $ci =& get_instance();
        extract($ci->data);
        if (!empty($args) && is_array($args)) extract( $args );
        $path = $ci->plugin->dir.'/'.PRODUCT_NAME.'/'.$templatePath.'.php';
        ob_start();
        include $path;
        if ($return === true) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            ob_end_flush();
            return $buffer;
        }
        ob_end_flush();
    }
}

if(!function_exists('_form_product_categories')) {
    function _form_product_categories($param, $value = '') {
        $output     = '';
        $options    = ProductCategory::gets(array('mutilevel' => 'option'));
        $options[0] = 'chọn danh mục';
        $output     .= form_dropdown($param->field, $options, set_value($param->field, $value), ' class="form-control '.$param->class.'" id="'.$param->id.'"');
        return $output;
    }
}
if(!function_exists('_form_product_suppliers')) {
    function _form_product_suppliers($param, $value = '') {
        $output     = '';
        $options    = gets_suppliers_option();
        $output     .= form_dropdown($param->field, $options, set_value($param->field, $value), ' class="form-control '.$param->class.'" id="'.$param->id.'"');
        return $output;
    }
}
