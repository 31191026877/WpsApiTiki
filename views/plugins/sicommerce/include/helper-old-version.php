<?php
class woocommerce {}
/** function thao tác với category phiên bản củ */
if(!function_exists('get_product')) {
    function get_product( $args = []) {
        return Product::get($args);
    }
}
if(!function_exists('gets_product')) {
    function gets_product( $args = []) {
        return Product::gets($args);
    }
}
if(!function_exists('count_product')) {
    function count_product( $args = []) {
        return Product::count($args);
    }
}
if(!function_exists('insert_product')) {
    function insert_product($product = [], $outsite = []) {
        return Product::insert($product);
    }
}
if(!function_exists('delete_product')) {
    function delete_product( $productID = 0, $trash = false ) {
        return Product::delete($productID, $trash);
    }
}
if(!function_exists('delete_list_product')) {
    function delete_list_product( $productID = [], $trash = false ) {
        return Product::deleteList($productID, $trash);
    }
}
if(!function_exists('get_product_category')) {
    /**
     * @since 2.0.6
     */
    function get_product_category( $args = [] ) {
        return ProductCategory::get($args);
    }
}
if(!function_exists('gets_product_category')) {
    function gets_product_category( $args = [] ) {
        return ProductCategory::gets($args);
    }
}
if(!function_exists('delete_product_category')) {
    function delete_product_category( $cate_ID = 0 ) {
        return ProductCategory::delete($cate_ID);
    }
}
if(!function_exists('delete_list_product_category')) {
    function delete_list_product_category( $cate_ID = [] ) {
        return ProductCategory::deleteList($cate_ID);
    }
}
if(!function_exists('wcmc_get_category')) {
    /**
     * [wcmc_get_category lấy danh mục sản phẩm]
     */
    function wcmc_get_category( $args = [] ) {

        return ProductCategory::get($args);
    }
}
if(!function_exists('wcmc_gets_category')) {
    /**
     * [wcmc_gets_category lấy danh sách danh mục sản phẩm]
     */
    function wcmc_gets_category( $args = [] ) {

        return ProductCategory::gets($args);
    }
}
if(!function_exists('wcmc_gets_category_recurs')) {

    function wcmc_gets_category_recurs( $trees = NULL, $where = [], $params = []) {

        $model = get_model('products');

        $model->settable('products_categories');

        $params = array_merge( $params, array( 'orderby' =>'order' ) );

        $root = $model->gets_where( $where , $params);

        if( isset( $root ) &&  have_posts($root) ) {

            foreach ($root as $val) {

                $trees[] = $val;

                $where['parent_id'] = $val->id;

                $trees   = wcmc_gets_category_recurs($trees, $where, $params);

            }
        }

        return $trees;
    }
}
if(!function_exists('wcmc_gets_category_mutilevel')) {
    /**
     * [wcmc_gets_category description]
     * @param  string $product_id   [description]
     * @param  string $attribute_op [description]
     * @param  string $model        [description]
     * @return [type]               [description]
     */
    function wcmc_gets_category_mutilevel( $id = 0, $where = [], $param = [], $model = '') {

        if($model == '') $model = get_model('products');

        $where_level = array_merge(array('parent_id' => $id), $where);

        $category = $model->fgets_categories_where('products_categories', $where_level, $param);

        $ci =& get_instance();

        $model->settable('products_categories');

        $categories = $ci->multilevel_categories($category, $where, $model, $param);

        $model->settable('products');

        return $categories;
    }
}
if(!function_exists('wcmc_gets_category_mutilevel_data')) {
    /**
     * [wcmc_gets_category description]
     * @param  string $product_id   [description]
     * @param  string $attribute_op [description]
     * @param  string $model        [description]
     * @return [type]               [description]
     */
    function wcmc_gets_category_mutilevel_data( $where = array( 'public' => 1 ), $params = []) {

        $ci       = &get_instance();

        $args = array(
            'mutilevel' => 'data',
            'where' => $where,
            'parms' => $params
        );

        return wcmc_gets_category( $args );
    }
}
if(!function_exists('wcmc_gets_category_mutilevel_option')) {
    /**
     * [wcmc_gets_category description]
     * @param  string $product_id   [description]
     * @param  string $attribute_op [description]
     * @param  string $model        [description]
     * @return [type]               [description]
     */
    function wcmc_gets_category_mutilevel_option( $where = array( 'public' => 1 ), $params = []) {

        $ci       = &get_instance();

        $args = array(
            'mutilevel' => 'option',
            'where' => $where,
            'parms' => $params
        );

        return wcmc_gets_category( $args );
    }
}
if(!function_exists('wcmc_delete_category')) {
    /**
     * @since  1.9.1
     */
    function wcmc_delete_category( $cate_ID = 0 ) {

        return delete_product_category($cate_ID);
    }
}
if(!function_exists('wcmc_delete_list_category')) {
    /**
     * @since  1.9.1
     */
    function wcmc_delete_list_category( $cate_ID = [] ) {

        return delete_list_product_category($cate_ID);
    }
}
if(!function_exists('get_product_meta')) {
    function get_product_meta( $product_id, $key = '', $single = true) {
        $data = metadata::get('product', $product_id, $key, $single);
        return $data;
    }
}
if(!function_exists('update_product_meta')) {
    function update_product_meta($product_id, $meta_key, $meta_value) {
        return update_metadata('product', $product_id, $meta_key, $meta_value);
    }
}
if(!function_exists('delete_product_meta')) {
    function delete_product_meta($product_id, $meta_key = '', $meta_value = '') {
        return delete_metadata('product', $product_id, $meta_key, $meta_value);
    }
}
if(!function_exists('get_product_category_meta')) {
    function get_product_category_meta( $cateID, $key = '', $single = true) {
        $data = metadata::get('products_categories', $cateID, $key, $single);
        return $data;
    }
}
if(!function_exists('update_product_category_meta')) {
    function update_product_category_meta($cateID, $meta_key, $meta_value) {
        return update_metadata('products_categories', $cateID, $meta_key, $meta_value);
    }
}
if(!function_exists('delete_product_category_meta')) {
    function delete_product_category_meta($cateID, $meta_key = '', $meta_value = '') {
        return delete_metadata('products_categories', $cateID, $meta_key, $meta_value);
    }
}
/** Suppliers */
if(!function_exists('get_suppliers')) {
    function get_suppliers( $args = []) {
        return Suppliers::get($args);
    }
}

