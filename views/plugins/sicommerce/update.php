<?php
if(!Admin::is()) return;
use Illuminate\Database\Capsule\Manager as DB;
function Product_update_core() {
    if(Admin::is() && Auth::check()) {
        $cart_version = Option::get('product_version');
        if(empty($cart_version)) {
            $cart_version = Option::get('wcmc_version');
        }
        if (version_compare(PRODUCT_VERSION, $cart_version) === 1) {
            $update = new Product_Update_Version();
            $update->runUpdate($cart_version);
            Option::update('product_version', PRODUCT_VERSION);
        }
    }
}
add_action('admin_init', 'Product_update_core');

Class Product_Update_Version {
    public function runUpdate($cartVersion) {
        $listVersion    = ['1.9.0', '2.0.0', '2.0.1', '2.0.4', '2.0.5', '2.1.0', '2.2.0', '3.0.0', '3.2.0', '3.3.0', '3.5.0', '3.6.0', '3.7.0'];
        $model          = get_model();
        foreach ($listVersion as $version ) {
            if(version_compare( $version, $cartVersion ) == 1) {
                $function = 'update_Version_'.str_replace('.','_',$version);
                if(method_exists($this, $function)) $this->$function($model);
            }
        }
        Option::update('product_version', PRODUCT_VERSION );
    }
    public function update_Version_1_9_0($model) {
        Product_Update_Role::Version_1_9_0($model);
    }
    public function update_Version_2_0_0($model) {
        Product_Update_Database::Version_2_0_0($model);
        Product_Update_Role::Version_2_0_0($model);
        Product_Update_Files::Version_2_0_0($model);
    }
    public function update_Version_2_0_1($model) {
        Product_Update_Database::Version_2_0_1($model);
    }
    public function update_Version_2_0_4($model) {
        Product_Update_Database::Version_2_0_4($model);
    }
    public function update_Version_2_0_5($model) {
        Product_Update_Database::Version_2_0_5($model);
    }
    public function update_Version_2_1_0($model) {
        Product_Update_Database::Version_2_1_0($model);
    }
    public function update_Version_2_2_0($model) {
        Product_Update_Database::Version_2_2_0($model);
    }
    public function update_Version_3_0_0($model) {
        Product_Update_Files::Version_3_0_0($model);
    }
    public function update_Version_3_2_0($model) {
        Product_Update_Database::Version_3_2_0($model);
        Product_Update_Role::Version_3_2_0($model);
    }
    public function update_Version_3_3_0($model) {
        Product_Update_Files::Version_3_3_0($model);
    }
    public function update_Version_3_5_0($model) {
        Product_Update_Database::Version_3_5_0($model);
        Product_Update_Files::Version_3_5_0($model);
    }
    public function update_Version_3_6_0($model) {
        Product_Update_Database::Version_3_6_0($model);
    }
    public function update_Version_3_7_0($model) {
        Product_Update_Database::Version_3_7_0($model);
        Product_Update_Files::Version_3_7_0($model);
    }
}
Class Product_Update_Database {
    public static function Version_2_0_0($model) {
        if(!model()::schema()->hasTable('suppliers')) {
            $model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."suppliers` (
                `id` INT NOT NULL AUTO_INCREMENT, 
                `name` VARCHAR(255) NOT NULL, 
                `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `firstname` VARCHAR(255) NULL, 
                `lastname` VARCHAR(255) NULL, 
                `email` VARCHAR(255) NULL, 
                `phone` VARCHAR(255) NULL, 
                `address` VARCHAR(255) NULL, 
                `created` DATETIME NULL, 
                `updated` DATETIME NULL,
                `user_created` int(11) DEFAULT NULL,
                `user_updated` int(11) DEFAULT NULL,
                `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `seo_description` text COLLATE utf8mb4_unicode_ci,
                `seo_keywords` text COLLATE utf8mb4_unicode_ci,
                `order` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

            if(!model()::schema()->hasColumn('products', 'supplier_id')) {
                $model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `supplier_id` INT NOT NULL DEFAULT '0' AFTER `price_sale`;");
            }
        }
        if(!model()::schema()->hasColumn('products', 'code')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `code` VARCHAR(255) NULL AFTER `id`;");
        }
    }
    public static function Version_2_0_1($model) {
        if(!model()::schema()->hasColumn('products', 'type')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `type` VARCHAR(255) NULL DEFAULT 'product' AFTER `status3`;");
            $model->query("UPDATE `".CLE_PREFIX."products` SET `type`= 'product' WHERE 1;");
        }
    }
    public static function Version_2_0_4($model) {
        if(!model()::schema()->hasColumn('products', 'weight')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `weight` INT NULL DEFAULT 0 AFTER `status3`;");
        }
    }
    public static function Version_2_0_5($model) {
        $supplier = Suppliers::count();
        if($supplier != 0) {
            option::update('woocommerce_supplier', 1);
        }
        else {
            option::update('woocommerce_supplier', 0);
        }
    }
    public static function Version_2_1_0($model) {
        Option::update('woocommerce_brands', 0);
        if(!model()::schema()->hasTable('brands')) {
            $model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."brands` (
                `id` INT NOT NULL AUTO_INCREMENT, 
                `name` VARCHAR(255) NOT NULL, 
                `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `created` DATETIME NULL, 
                `updated` DATETIME NULL,
                `user_created` int(11) DEFAULT NULL,
                `user_updated` int(11) DEFAULT NULL,
                `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `seo_description` text COLLATE utf8mb4_unicode_ci,
                `seo_keywords` text COLLATE utf8mb4_unicode_ci,
                `order` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
            if(!model()::schema()->hasColumn('products', 'brand_id')) {
                $model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `brand_id` INT NOT NULL DEFAULT '0' AFTER `supplier_id`;");
            }
        }
    }
    public static function Version_2_2_0($model) {
        if(!model()::schema()->hasColumn('brands', 'excerpt')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."brands` ADD `excerpt` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `name`;");
        }
        $model->query("ALTER TABLE `".CLE_PREFIX."products` CHANGE `excerpt` `excerpt` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
        $model->query("ALTER TABLE `".CLE_PREFIX."products` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
    }
    public static function Version_3_2_0($model) {
        $options = [
            'woocommerce_brands'        => 'product_brands',
            'woocommerce_supplier'      => 'product_supplier',
            'woocommerce_currency'      => 'product_currency',
            'woocommerce_price_contact' => 'product_price_contact',
            'wcmc_version'              => 'product_version', // version 2.0.5
        ];
        foreach ($options as $optionOld => $optionNew) {
            $value = Option::get($optionOld);
            $valueNew = Option::get($optionNew);
            if(empty($valueNew)) Option::update($optionNew, $value);
            Option::delete($optionOld);
        }
    }
    public static function Version_3_5_0($model) {
        $brands = Brands::gets();
        $suppliers = Suppliers::gets();
        $model->settable('routes');
        foreach ($brands as $brand) {
            $router = $model->get(Qr::set('slug', $brand->slug)->where('object_id', $brand->id));
            if(have_posts($router)) {
                $model->update([
                    'controller'    => 'frontend/products/index/',
                    'callback'      => '',
                    'object_type'   => 'brands',
                ],Qr::set($router->id));
            }
        }
        foreach ($suppliers as $supplier) {
            $router = $model->get(Qr::set('slug', $supplier->slug)->where('object_id', $supplier->id));
            if(have_posts($router)) {
                $model->update([
                    'controller'    => 'frontend/products/index/',
                    'callback'      => '',
                    'object_type'   => 'suppliers',
                ],Qr::set($router->id));
            }
        }
    }
    public static function Version_3_6_0($model) {
        $model->query("ALTER TABLE `".CLE_PREFIX."product_metadata` CHANGE `meta_key` `meta_key` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
        $model->query("ALTER TABLE `".CLE_PREFIX."product_metadata` ADD INDEX(`object_id`, `meta_key`);");
    }
    public static function Version_3_7_0($model) {
        $productCurrency = [];
        $productPriceContact = [];
        foreach (Language::listKey() as $key) {
            $productCurrency[$key]['unit']      = Option::get('product_currency');
            $productCurrency[$key]['position']  = 'after';
            $productPriceContact[$key] = Option::get('product_price_contact');
        }
        Option::update('product_currency', $productCurrency);
        Option::update('product_price_contact', $productPriceContact);
        if(!model()::schema()->hasTable('products_metadata')) {
            DB::schema()->rename('product_metadata', 'products_metadata');
        }
    }
}
Class Product_Update_Role {
    public static function Version_1_9_0($model) {
        $role = Role::get('root');
        $role->add_cap('wcmc_product_list');
        $role->add_cap('wcmc_product_edit');
        $role->add_cap('wcmc_product_delete');
        $role->add_cap('wcmc_product_cate_list');
        $role->add_cap('wcmc_product_cate_edit');
        $role->add_cap('wcmc_product_cate_delete');

        $role = Role::get('administrator');
        $role->add_cap('wcmc_product_list');
        $role->add_cap('wcmc_product_edit');
        $role->add_cap('wcmc_product_delete');
        $role->add_cap('wcmc_product_cate_list');
        $role->add_cap('wcmc_product_cate_edit');
        $role->add_cap('wcmc_product_cate_delete');
    }
    public static function Version_2_0_0($model) {
        $role = Role::get('root');
        $role->add_cap('wcmc_product_setting');

        $role = Role::get('administrator');
        $role->add_cap('wcmc_product_setting');
    }
    public static function Version_3_2_0($model) {

        $roles = [
            'wcmc_product_list'         => 'product_list',
            'wcmc_product_edit'         => 'product_edit',
            'wcmc_product_delete'       => 'product_delete',
            'wcmc_product_cate_list'    => 'product_cate_list',
            'wcmc_product_cate_edit'    => 'product_cate_edit',
            'wcmc_product_cate_delete'  => 'product_cate_delete',
            'wcmc_product_setting'      => 'product_setting',
        ];

        foreach ($roles as $roleOld => $roleNew) {
            $role = Role::get('root');
            $role->remove_cap($roleOld);
            $role->add_cap($roleNew);
            $role = Role::get('administrator');
            $role->remove_cap($roleOld);
            $role->add_cap($roleNew);
        }

        $users = User::gets(Qr::set('username', '>', 2));

        foreach ($users as $item) {
            foreach ($roles as $roleOld => $roleNew) {
                if(User::hasCap($item->id, $roleOld)) {
                    User::addRole($item->id, $roleNew);
                }
            }
        }
    }
}
Class Product_Update_Files {
    public static function Version_2_0_0($model) {
        $path = FCPATH.PRODUCT_PATH.'/';
        $Files = [
            'admin/views/html-settings-tab-general.php',
            'admin/views/html-settings-tab-product-detail.php',
            'admin/views/html-settings-tab-product-index.php',
            'admin/views/html-settings-tab-product-object.php',
            'admin/views/html-settings-tab-product.php',
        ];
        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
    }
    public static function Version_3_0_0($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;
        $Files = [
            'admin/views/html-form-left.php',
            'admin/views/html-form-right.php',
            'admin/include/helper-metadata.php',
        ];
        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
    }
    public static function Version_3_3_0($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;
        $Files = [
            'admin/action-bar.php',
            'admin/form.php',
            'admin/table.php',
            'admin/menu.php',
            'admin/brands.php',
            'admin/suppliers.php',
            'admin/related.php',
        ];
        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
    }
    public static function Version_3_5_0($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;
        $Files = [
            'admin/products/delete.php',
            'admin/categories/delete.php',
        ];
        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
    }
    public static function Version_3_7_0($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;
        $Files = [
            'admin/brands/ajax.php',
            'admin/brands/form.php',
            'admin/brands/menu.php',
            'admin/suppliers/ajax.php',
            'admin/suppliers/form.php',
        ];
        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
    }
}