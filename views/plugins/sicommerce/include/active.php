<?php
Class ProductActivator {
    public static function activate(): void {
        self::table();
        self::role();
        self::module();
        self::option();
    }
    public static function table(): void {
        $model = model();
        if(!$model::schema()->hasTable('products')) {
            $model::schema()->create('products', function ($table) {
                $table->increments('id');
                $table->string('code', 50)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('title', 200)->collate('utf8mb4_unicode_ci');
                $table->string('slug', 200)->collate('utf8mb4_unicode_ci');
                $table->text('excerpt')->collate('utf8mb4_unicode_ci')->nullable();
                $table->longText('content')->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('image', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('seo_title', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->text('seo_description')->collate('utf8mb4_unicode_ci')->nullable();
                $table->text('seo_keywords')->collate('utf8mb4_unicode_ci')->nullable();
                $table->tinyInteger('public')->default(1);
                $table->integer('price')->default(0);
                $table->integer('price_sale')->default(0);
                $table->integer('supplier_id')->default(0);
                $table->integer('brand_id')->default(0);
                $table->integer('order')->default(0);
                $table->integer('user_created')->default(0);
                $table->integer('user_updated')->default(0);
                $table->dateTime('created');
                $table->dateTime('updated')->nullable();
                $table->string('theme_layout', 100)->nullable();
                $table->string('theme_view', 100)->nullable();
                $table->string('type', 100)->default('product');
                $table->tinyInteger('trash')->default(0);
                $table->string('status', 50)->collate('utf8mb4_unicode_ci')->default('public');
                $table->tinyInteger('status1')->default(0);
                $table->tinyInteger('status2')->default(0);
                $table->tinyInteger('status3')->default(0);
                $table->integer('parent_id')->default(0);
                $table->integer('weight')->default(0);
                $table->index('title');
                $table->index('slug');
            });
        }
        if(!$model::schema()->hasTable('products_categories')) {
            $model::schema()->create('products_categories', function ($table) {
                $table->increments('id');
                $table->string('name', 200)->collate('utf8mb4_unicode_ci');
                $table->string('slug', 200)->collate('utf8mb4_unicode_ci');
                $table->text('excerpt')->collate('utf8mb4_unicode_ci')->nullable();
                $table->longText('content')->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('image', 100)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('seo_title', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->text('seo_description')->collate('utf8mb4_unicode_ci')->nullable();
                $table->text('seo_keywords')->collate('utf8mb4_unicode_ci')->nullable();
                $table->tinyInteger('public')->default(1);
                $table->tinyInteger('status')->default(0);
                $table->integer('parent_id')->default(0);
                $table->integer('level')->default(0);
                $table->integer('lft')->default(0);
                $table->integer('rgt')->default(0);
                $table->integer('key')->default(0);
                $table->string('theme_layout', 100)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('theme_view', 100)->collate('utf8mb4_unicode_ci')->nullable();
                $table->integer('order')->default(0);
                $table->integer('user_created')->default(0);
                $table->integer('user_updated')->default(0);
                $table->dateTime('created');
                $table->dateTime('updated')->nullable();
                $table->index('name');
                $table->index('slug');
            });
        }
        if(!$model::schema()->hasTable('products_metadata')) {
            $model::schema()->create('products_metadata', function ($table) {
                $table->increments('id');
                $table->integer('object_id')->default(0);
                $table->string('meta_key', 100)->collate('utf8mb4_unicode_ci')->nullable();
                $table->longText('meta_value')->collate('utf8mb4_unicode_ci')->nullable();
                $table->integer('order')->default(0);
                $table->dateTime('created');
                $table->dateTime('updated')->nullable();
                $table->index('object_id');
                $table->index('meta_key');
            });
        }
        if(!$model::schema()->hasTable('suppliers')) {
            $model::schema()->create('suppliers', function ($table) {
                $table->increments('id');
                $table->string('name', 200)->collate('utf8mb4_unicode_ci');
                $table->string('firstname', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('lastname', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('email', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('phone', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('address', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('slug', 200)->collate('utf8mb4_unicode_ci');
                $table->string('image', 100)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('seo_title', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->text('seo_description')->collate('utf8mb4_unicode_ci')->nullable();
                $table->text('seo_keywords')->collate('utf8mb4_unicode_ci')->nullable();
                $table->integer('order')->default(0);
                $table->dateTime('created');
                $table->dateTime('updated')->nullable();
                $table->integer('user_created')->default(0);
                $table->integer('user_updated')->default(0);
                $table->index('slug');
            });
        }
        if(!$model::schema()->hasTable('brands')) {
            $model::schema()->create('brands', function ($table) {
                $table->increments('id');
                $table->string('name', 200)->collate('utf8mb4_unicode_ci');
                $table->text('excerpt')->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('slug', 200)->collate('utf8mb4_unicode_ci');
                $table->string('image', 100)->collate('utf8mb4_unicode_ci')->nullable();
                $table->string('seo_title', 200)->collate('utf8mb4_unicode_ci')->nullable();
                $table->text('seo_description')->collate('utf8mb4_unicode_ci')->nullable();
                $table->text('seo_keywords')->collate('utf8mb4_unicode_ci')->nullable();
                $table->integer('order')->default(0);
                $table->dateTime('created');
                $table->dateTime('updated')->nullable();
                $table->integer('user_created')->default(0);
                $table->integer('user_updated')->default(0);
                $table->index('slug');
            });
        }
    }
    public static function role(): void {
        $role = Role::get('root');
        $role->add_cap('product_list');
        $role->add_cap('product_edit');
        $role->add_cap('product_delete');
        $role->add_cap('product_cate_list');
        $role->add_cap('product_cate_edit');
        $role->add_cap('product_cate_delete');
        $role->add_cap('product_setting');

        $role = Role::get('administrator');
        $role->add_cap('product_list');
        $role->add_cap('product_edit');
        $role->add_cap('product_delete');
        $role->add_cap('product_cate_list');
        $role->add_cap('product_cate_edit');
        $role->add_cap('product_cate_delete');
        $role->add_cap('product_setting');
    }
    public static function module(): void {
        $module  = [
            'products.php'                      => PRODUCT_PATH.'admin/module/products.php',
            'products-categories.php'           => PRODUCT_PATH.'admin/module/products-categories.php',
        ];
        foreach ($module as $file_name => $file_path) {
            $file_new  = FCPATH.APPPATH.'controllers/backend/'.$file_name;
            $file_path = FCPATH.$file_path;
            if(file_exists($file_new)) unlink($file_new);
            if(file_exists($file_path)) {
                $handle     = file_get_contents($file_path);
                $file_new   = fopen($file_new, "w");
                fwrite($file_new, $handle);
                fclose($file_new);
            }
        }
    }
    public static function option(): void {
        $options = [
            'product_brands'             => 0, // version 2.1.0
            'product_supplier'           => 0, // version 2.0.5
            'product_currency'           => 'đ',
            'product_price_contact'      => __('Liên hệ', 'lien-he'),
            'product_pr_page'            => 16,
            'category_row_count'         => 4,
            'category_row_count_tablet'  => 3,
            'category_row_count_mobile'  => 2,
            'product_content'            => [],
            'product_sidebar'            => [],
            'product_version'            => PRODUCT_VERSION, // version 2.0.5
            'product_fulltext_search'    => false, // version 3.4.0
        ];

        foreach ($options as $option_key => $option_value) {
            Option::add($option_key, $option_value);
        }
    }
}

Class ProductDeactivator {
    public static function uninstall(): void {
        self::table();
        self::option();
    }
    public static function table(): void {
        $model = model();
        $model::schema()->drop('products');
        $model::schema()->drop('products_categories');
        $model::schema()->drop('products_metadata');
        $model::schema()->drop('suppliers');
        $model::schema()->drop('brands');
    }
    public static function option(): void {
        $options = [
            'product_brands'             => 0, // version 2.1.0
            'product_supplier'           => 0, // version 2.0.5
            'product_currency'           => 'đ',
            'product_price_contact'      => __('Liên hệ', 'lien-he'),
            'product_pr_page'            => 16,
            'category_row_count'         => 4,
            'category_row_count_tablet'  => 3,
            'category_row_count_mobile'  => 2,
            'product_content'            => [],
            'product_sidebar'            => [],
            'product_version'            => PRODUCT_VERSION, // version 2.0.5
            'product_fulltext_search'    => false, // version 3.4.0
        ];
        foreach ($options as $option_key => $option_value) {
            Option::delete($option_key);
        }
    }
}