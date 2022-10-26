<?php
if(!Admin::is()) return;
function Cart_update_core() {
    if(Admin::is() && Auth::check()) {
        $cart_version = Option::get('cart_version');
        if (version_compare(CART_VERSION, $cart_version) === 1) {
            $update = new Cart_Update_Version();
            $update->runUpdate($cart_version);
        }
    }
}
add_action('admin_init', 'Cart_update_core', 1);

Class Cart_Update_Version {
    public function runUpdate($cartVersion) {
        $listVersion    = ['2.0.0', '2.3.1', '2.3.2', '2.3.3', '2.4.0', '2.5.0', '2.6.0', '2.6.1', '2.6.2', '2.8.0', '2.8.3', '2.8.4', '3.1.0', '3.2.0', '3.2.2'];
        $model          = get_model();
        foreach ($listVersion as $version ) {
            if(version_compare( $version, $cartVersion ) == 1) {
                $function = 'update_Version_'.str_replace('.','_',$version);
                if(method_exists($this, $function)) $this->$function($model);
            }
        }
        Option::update('cart_version', CART_VERSION );
    }
    public function update_Version_2_0_0($model) {
        Cart_Update_Database::Version_2_0_0($model);
    }
    public function update_Version_2_3_1($model) {
        Cart_Update_Database::Version_2_3_1($model);
        Cart_Update_Role::Version_2_3_1($model);
    }
    public function update_Version_2_3_2($model) {
        Cart_Update_Database::Version_2_3_2($model);
    }
    public function update_Version_2_3_3($model) {
        Cart_Update_Database::Version_2_3_3($model);
        Cart_Update_Role::Version_2_3_3($model);
    }
    public function update_Version_2_4_0($model) {
        Cart_Update_Database::Version_2_4_0($model);
        Cart_Update_Role::Version_2_4_0($model);
        Cart_Update_Files::Version_2_4_0($model);
    }
    public function update_Version_2_5_0($model) {
        Cart_Update_Database::Version_2_5_0($model);
    }
    public function update_Version_2_6_0($model) {
        Cart_Update_Database::Version_2_6_0($model);
        Cart_Update_Files::Version_2_6_0($model);
    }
    public function update_Version_2_6_1($model) {
        Cart_Update_Database::Version_2_6_1($model);
    }
    public function update_Version_2_6_2($model) {
        Cart_Update_Database::Version_2_6_2($model);
        Cart_Update_Files::Version_2_6_2($model);
        Cart_Update_Role::Version_2_6_2($model);
    }
    public function update_Version_2_8_0($model) {
        Cart_Update_Files::Version_2_8_0($model);
        Cart_Update_Role::Version_2_8_0($model);
    }
    public function update_Version_2_8_3($model) {
        Cart_Update_Database::Version_2_8_3($model);
    }
    public function update_Version_2_8_4($model) {
        Cart_Update_Database::Version_2_8_4($model);
    }
    public function update_Version_3_1_0($model) {
        Cart_Update_Files::Version_3_1_0($model);
        Cart_Update_Database::Version_3_1_0($model);
    }
    public function update_Version_3_2_0($model) {
        Cart_Update_Database::Version_3_2_0($model);
    }
    public function update_Version_3_2_2($model) {
        Cart_Update_Files::Version_3_2_2($model);
        Cart_Update_Database::Version_3_2_2($model);
    }
}
Class Cart_Update_Database {
    public static function Version_2_0_0($model) {

        $model->settable('wcmc_order');

        //V1.2
        $orders = $model->gets();

        foreach ($orders as $order) {

            $model->settable('metabox');

            $metabox = ['object_type' => 'woocommerce_order', 'object_id' => $orders->id];

            if(!empty($order->billing_fullname)) {
                $metabox['meta_key'] 	= 'billing_fullname';
                $metabox['meta_value'] 	= $order->billing_fullname;
                $model->add($metabox);
            }

            if(!empty($order->billing_email)) {
                $metabox['meta_key'] 	= 'billing_email';
                $metabox['meta_value'] 	= $order->billing_email;
                $model->add($metabox);
            }

            if(!empty($order->billing_phone)) {
                $metabox['meta_key'] 	= 'billing_phone';
                $metabox['meta_value'] 	= $order->billing_phone;
                $model->add($metabox);
            }

            if(!empty($order->billing_address)) {
                $metabox['meta_key'] 	= 'billing_address';
                $metabox['meta_value'] 	= $order->billing_address;
                $model->add($metabox);
            }

            if(!empty($order->shipping_fullname)) {
                $metabox['meta_key'] 	= 'shipping_fullname';
                $metabox['meta_value'] 	= $order->shipping_fullname;
                $model->add($metabox);
            }

            if(!empty($order->shipping_email)) {
                $metabox['meta_key'] 	= 'shipping_email';
                $metabox['meta_value'] 	= $order->shipping_email;
                $model->add($metabox);
            }

            if(!empty($order->shipping_phone)) {
                $metabox['meta_key'] 	= 'shipping_phone';
                $metabox['meta_value'] 	= $order->shipping_phone;
                $model->add($metabox);
            }

            if(!empty($order->shipping_address)) {
                $metabox['meta_key'] 	= 'shipping_address';
                $metabox['meta_value'] 	= $order->shipping_address;
                $model->add($metabox);
            }
        }

        if(model()::schema()->hasColumn('billing_fullname', 'wcmc_order')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `billing_fullname`");
        }

        if(model()::schema()->hasColumn('billing_email', 'wcmc_order')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `billing_email`");
        }

        if(model()::schema()->hasColumn('billing_phone', 'wcmc_order')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `billing_phone`");
        }

        if(model()::schema()->hasColumn('wcmc_order', 'billing_address') ) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `billing_address`");
        }

        if(model()::schema()->hasColumn('wcmc_order', 'shipping_fullname') ) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `shipping_fullname`");
        }

        if(model()::schema()->hasColumn('wcmc_order', 'shipping_email') ) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `shipping_email`");
        }

        if(model()::schema()->hasColumn('wcmc_order', 'shipping_phone')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `shipping_phone`");
        }

        if(model()::schema()->hasColumn('wcmc_order', 'shipping_address')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `shipping_address`");
        }

        if(!model()::schema()->hasColumn('wcmc_options', 'slug')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_options` ADD `slug` VARCHAR(255) NULL AFTER `order`");
        }

        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` CHANGE `status` `status` VARCHAR(255) NOT NULL DEFAULT '".ORDER_WAIT."'");

        $model->query("UPDATE `".CLE_PREFIX."wcmc_order` SET `status`= '".ORDER_WAIT."' WHERE `status` = '1'");

        $model->query("UPDATE `".CLE_PREFIX."wcmc_order` SET `status`= '".ORDER_CONFIRM."' WHERE `status` = '2'");

        $model->query("UPDATE `".CLE_PREFIX."wcmc_order` SET `status`= '".ORDER_COMPLETED."' WHERE `status` = '3'");

        $model->query("UPDATE `".CLE_PREFIX."wcmc_order` SET `status`= 'wc-failed' WHERE `status` = '4'");

        $model->query("UPDATE `".CLE_PREFIX."relationships` SET `value` = 'products_categories' WHERE `".CLE_PREFIX."relationships`.`object_type` = 'products'");

        $model->settable('wcmc_options');

        $options = $model->gets();

        if(have_posts($options)) {
            foreach ($options as $option) {
                $model->update(['slug' => Str::slug($option->title)], Qr::set($option->id));
            }
        }

        //V1.3
        if(!model()::schema()->hasTable('wcmc_order_metadata')) {

            $model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8mb4_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        }

        if(!model()::schema()->hasColumn('wcmc_order', 'user_created')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` ADD `user_created` INT NOT NULL DEFAULT '0' AFTER `status`, ADD `user_updated` INT NOT NULL DEFAULT '0' AFTER `user_created`;");
        }

        if(model()::schema()->hasColumn('wcmc_order','order_note')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `order_note`;");
        }

        if(!model()::schema()->hasTable('product_metadata')) {

            $model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."product_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8mb4_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }

        if(!model()::schema()->hasTable('wcmc_variations_metadata')) {

            $model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_variations_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8mb4_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }

        if(model()::schema()->hasTable('wcmc_order_metadata') ) {

            $model->settable('metabox');

            $metadatas = $model->gets(Qr::set('object_type', 'woocommerce_order'));

            if( have_posts($metadatas) ) {

                $model->settable('wcmc_order_metadata');

                foreach ($metadatas as $meta) {
                    Order::updateMeta( $meta->object_id, $meta->meta_key, $meta->meta_value );
                }

                $model->settable('metabox');

                $model->delete_where( array('object_type' => 'woocommerce_order'));
            }

            $check['order_metadata'] = true;
        }

        if(model()::schema()->hasTable('product_metadata')) {

            $model->settable('metabox');

            $metadatas = $model->gets(Qr::set('object_type', 'woocommerce_attributes')->where('meta_key', '_product_attributes'));

            if(have_posts($metadatas)) {

                $model->settable('product_metadata');

                foreach ($metadatas as $meta) {
                    Metadata::update('products', $meta->object_id, 'attributes', $meta->meta_value );
                }

                $model->settable('metabox')->delete(Qr::set('object_type', 'woocommerce_attributes')->where('meta_key', '_product_attributes'));
            }

            $check['product_metadata'] = true;
        }

        if(model()::schema()->hasTable('wcmc_variations_metadata')) {

            $model->settable('metabox');

            $metadatas = $model->gets(Qr::set('object_type', 'woocommerce_attributes'));

            if( have_posts($metadatas) ) {

                $model->settable('wcmc_variations_metadata');

                foreach ($metadatas as $meta) {
                    Metadata::update('wcmc_variations', $meta->object_id, $meta->meta_key, $meta->meta_value );
                }

                $model->settable('metabox')->delete(Qr::set('object_type', 'woocommerce_attributes'));
            }

            $check['variations_metadata'] = true;
        }

        $model->settable('relationships');

        $model->update(Qr::set('object_type', 'attributes')->where('object_type', 'woocommerce_attributes'));

        Option::update('cart_database_version', '1.3');
    }
    public static function Version_2_3_1($model) {
        $orders = UpdateOrderV1::gets();
        foreach ($orders as $order) {
            $quantity = 0;
            foreach ($order->items as $item) {
                $quantity += $item->quantity;
            }
            UpdateOrderV1::updateMeta( $order->id, 'quantity', $quantity );
        }
        Option::update( 'cart_database_version', '1.4' );
    }
    public static function Version_2_3_2($model) {
        if(!model()::schema()->hasTable('wcmc_order_detail_metadata')) {
            $model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order_detail_metadata` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `object_id` int(11) DEFAULT NULL,
                `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `meta_value` text COLLATE utf8mb4_unicode_ci,
                `created` datetime DEFAULT NULL,
                `updated` datetime DEFAULT NULL,
                `order` int(11) DEFAULT '0'
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
        if(model()::schema()->hasTable('wcmc_order_detail_metadata')) {
            $order_items = UpdateOrderV1::getsItem();
            foreach ($order_items as $item) {
                UpdateOrderV1::updateItemMeta( $item->id, 'attribute', $item->option );
            }
        }
        Option::update( 'cart_database_version', '1.5' );
    }
    public static function Version_2_3_3($model) {
        if(!model()::schema()->hasColumn('users', 'order_count')) {

            if(!model()::schema()->hasColumn('users', 'order_count')) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `order_count` INT NOT NULL DEFAULT '0' AFTER `order`;");

            if(!model()::schema()->hasColumn('users', 'order_total')) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `order_total` INT NOT NULL DEFAULT '0' AFTER `order`;");

            if(!model()::schema()->hasColumn('users', 'customer')) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `customer` INT NOT NULL DEFAULT '0' AFTER `order`;");

            $orders = UpdateOrderV1::gets();

            $order_total = [];

            foreach ($orders as $key => $order) {

                if(!isset($order_total[$order->billing_email])) {

                    $order_total[$order->billing_email] = [
                        'order_count' => 1,
                        'order_total' => 0,
                        'order'		  => $order,
                        'order_recent'=> $order->code,
                    ];

                    if($order->status == ORDER_COMPLETED) {

                        $order_total['order_total'] = $order->total;
                    }
                }
                else {

                    $order_total[$order->billing_email]['order_count'] += 1;

                    $order_total[$order->billing_email]['order'] = $order;

                    $order_total[$order->billing_email]['order_recent'] = $order->code;

                    if($order->status == ORDER_COMPLETED) {

                        $order_total[$order->billing_email]['order_total'] += $order->total;
                    }
                }
            }

            foreach ($order_total as $email => $data) {

                $user = User::get(Qr::set('status', '<>', 'null')->where('email', $email));

                if(have_posts($user)) {

                    $user->order_total = $data['order_total'];

                    $user->order_count = $data['order_count'];

                    $user->customer    = 2;

                    User::insert((array)$user);

                    User::updateMeta( $user->id, 'order_recent', $data['order_recent']);
                }
                else {

                    $fullname =  explode(' ', $data['order']->billing_fullname);

                    $lastname 	= array_pop($fullname);

                    $firstname 	= str_replace( ' '.$lastname, '', $data['order']->billing_fullname );

                    $user = [
                        'firstname' 	=> $firstname,
                        'lastname'  	=> $lastname,
                        'email'			=> $data['order']->billing_email,
                        'phone'			=> $data['order']->billing_phone,
                        'order_total' 	=> $data['order_total'],
                        'order_count' 	=> $data['order_count'],
                        'status' 	    => 'public',
                        'customer' 	    => 1
                    ];

                    $model->settable('users');

                    $user_id = $model->add($user);

                    if(!is_skd_error($user_id)) {

                        user_set_role( $user_id, 'customer');

                        User::updateMeta( $user_id, 'order_recent', $data['order_recent']);
                    }
                }
            }

            foreach ($orders as $key => $order) {

                $user = User::get(Qr::set('status', '<>', 'null')->where('email', $order->billing_email));

                if(have_posts($user)) {

                    UpdateOrderV1::insert([
                        'id' => $order->id,
                        'user_created' => $user->id
                    ]);
                }
            }
        }
        Option::update( 'cart_database_version', '1.6' );
    }
    public static function Version_2_4_0($model) {
        $orders = UpdateOrderV1::gets();
        foreach ($orders as $key => $order) {
            if( $order->shipping_fullname == $order->billing_fullname && $order->shipping_address == $order->billing_address &&  $order->shipping_phone == $order->billing_phone &&  $order->shipping_email == $order->billing_email ) {
                UpdateOrderV1::updateMeta($order->id, 'other_delivery_address', false);
            } else {
                UpdateOrderV1::updateMeta($order->id, 'other_delivery_address', true);
            }
        }

        if(model()::schema()->hasTable('wcmc_variations') && !model()::schema()->hasColumn('wcmc_variations', 'code')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_variations` ADD `code` VARCHAR(255) NULL AFTER `id`;");
        }
        Option::update( 'cart_database_version', '1.7' );
    }
    public static function Version_2_5_0($model) {
        if(model()::schema()->hasColumn('products', 'status')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."products` CHANGE `status` `status` VARCHAR(255) NOT NULL DEFAULT 'public';");
            $model->query("UPDATE `".CLE_PREFIX."products` SET `status`= 'public' WHERE 1;");
        }
        if(!model()::schema()->hasColumn('products', 'type')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `type` VARCHAR(255) NULL DEFAULT 'product' AFTER `status3`;");
            $model->query("UPDATE `".CLE_PREFIX."products` SET `type`= 'product' WHERE 1;");
        }
        if(!model()::schema()->hasColumn('products', 'parent_id')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `parent_id` INT NULL DEFAULT 0 AFTER `status3`;");
            $model->query("UPDATE `".CLE_PREFIX."products` SET `parent_id`= 0 WHERE 1;");
        }
        if(model()::schema()->hasTable('wcmc_variations')) {
            $success = [];
            $model->settable('wcmc_variations');
            $variations = $model->gets(Qr::set('object_type', 'public'));
            if(have_posts($variations)) {
                foreach ($variations as $key => $variation) {
                    $model->settable('products');
                    $product = $model->get(Qr::set($variation->object_id));
                    $product_variation = [
                        'title' 	=> $product->title,
                        'code' 		=> $variation->code,
                        'status' 	=> 'public',
                        'type' 		=> 'variations',
                        'created' 	=> $variation->created,
                        'updated' 	=> $variation->updated,
                        'parent_id' => $variation->object_id,
                    ];
                    $product_variation['price'] 		= metadata::get( 'wcmc_variations', $variation->id, '_price', true );
                    $product_variation['price_sale'] 	= metadata::get( 'wcmc_variations', $variation->id, '_price_sale', true );
                    $product_variation['image'] 		= metadata::get( 'wcmc_variations', $variation->id, '_image', true );
                    $model->settable('products');
                    $id = $model->add($product_variation);
                    if($id != 0) {
                        $model->settable('wcmc_variations')->delete(Qr::set($variation->id));
                        $model->settable('wcmc_variations_metadata');
                        $model->delete(Qr::set('object_id', $variation->id)->where('meta_key', '_price'));
                        $model->delete(Qr::set('object_id', $variation->id)->where('meta_key', '_price_sale'));
                        $model->delete(Qr::set('object_id', $variation->id)->where('meta_key', '_image'));
                        $metadata = $model->gets(Qr::set('object_id', $variation->id));
                        if(have_posts($metadata)) {
                            foreach ($metadata as $meta) {
                                Product::updateMeta($id, $meta->meta_key, $meta->meta_value);
                            }
                            $model->settable('wcmc_variations_metadata')->delete(Qr::set('object_id', $variation->id));
                        }
                    }
                }
                $model->settable('wcmc_variations');
                $variations = $model->gets(Qr::set('object_type', 'public'));
                if(!have_posts($variations)) {
                    $model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_variations`");
                }
                $model->settable('wcmc_variations_metadata');
                $metadata = $model->gets();
                if(!have_posts($metadata)) {
                    $model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_variations_metadata`");
                }
            }
            else {
                $model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_variations`");
                $model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_variations_metadata`");
            }
        }
        Option::update( 'cart_database_version', '2.0' );
    }
    public static function Version_2_6_0($model) {
        if(!model()::schema()->hasColumn('products', 'weight')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `weight` INT NULL DEFAULT 0 AFTER `status3`;");
        }
        if(!model()::schema()->hasTable('wcmc_order_history')) {
            $model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order_history` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `order_id` int(11) DEFAULT NULL,
                `message` text COLLATE utf8mb4_unicode_ci,
                `created` datetime DEFAULT NULL,
                `updated` datetime DEFAULT NULL,
                `order` int(11) DEFAULT '0',
                `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
        Option::update( 'cart_database_version', '2.1' );
    }
    public static function Version_2_6_1($model) {
        if(model()::schema()->hasColumn('users', 'order_count')) {
            $model->query("ALTER TABLE `".CLE_PREFIX."users` CHANGE `customer` `customer` INT(11) NULL DEFAULT '0', CHANGE `order_total` `order_total` INT(11) NULL DEFAULT '0', CHANGE `order_count` `order_count` INT(11) NULL DEFAULT '0';");
        }
        Option::update( 'cart_database_version', '2.2' );
    }
    public static function Version_2_6_2($model) {
        if(!model()::schema()->hasColumn('wcmc_order', 'status_pay')) {

            $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` ADD `status_pay` VARCHAR(255) NULL DEFAULT 'unpaid' AFTER `status`;");

            $orders = UpdateOrderV1::gets([], false, false);

            foreach ($orders as $order) {

                $order->status_pay = 'unpaid';

                if($order->status == 'wc-pending') {
                    $order->status = ORDER_SHIPPING;
                }

                if($order->status == ORDER_COMPLETED) {
                    $order->status_pay = 'paid';
                }

                UpdateOrderV1::insert((array)$order);

                delete_cache( 'order_'.$order->id, true );
            }
        }
        $model->query("ALTER TABLE `".CLE_PREFIX."users` CHANGE `username` `username` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `salt` `salt` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        $orders = UpdateOrderV1::gets([], false, false);
        foreach ($orders as $order) {
            if($order->status == 'wc-wait-confim') $order->status = ORDER_WAIT;
            if($order->status == 'wc-confim') $order->status = ORDER_CONFIRM;
            UpdateOrderV1::insert((array)$order);
            CacheHandler::delete( 'order_'.$order->id, true );
        }
        Option::update( 'cart_database_version', '2.5' );
    }
    public static function Version_2_8_3($model) {
        $model->settable('language')->update(Qr::set('object_type', 'attributes')->where('object_type', 'wcmc_attribute'));
        $model->settable('routes')->update(Qr::set('object_type', 'attributes')->where('object_type', 'wcmc_attribute')->where('directional', 'attributes')->where('callback', 'attributes_frontend'));
        Option::update( 'cart_database_version', '2.5.1' );
    }
    public static function Version_2_8_4($model) {
        Role::add('customer', 'Khách hàng');
        Option::update('default_role', 'customer');
        $model->settable('users')->update(['role' => 'customer'], Qr::set('role', 'subscriber'));
        CacheHandler::delete( 'user_', true );
        Option::update( 'cart_database_version', '2.5.1' );
    }
    public static function Version_3_1_0($model) {
        $cart_setting_email = Option::get('wcmc_email');
        Option::update('cart_email', $cart_setting_email);
        Option::delete('wcmc_email');

        $shipping_default = Option::get('wcmc_shipping_default');
        Option::update('cart_shipping_default', $shipping_default);
        Option::delete('wcmc_shipping_default');

        $shipping = Option::get('wcmc_shipping');
        Option::update('cart_shipping', $shipping);
        Option::delete('wcmc_shipping');

        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_options` RENAME TO `".CLE_PREFIX."attribute`;");
        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_options_item` RENAME TO `".CLE_PREFIX."attribute_item`;");

        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` RENAME TO `".CLE_PREFIX."order`;");
        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order_history` RENAME TO `".CLE_PREFIX."order_history`;");
        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order_metadata` RENAME TO `".CLE_PREFIX."order_metadata`;");
        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order_detail` RENAME TO `".CLE_PREFIX."order_detail`;");
        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order_detail_metadata` RENAME TO `".CLE_PREFIX."order_detail_metadata`;");
        $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_session` RENAME TO `".CLE_PREFIX."session`;");
    }
    public static function Version_3_2_0($model) {
        $model->settable('routes');
        $attributes = $model->gets(Qr::set('directional', 'attributes')->where('callback', 'attributes_frontend'));
        if(have_posts($attributes)) {
            foreach ($attributes as $attribute) {
                $model->update(['object_type' => 'attributes'], Qr::set($attribute->id));
            }
        }
    }
    public static function Version_3_2_2($model) {
        $model->query("ALTER TABLE `".CLE_PREFIX."order_metadata` CHANGE `meta_key` `meta_key` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
        $model->query("ALTER TABLE `".CLE_PREFIX."order_detail_metadata` CHANGE `meta_key` `meta_key` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
        $model->query("ALTER TABLE `".CLE_PREFIX."order_metadata` ADD INDEX(`object_id`, `meta_key`);");
        $model->query("ALTER TABLE `".CLE_PREFIX."order_detail_metadata` ADD INDEX(`object_id`, `meta_key`);");
    }
}
Class Cart_Update_Role {
    public static function Version_2_3_1($model) {
        $role = get_role('root');
        $role->add_cap('wcmc_order_list');
        $role->add_cap('wcmc_order_edit');
        $role->add_cap('wcmc_order_delete');
        $role->add_cap('wcmc_attributes_list');
        $role->add_cap('wcmc_attributes_edit');
        $role->add_cap('wcmc_attributes_delete');

        $role = get_role('administrator');
        $role->add_cap('wcmc_order_list');
        $role->add_cap('wcmc_order_edit');
        $role->add_cap('wcmc_order_delete');
        $role->add_cap('wcmc_attributes_list');
        $role->add_cap('wcmc_attributes_edit');
        $role->add_cap('wcmc_attributes_delete');
    }
    public static function Version_2_3_3($model) {
        $role = get_role('root');
        $role->add_cap('customer_list');
        $role->add_cap('customer_active');
        $role->add_cap('customer_add');
        $role->add_cap('customer_edit');
        $role->add_cap('customer_reset_password');
        $role->add_cap('wcmc_attributes_add');
        $role->add_cap('wcmc_order_add');
        $role->add_cap('wcmc_order_copy');

        $role = get_role('administrator');
        $role->add_cap('customer_list');
        $role->add_cap('customer_active');
        $role->add_cap('customer_add');
        $role->add_cap('customer_edit');
        $role->add_cap('customer_reset_password');
        $role->add_cap('wcmc_attributes_add');
        $role->add_cap('wcmc_order_add');
        $role->add_cap('wcmc_order_copy');
    }
    public static function Version_2_4_0($model) {
        $role = get_role('root');
        $role->add_cap('wcmc_order_setting');

        $role = get_role('administrator');
        $role->add_cap('wcmc_order_setting');
    }
    public static function Version_2_6_2($model) {
        Option::update( 'order_cancelled_reason', [
            'KH thay đổi / KH Hủy đơn',
            'Không liên hệ được KH',
            'Đơn hàng sai thông tin',
            'Sản phẩm không có sẳn',
        ]);
        $role = get_role('administrator');
        $role->add_cap('list_users');
    }
    public static function Version_2_8_0($model) {
        $roles = [
            'wcmc_order_list'           => 'order_list',
            'wcmc_order_edit'           => 'order_edit',
            'wcmc_order_delete'         => 'order_delete',
            'wcmc_order_add'            => 'order_add',
            'wcmc_order_copy'           => 'order_copy',
            'wcmc_order_setting'        => 'order_setting',
            'wcmc_attributes_list'      => 'attributes_list',
            'wcmc_attributes_add'       => 'attributes_add',
            'wcmc_attributes_edit'      => 'attributes_edit',
            'wcmc_attributes_delete'    => 'attributes_delete',
        ];

        foreach ($roles as $roleOld => $roleNew) {
            $role = Role::get('root');
            $role->remove_cap($roleOld);
            $role->add_cap($roleNew);
            $role = Role::get('administrator');
            $role->remove_cap($roleOld);
            $role->add_cap($roleNew);
        }
        $users = User::gets(Qr::set('username', '<>', '')->where('id', '>', 2));
        foreach ($users as $item) {
            foreach ($roles as $roleOld => $roleNew) {
                if(User::hasCap($item->id, $roleOld)) {
                    User::addRole($item->id, $roleNew);
                }
            }
        }
    }
}
Class Cart_Update_Files {
    public static function Version_2_4_0($model) {
        $path = FCPATH.CART_PATH.'/';
        $Files = [
            //options
            'admin/wcmc-product-options.php',
            'admin/views/options/html-product-options-add.php',
            'admin/views/options/html-product-options-edit.php',
            'admin/views/options/html-product-options-item-add.php',
            'admin/views/options/html-product-options-item-edit.php',
            //customer
            'admin/customer/html/created/html-content.php',
            'admin/customer/html/created/html-note.php',
            'admin/customer/html/detail/html-content-order.php',
            'admin/customer/html/detail/html-content.php',
            'admin/customer/html/detail/html-customer-info.php',
            'admin/customer/html/html-customer-created.php',
            'admin/customer/html/html-customer-detail.php',
            'admin/customer/html/html-customer-list.php',
        ];
        foreach ($Files as $file) {
            if(file_exists($path.$file)) unlink($path.$file);
        }
        $Folders = [
            'admin/views/options',
            'admin/customer/html/created',
            'admin/customer/html/detail',
            'admin/customer/html'
        ];

        foreach ($Folders as $folder) {
            if(is_dir($path.$folder)) rmdir($path.$folder);
        }
    }
    public static function Version_2_6_0($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;
        $Files = [
            //wcmc-attribute :: đã duy chuyển vào attribute
            'admin/wcmc-attribute.php',
            'admin/views/attribute/html-attribute-add.php',
            'admin/views/attribute/html-attribute-edit.php',
            'admin/views/attribute/html-attribute-item-add.php',
            'admin/views/attribute/html-attribute-item-edit.php',
        ];
        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
        $Folders = [
            'admin/views/attribute',
        ];
        foreach ($Folders as $folder) {
            if(is_dir($path.$folder)) {
                rmdir($path.$folder);
            }
        }
    }
    public static function Version_2_6_2($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;

        $Files = [
            'admin/customer/wcmc-customer-action-bar.php',
            'admin/customer/wcmc-customer-heading.php',
            'admin/views/customer/html-customer-list.php',
            'admin/views/customer/html-customer-created.php',
            'admin/views/customer/created/html-note.php',
        ];

        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
    }
    public static function Version_2_8_0($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;

        $Files = [
            'admin/order/print.php',
            'assets/css/wcmc-cart-style.less',
            'assets/css/wcmc-cart-style.css',
            'assets/css/wcmc-cart-style.css.map',
            'assets/js/wcmc-add-to-cart.js',
        ];

        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
    }
    public static function Version_3_1_0($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;

        $Files = [
            'admin/navigation.php',
            'admin/table.php',
            'admin/print.php',
            'admin/setting/email.php',
            'admin/setting/cancelled.php',
            'admin/attribute/attribute-ajax.php',
            'admin/attribute/attribute-items.php',
            'admin/product-metabox/product-metabox.php',
            'admin/product-metabox/product-metabox-ajax.php',
            'admin/product-metabox/views/html-product-metabox.php',
            'admin/product-metabox/views/html-product-metabox-attribute-item.php',
            'admin/product-metabox/views/html-product-metabox-tab-attributes.php',
            'admin/product-metabox/views/html-product-metabox-tab-variations.php',
            'admin/product-metabox/views/html-product-metabox-variation-item.php',
        ];

        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }

        $Folders = [
            'admin/product-metabox/views',
            'admin/product-metabox',
        ];
        foreach ($Folders as $folder) {
            if(is_dir($path.$folder)) {
                rmdir($path.$folder);
            }
        }
    }
    public static function Version_3_2_2($model) {
        $path = FCPATH.VIEWPATH.'plugins/'.CART_NAME;

        $Files = [
            'template/detail/ajax_price_variations.php',
        ];

        foreach ($Files as $file) {
            if(file_exists($path.$file)) {
                unlink($path.$file);
            }
        }
    }
}