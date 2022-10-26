<?php
Class ProductElementViewsCart {
    public function __construct() {
        add_filter('productItemStyle', 'ProductElementViewsCart::customProductItemStyle');
        if(Admin::is()) {
            add_action('admin_product_setting_object', 'ProductElementViewsCart::adminConfigHtml', 60);
            Metabox::add('admin_product_element_views', 'Lượt xem tùy chỉnh', 'ProductElementViewsCart::viewsMetaBoxForm', ['module' => 'products']);
            add_action('save_object', 'ProductElementViewsCart::saveViews', 10, 2);
        }
        else {
            add_action('cle_header', 'ProductElementViewsCart::cssVariables');
            add_action('theme_custom_less', 'ProductElementViewsCart::less');
            add_action('cle_footer', 'ProductElementViewsCart::autoPlusView');
        }
        if(Prd::itemStyle('viewsCart.style') == 'inline') {
            add_action('product_object_info', 'ProductElementViewsCart::renderInlineBlock', 60);
        }
        else {
            add_action('product_object_info', 'ProductElementViewsCart::renderViews', Prd::itemStyle('viewsCart.views.position'));
            add_action('product_object_info', 'ProductElementViewsCart::renderCart', Prd::itemStyle('viewsCart.cart.position'));
        }
    }
    public static function adminConfigHtml(): void {
        $viewCart = Prd::itemStyle('viewsCart');
        Plugin::partial(PR_EL_NAME, 'modules/views_cart/views/admin/config', ['views' => $viewCart['views'], 'cart' => $viewCart['cart'], 'style' => $viewCart['style']]);
    }
    public static function customProductItemStyle($style) {
        $style['viewsCart'] = [
            'style' => 'block',
            'views' => [
                'show' => false, 'position' => 40,
                'color' => '#000',
                'borderColor' => '#000',
                'borderWidth' => '1',
                'bg' => '#fff',
                'colorHover' => '#fff',
                'borderColorHover' => Option::get('theme_color'),
                'bgHover' => Option::get('theme_color'),
                'align' => 'center', 'font' => '','weight' => 'bold', 'size' => '12'
            ],
            'cart' => [
                'show' => false, 'position' => 40,
                'color' => '#000',
                'borderColor' => '#000',
                'borderWidth' => '1',
                'bg' => '#fff',
                'colorHover' => '#fff',
                'borderColorHover' => Option::get('theme_color'),
                'bgHover' => Option::get('theme_color'),
                'align' => 'center', 'font' => '','weight' => 'bold', 'size' => '12'
            ]
        ];

        $viewsCart = Option::get('productViewsCart');

        if(isset($viewsCart['style'])) $style['viewsCart']['style']  = $viewsCart['style'];

        if(isset($viewsCart['views']['position']))       $style['viewsCart']['views']['position']  = $viewsCart['views']['position'];
        if(isset($viewsCart['views']['show']))           $style['viewsCart']['views']['show']      = $viewsCart['views']['show'];
        if(isset($viewsCart['views']['color']))          $style['viewsCart']['views']['color']     = $viewsCart['views']['color'];
        if(isset($viewsCart['views']['borderColor']))    $style['viewsCart']['views']['borderColor']     = $viewsCart['views']['borderColor'];
        if(isset($viewsCart['views']['borderWidth']))    $style['viewsCart']['views']['borderWidth']     = $viewsCart['views']['borderWidth'];
        if(isset($viewsCart['views']['bg']))             $style['viewsCart']['views']['bg']     = $viewsCart['views']['bg'];
        if(isset($viewsCart['views']['colorHover']))          $style['viewsCart']['views']['colorHover']     = $viewsCart['views']['colorHover'];
        if(isset($viewsCart['views']['borderColorHover']))    $style['viewsCart']['views']['borderColorHover']     = $viewsCart['views']['borderColorHover'];
        if(isset($viewsCart['views']['bgHover']))             $style['viewsCart']['views']['bgHover']     = $viewsCart['views']['bgHover'];
        if(isset($viewsCart['views']['align']))    $style['viewsCart']['views']['align']     = $viewsCart['views']['align'];
        if(isset($viewsCart['views']['font']))     $style['viewsCart']['views']['font']      = $viewsCart['views']['font'];
        if(isset($viewsCart['views']['weight']))   $style['viewsCart']['views']['weight']    = $viewsCart['views']['weight'];
        if(isset($viewsCart['views']['size']))     $style['viewsCart']['views']['size']      = $viewsCart['views']['size'];

        if(isset($viewsCart['cart']['position'])) $style['viewsCart']['cart']['position']  = $viewsCart['cart']['position'];
        if(isset($viewsCart['cart']['show']))     $style['viewsCart']['cart']['show']      = $viewsCart['cart']['show'];
        if(isset($viewsCart['cart']['color']))    $style['viewsCart']['cart']['color']     = $viewsCart['cart']['color'];
        if(isset($viewsCart['cart']['borderColor']))    $style['viewsCart']['cart']['borderColor']     = $viewsCart['cart']['borderColor'];
        if(isset($viewsCart['cart']['borderWidth']))    $style['viewsCart']['cart']['borderWidth']     = $viewsCart['cart']['borderWidth'];
        if(isset($viewsCart['cart']['bg']))             $style['viewsCart']['cart']['bg']     = $viewsCart['cart']['bg'];
        if(isset($viewsCart['cart']['colorHover']))          $style['viewsCart']['cart']['colorHover']     = $viewsCart['cart']['colorHover'];
        if(isset($viewsCart['cart']['borderColorHover']))    $style['viewsCart']['cart']['borderColorHover'] = $viewsCart['cart']['borderColorHover'];
        if(isset($viewsCart['cart']['bgHover']))             $style['viewsCart']['cart']['bgHover']     = $viewsCart['cart']['bgHover'];
        if(isset($viewsCart['cart']['align']))    $style['viewsCart']['cart']['align']     = $viewsCart['cart']['align'];
        if(isset($viewsCart['cart']['font']))     $style['viewsCart']['cart']['font']      = $viewsCart['cart']['font'];
        if(isset($viewsCart['cart']['weight']))   $style['viewsCart']['cart']['weight']    = $viewsCart['cart']['weight'];
        if(isset($viewsCart['cart']['size']))     $style['viewsCart']['cart']['size']      = $viewsCart['cart']['size'];

        return $style;
    }
    /*===================================
     * Xử lý metabox
    ===================================*/
    public static function viewsMetaBoxForm($object): void {
        $view = (have_posts($object)) ? (int)Product::getMeta($object->id, 'views', true) : 0;
        $Form = new FormBuilder();
        $Form->add('views', 'number', ['label' => 'Lượt xem sản phẩm', 'value' => 0], $view)->html(false);
    }
    public static function saveViews($product_id, $module): void {
        if($module == 'products') {
            $product_views = (int)Request::Post('views');
            Product::updateMeta($product_id, 'views', $product_views);
        }
    }
    /****===================================
     * Render frontend
    ===================================*/
    static function renderInlineBlock($object): void {
        echo '<div class="pre-box-views-cart">';
        ProductElementViewsCart::renderViews($object);
        ProductElementViewsCart::renderCart($object);
        echo '</div>';
    }
    static function renderViews($object): void {
        if(Prd::itemStyle('viewsCart.views.show') == 1) {
            $view = (int)Product::getMeta($object->id, 'views', true);
            Plugin::partial(PR_EL_NAME, 'modules/views_cart/views/html-views', ['view' => $view]);
        }
    }
    static function renderCart($object): void {
        if(Prd::itemStyle('viewsCart.cart.show') == 1) {
            $count = Product::count(Qr::set('parent_id', $object->id)->where('type', 'variations'));
            Plugin::partial(PR_EL_NAME, 'modules/views_cart/views/html-cart', ['count' => $count, 'object' => $object]);
        }
    }

    static function cssVariables(): void {
        $configViews = Prd::itemStyle('viewsCart.views');
        if($configViews['show'] == 1) {
        ?>
        <style>
            :root {
                --pre-views-bg         : <?php echo (!empty($configViews['bg'])) ? $configViews['bg'] : 'var(--theme-color)';?>;
                --pre-views-color      : <?php echo $configViews['color'];?>;
                --pre-views-border     : <?php echo (!empty($configViews['borderColor'])) ? $configViews['borderColor'] : 'var(--theme-color)';?>;
                --pre-views-border-width     : <?php echo (!empty($configViews['borderWidth'])) ? $configViews['borderWidth'] : '0';?>px;
                --pre-views-bg-hover         : <?php echo (!empty($configViews['bgHover'])) ? $configViews['bgHover'] : 'var(--theme-color)';?>;
                --pre-views-color-hover      : <?php echo $configViews['colorHover'];?>;
                --pre-views-border-hover     : <?php echo (!empty($configViews['borderColorHover'])) ? $configViews['borderColorHover'] : 'var(--theme-color)';?>;
                --pre-views-font       : <?php echo (!empty($configViews['font'])) ? $configViews['font'] : 'var(--font-family)';?>;
                --pre-views-size       : <?php echo $configViews['size'];?>px;
                --pre-views-weight       : <?php echo $configViews['weight'];?>px;
                --pre-views-align       : <?php echo $configViews['align'];?>;
            }
        </style>
        <?php
        }
        $configCart = Prd::itemStyle('viewsCart.cart');
        if($configCart['show'] == 1) {
            ?>
            <style>
                :root {
                    --pre-cart-bg         : <?php echo (!empty($configCart['bg'])) ? $configCart['bg'] : 'var(--theme-color)';?>;
                    --pre-cart-color      : <?php echo $configCart['color'];?>;
                    --pre-cart-border     : <?php echo (!empty($configCart['borderColor'])) ? $configCart['borderColor'] : 'var(--theme-color)';?>;
                    --pre-cart-border-width     : <?php echo (!empty($configCart['borderWidth'])) ? $configCart['borderWidth'] : '0';?>px;
                    --pre-cart-bg-hover         : <?php echo (!empty($configCart['bgHover'])) ? $configCart['bgHover'] : 'var(--theme-color)';?>;
                    --pre-cart-color-hover      : <?php echo $configCart['colorHover'];?>;
                    --pre-cart-border-hover     : <?php echo (!empty($configCart['borderColorHover'])) ? $configCart['borderColorHover'] : 'var(--theme-color)';?>;
                    --pre-cart-font       : <?php echo (!empty($configCart['font'])) ? $configCart['font'] : 'var(--font-family)';?>;
                    --pre-cart-size       : <?php echo $configCart['size'];?>px;
                    --pre-cart-weight       : <?php echo $configCart['weight'];?>px;
                    --pre-cart-align       : <?php echo $configCart['align'];?>;
                }
            </style>
            <?php
        }
    }

    static function less(): void {
        include PR_EL_PATH.'/modules/views_cart/views/style-element.less';
    }

    static function autoPlusView(): void {
        if(Template::isPage('products_detail')) {
            $object = get_object_current('object');
            if(have_posts($object)) {
                $views = (int)Product::getMeta($object->id, 'views', true);
                Product::updateMeta($object->id, 'views', $views+1);
            }
        }
    }
}

new ProductElementViewsCart();