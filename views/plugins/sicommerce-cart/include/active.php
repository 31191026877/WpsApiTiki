<?php
Class Cart_Activator {
    public static function activate() {
        self::createTable();
        self::createPage();
        self::addOption();
        self::addRole();
    }
    public static function createTable(): void {
        if(class_exists('woocommerce-cart') || file_exists(Path::plugin('woocommerce-cart').'/woocommerce-cart.php')) {
            $update = new Cart_Update_Version();
            $update->runUpdate('2.3.3');
        }
        else {
            $model = model();
            if(!$model::schema()->hasTable('attribute')) {
                $model::schema()->create('attribute', function ($table) {
                    $table->increments('id');
                    $table->string('title', 200)->collate('utf8mb4_unicode_ci')->nullable();
                    $table->string('slug', 200)->collate('utf8mb4_unicode_ci')->nullable();
                    $table->string('option_type', 200)->collate('utf8mb4_unicode_ci')->nullable();
                    $table->integer('order')->default(0);
                    $table->integer('public')->default(1);
                    $table->dateTime('created');
                    $table->dateTime('updated')->nullable();
                });
            }

            if(!$model::schema()->hasTable('attribute_item')) {
                $model::schema()->create('attribute_item', function ($table) {
                    $table->increments('id');
                    $table->integer('option_id')->default(0);
                    $table->string('title', 200)->collate('utf8mb4_unicode_ci');
                    $table->string('value', 200)->collate('utf8mb4_unicode_ci');
                    $table->string('image', 255)->collate('utf8mb4_unicode_ci');
                    $table->string('type', 100)->collate('utf8mb4_unicode_ci');
                    $table->integer('order')->default(0);
                    $table->integer('public')->default(1);
                    $table->dateTime('created');
                    $table->dateTime('updated')->nullable();
                    $table->index('option_id');
                });
            }

            if(!$model::schema()->hasTable('order')) {
                $model::schema()->create('order', function ($table) {
                    $table->increments('id');
                    $table->string('code', 100)->collate('utf8mb4_unicode_ci');
                    $table->string('total', 200)->collate('utf8mb4_unicode_ci')->default(0);
                    $table->integer('order')->default(0);
                    $table->integer('public')->default(1);
                    $table->dateTime('created');
                    $table->dateTime('updated')->nullable();
                    $table->string('status', 100)->collate('utf8mb4_unicode_ci');
                    $table->string('status_pay', 100)->collate('utf8mb4_unicode_ci');
                    $table->integer('user_created')->default(0);
                    $table->integer('user_updated')->default(0);
                });
            }

            if(!$model::schema()->hasTable('order_metadata')) {
                $model::schema()->create('order_metadata', function ($table) {
                    $table->increments('id');
                    $table->integer('object_id')->default(0);
                    $table->string('meta_key', 100)->collate('utf8mb4_unicode_ci');
                    $table->text('meta_value')->collate('utf8mb4_unicode_ci')->nullable();
                    $table->integer('order')->default(0);
                    $table->dateTime('created');
                    $table->dateTime('updated')->nullable();
                    $table->index('object_id');
                    $table->index('meta_key');
                });
            }

            if(!$model::schema()->hasTable('order_detail')) {
                $model::schema()->create('order_detail', function ($table) {
                    $table->increments('id');
                    $table->integer('order_id')->default(0);
                    $table->integer('product_id')->default(0);
                    $table->string('title', 200)->collate('utf8mb4_unicode_ci')->nullable();
                    $table->integer('quantity')->default(0);
                    $table->string('image', 200)->collate('utf8mb4_unicode_ci')->nullable();
                    $table->text('option')->collate('utf8mb4_unicode_ci')->nullable();
                    $table->string('price', 200)->default(0);
                    $table->string('subtotal', 200)->default(0);
                    $table->integer('order')->default(0);
                    $table->dateTime('created');
                    $table->dateTime('updated')->nullable();
                });
            }

            if(!$model::schema()->hasTable('order_detail_metadata')) {
                $model::schema()->create('order_detail_metadata', function ($table) {
                    $table->increments('id');
                    $table->integer('object_id')->default(0);
                    $table->string('meta_key', 100)->collate('utf8mb4_unicode_ci');
                    $table->text('meta_value')->collate('utf8mb4_unicode_ci')->nullable();
                    $table->integer('order')->default(0);
                    $table->dateTime('created');
                    $table->dateTime('updated')->nullable();
                    $table->index('object_id');
                    $table->index('meta_key');
                });
            }

            if(!$model::schema()->hasTable('order_history')) {
                $model::schema()->create('order_history', function ($table) {
                    $table->increments('id');
                    $table->integer('order_id')->default(0);
                    $table->text('message')->collate('utf8mb4_unicode_ci')->nullable();
                    $table->integer('order')->default(0);
                    $table->dateTime('created');
                    $table->dateTime('updated')->nullable();
                    $table->string('action', 100)->collate('utf8mb4_unicode_ci')->nullable();
                    $table->index('order_id');
                });
            }

            if(!$model::schema()->hasTable('session')) {
                $model::schema()->create('session', function ($table) {
                    $table->increments('session_id');
                    $table->string('session_key', 255)->collate('utf8mb4_unicode_ci')->nullable();
                    $table->text('session_value')->collate('utf8mb4_unicode_ci')->nullable();
                    $table->integer('session_expiry')->default(0);
                    $table->integer('order')->default(0);
                    $table->dateTime('created');
                    $table->dateTime('updated')->nullable();
                });
            }

            if(!$model::schema()->hasColumn('users', 'order_count')) {
                $model::select("ALTER TABLE `".CLE_PREFIX."users` ADD `order_count` INT NULL DEFAULT '0' AFTER `order`;");
            }
            if(!$model::schema()->hasColumn('users', 'order_total')) {
                $model::select("ALTER TABLE `".CLE_PREFIX."users` ADD `order_total` INT NULL DEFAULT '0' AFTER `order`;");
            }
            if(!$model::schema()->hasColumn('users', 'customer')) {
                $model::select("ALTER TABLE `".CLE_PREFIX."users` ADD `customer` INT NULL DEFAULT '0' AFTER `order`;");
            }
            $model::select("ALTER TABLE `".CLE_PREFIX."users` CHANGE `username` `username` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `salt` `salt` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        }
    }
    public static function createPage() {
        // ADD TRANG NỘI DUNG
        if(Pages::count(Qr::set('slug', 'gio-hang')) == 0) {
            $page = [
                'title' 	=> 'Giỏ hàng',
                'content' 	=> '[page_cart]'
            ];
            $cart = Pages::insert($page);
            if(!is_skd_error($cart)) {
                Option::add('page_cart_id',  $cart);
            }
        }
        if(Pages::count(Qr::set('slug', 'thanh-toan')) == 0) {
            $page = [
                'title' 	=> 'Thanh toán',
                'content' 	=> '[page_checkout]',
            ];
            $checkout 	= Pages::insert($page);
            if(!is_skd_error($checkout)) {
                option::add( 'page_checkout_id',  $checkout);
            }
        }
        if(Pages::count(Qr::set('slug', 'don-hang')) == 0) {
            $page = [
                'title' 	=> 'Đơn hàng',
                'content' 	=> '[page_success]',
            ];
            $success 	= Pages::insert($page);
            if(!is_skd_error($success)) {
                option::add( 'page_success_id',  $success);
            }
        }
    }
    public static function addOption() {
        Option::update('default_role', 'customer');
        $options = [
            'cart_version'              => CART_VERSION,
            'cart_database_version'     => CART_DATABASE,
            'order_cancelled_reason'    => [
                'KH thay đổi / KH Hủy đơn',
                'Không liên hệ được KH',
                'Đơn hàng sai thông tin',
                'Sản phẩm không có sẳn',
            ]
        ];
        foreach ($options as $option_key => $option_value) {
            Option::add( $option_key, $option_value );
        }
    }
    public static function addRole() {
        Role::add('customer', 'Khách hàng');

        $role = Role::get('root');
        $role->add_cap('order_list');
        $role->add_cap('order_edit');
        $role->add_cap('order_delete');
        $role->add_cap('order_add');
        $role->add_cap('order_copy');
        $role->add_cap('order_setting');
        $role->add_cap('attributes_list');
        $role->add_cap('attributes_add');
        $role->add_cap('attributes_edit');
        $role->add_cap('attributes_delete');

        //khách hàng
        $role->add_cap('list_users');
        $role->add_cap('customer_active');
        $role->add_cap('customer_add');
        $role->add_cap('customer_edit');
        $role->add_cap('customer_reset_password');

        $role = Role::get('administrator');
        $role->add_cap('order_list');
        $role->add_cap('order_edit');
        $role->add_cap('order_delete');
        $role->add_cap('order_add');
        $role->add_cap('order_copy');
        $role->add_cap('order_setting');
        $role->add_cap('attributes_list');
        $role->add_cap('attributes_add');
        $role->add_cap('attributes_edit');
        $role->add_cap('attributes_delete');

        //khách hàng
        $role->add_cap('list_users');
        $role->add_cap('customer_active');
        $role->add_cap('customer_add');
        $role->add_cap('customer_edit');
        $role->add_cap('customer_reset_password');
    }
}

Class Cart_Deactivator {
    public static function uninstall() {
        self::cropTable();
        self::deletePage();
        self::deleteOption();
        self::deleteData();
    }
    public static function cropTable() {
        $model = model();
        $model::schema()->drop('attribute');
        $model::schema()->drop('attribute_item');
        $model::schema()->drop('order');
        $model::schema()->drop('order_metadata');
        $model::schema()->drop('order_detail');
        $model::schema()->drop('order_detail_metadata');
        $model::schema()->drop('order_history');
        $model::schema()->drop('session');
    }
    public static function deletePage() {
        $page_cart      = Option::get('page_cart_id');
        $page_checkout  = Option::get('page_checkout_id');
        $page_success   = Option::get('page_success_id');
        Pages::delete($page_cart);
        Pages::delete($page_checkout);
        Pages::delete($page_success);
        Option::delete('page_cart_id');
        Option::delete('page_checkout_id');
        Option::delete('page_success_id');
    }
    public static function deleteOption() {
        $options = [
            'cart_version',
            'cart_database_version',
            '_setting_checkout_cod',
            '_setting_checkout_bacs',
            'order_cancelled_reason'
        ];
        foreach ($options as $option_key => $option_value) {
            Option::delete( $option_value );
        }
    }
    public static function deleteData() {
        model('relationships')->delete(Qr::set('object_type', 'attributes'));
    }
}