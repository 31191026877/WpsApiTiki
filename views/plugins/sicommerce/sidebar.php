<?php
Class ProductSidebar {

    static public function category() {

        $heading = sicommerce::config('product_sidebar.category.title');

        if(Language::current() != Language::default()) {
            $heading = sicommerce::config('product_sidebar.category.title_'.Language::current());
        }
        $categories = ProductCategory::Gets(Qr::set()->categoryType('multilevel')->select('id', 'name', 'slug', 'level'));
        Prd::template('sidebar/widget_category', ['heading' => apply_filters('product_sidebar_category_heading', $heading), 'categories' => $categories]);
    }

    static public function selling() {

        $heading = sicommerce::config('product_sidebar.selling.title');

        if(Language::current() != Language::default()) {
            $heading = sicommerce::config('product_sidebar.selling.title_'.Language::current());
        }

        $args = [
            'where' => ['status2' => 1],
            'params' => ['limit' => 5]
        ];

        $args = apply_filters('product_sidebar_selling', $args);

        $products = Product::gets($args);

        Prd::template('sidebar/widget_product', ['heading' => apply_filters('product_sidebar_selling', $heading), 'products' => $products]);
    }

    static public function hot() {
        $heading = sicommerce::config('product_sidebar.hot.title');
        if(Language::current() != Language::default()) {
            $heading = sicommerce::config('product_sidebar.hot.title_'.Language::current());
        }
        $args = [
            'where' => ['status3' => 1],
            'params' => ['limit' => 5]
        ];

        $args = apply_filters('product_sidebar_hot', $args);

        $products = Product::gets($args);

        Prd::template('sidebar/widget_product', ['heading' => apply_filters('product_sidebar_hot', $heading), 'products' => $products]);
    }

    static public function sale() {
        $heading = sicommerce::config('product_sidebar.sale.title');
        if(Language::current() != Language::default()) {
            $heading = sicommerce::config('product_sidebar.sale.title_'.Language::current());
        }
        $args = [
            'where' => ['price_sale <>' => 0],
            'params' => ['limit' => 5]
        ];

        $args = apply_filters('product_sidebar_sale', $args);

        $products = Product::gets($args);

        Prd::template('sidebar/widget_product', ['heading' => apply_filters('product_sidebar_sale', $heading), 'products' => $products]);
    }
}
if(Template::isPage('products_index')) {
    if(!empty(sicommerce::config('product_sidebar.category.enable'))) {
        add_action('theme_sidebar', 'ProductSidebar::category', sicommerce::config('product_sidebar.category.order'));
    }
    if(!empty(sicommerce::config('product_sidebar.selling.enable'))) {
        add_action('theme_sidebar', 'ProductSidebar::selling', sicommerce::config('product_sidebar.selling.order'));
    }
    if(!empty(sicommerce::config('product_sidebar.hot.enable'))) {
        add_action('theme_sidebar', 'ProductSidebar::hot', sicommerce::config('product_sidebar.hot.order'));
    }
    if(!empty(sicommerce::config('product_sidebar.sale.enable'))) {
        add_action('theme_sidebar', 'ProductSidebar::sale', sicommerce::config('product_sidebar.sale.order'));
    }
}