if(!function_exists('get_suppliers_by')) {
    function get_suppliers_by($field, $value, $params = []) {
        return Suppliers::getBy($field, $value, $params);
    }
}

if(!function_exists('gets_suppliers')) {
    function gets_suppliers( $args = []) {
        return Suppliers::gets($args);
    }
}

if(!function_exists('gets_suppliers_by')) {
    function gets_suppliers_by( $field, $value, $params = []) {
        return Suppliers::getsBy($field, $value, $params);
    }
}

if(!function_exists('count_suppliers')) {
    function count_suppliers( $args = []) {
        return Suppliers::count($args);
    }
}

if(!function_exists('insert_suppliers')) {
    function insert_suppliers( $suppliers = []) {
        return Suppliers::insert($suppliers);
    }
}

if( !function_exists('delete_suppliers') ) {
    function delete_suppliers( $suppliersID = 0, $trash = false ) {
        return Suppliers::delete($suppliersID, $trash);
    }
}

if( !function_exists('delete_list_suppliers') ) {
    function delete_list_suppliers( $suppliersID = [], $trash = false ) {
        return Suppliers::deleteList($suppliersID, $trash);
    }
}

if(!function_exists('get_suppliers_meta')) {
    function get_suppliers_meta( $suppliers_id, $key = '', $single = true) {
        return Suppliers::getMeta($suppliers_id, $key, $single);
    }
}

if(!function_exists('update_suppliers_meta')) {
    function update_suppliers_meta($suppliers_id, $meta_key, $meta_value) {
        return Suppliers::updateMeta($suppliers_id, $meta_key, $meta_value);
    }
}

if(!function_exists('delete_suppliers_meta')) {
    function delete_suppliers_meta($suppliers_id, $meta_key = '', $meta_value = '') {
        return Suppliers::deleteMeta($suppliers_id, $meta_key, $meta_value);
    }
}

if(!function_exists('gets_suppliers_option')) {
    function gets_suppliers_option() {
        return Suppliers::getsOption();
    }
}
/** Brands */
if(!function_exists('get_brands')) {
    function get_brands( $args = [] ) {
        return Brand::get($args);
    }
}

if(!function_exists('get_brands_by')) {
    function get_brands_by( $field, $value, $params = [] ) {
        return Brand::getBy($field, $value, $params);
    }
}

if(!function_exists('gets_brands')) {
    function gets_brands( $args = [] ) {
        return Brand::gets($args);
    }
}

if(!function_exists('gets_brands_by')) {
    function gets_brands_by( $field, $value, $params = [] ) {
        return Brand::getsBy($field, $value, $params);
    }
}

if(!function_exists('count_brands')) {
    function count_brands($args = []) {
        return Brand::count($args);
    }
}

if(!function_exists('insert_brands')) {
    function insert_brands($brands = []) {
        return Brand::insert($brands);
    }
}

if(!function_exists('delete_brands')) {
    function delete_brands( $brandsID = 0) {
        return Brand::delete($brandsID);
    }
}

if(!function_exists('delete_list_brands')) {
    function delete_list_brands( $brandsID = []) {
        return Brand::deleteList($brandsID);
    }
}

if(!function_exists('get_brands_meta')) {
    function get_brands_meta( $brands_id, $key = '', $single = true) {
        return Brand::getMeta($brands_id, $key, $single);
    }
}

if(!function_exists('update_brands_meta')) {
    function update_brands_meta($brands_id, $meta_key, $meta_value) {
        return Brand::updateMeta($brands_id, $meta_key, $meta_value);
    }
}

if(!function_exists('delete_brands_meta')) {
    function delete_brands_meta($brands_id, $meta_key = '', $meta_value = '') {
        return Brand::deleteMeta($brands_id, $meta_key, $meta_value);
    }
}

if(!function_exists('gets_brands_option')) {
    function gets_brands_option() {
        return Brand::getsOption([]);
    }
}
/**
 * HỖ TRỢ VERSION 2.1.0 TRỞ XUỐNG
 */
if(!function_exists('wcmc_get_template')) {
    function wcmc_get_template($template_path = '' , $args = '', $return = false) {
        return Prd::template($template_path, $args, $return);
    }
}
if(!function_exists('wcmc_get_include')) {

    function wcmc_get_include( $template_path = '' , $args = '', $return = false) {

        return Prd::templateInclude($template_path, $args, $return);
    }
}
if(!function_exists('wcmc_get_template_version')) {
    /**
     * Print a single notice immediately.
     * @since 2.3.2
     */
    function wcmc_get_template_version() {

        $ci =& get_instance();

        $path = VIEWPATH.$ci->data['template']->name.'/sicommerce/version.php';

        $version = '1.0.0';

        if(!file_exists($path)) {

            $path = $ci->plugin->dir.'/sicommerce/template/version.php';

            if(!file_exists($path)) {

                return $version;
            }
        }

        $string = file($path);

        $count 	= 0;

        foreach ($string as $k => $val) {

            $val 		= trim($val);

            $pos_start  = stripos($val,' * ')+1;

            $pos_end    = strlen($val);

            //Template name
            if(strpos($val,'@version',0) 	!== false) {
                $version 	= trim(substr($val, $pos_start, $pos_end)); $count++;
            }
        }

        $version = str_replace('@version','', $version);

        $version = trim($version);

        return $version;
    }
}

Class Brand {

    static public function get($args = []) {
        return Brands::get($args);
    }

    static public function getBy( $field, $value, $params = [] ) {
        return Brands::getBy($field, $value, $params);
    }

    static public function gets( $args = [] ) {
        return Brands::gets($args);
    }

    static public function getsBy( $field, $value, $params = [] ) {
        return Brands::getsBy($field, $value, $params);
    }

    static public function count( $args = [] ) {
        return Brands::count($args);
    }

    static public function insert( $brands = [] ) {
        return Brands::insert($brands);
    }

    static public function delete( $brandsID = 0) {
        return Brands::delete($brandsID);
    }

    static public function deleteList( $brandsID = []) {
        return Brands::deleteList($brandsID);
    }

    static public function getMeta( $brands_id, $key = '', $single = true) {
        return Brands::getMeta($brands_id, $key, $single);
    }

    static public function updateMeta($brands_id, $meta_key, $meta_value) {
        return Brands::updateMeta($brands_id, $meta_key, $meta_value);
    }

    static public function deleteMeta($brands_id, $meta_key = '', $meta_value = '') {
        return Brands::deleteMeta($brands_id, $meta_key, $meta_value);
    }

    static public function getsOption($args = []) {
        return Brands::getsOption($args);
    }
}

if(!function_exists('productItemStyle')) {
    function productItemStyle($key = '') {
        return Prd::itemStyle($key);
    }
}
if(!function_exists('_price_currency')) {
    function _price_currency() {
        return Prd::priceUnit();
    }
}
if(!function_exists('_price_none')) {
    function _price_none() {
        return Prd::priceNone();
    }
}
if(!function_exists('scmc_template')) {
    function scmc_template( $template_path = '' , $args = '', $return = false ) {
        return Prd::template($template_path, $args, $return);
    }
}
if(!function_exists('scmc_include')) {
    function scmc_include( $template_path = '' , $args = '', $return = false) {
        return Prd::templateInclude($template_path, $args, $return);
    }
}