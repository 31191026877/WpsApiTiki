<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Taxonomy {
    static public function getCategory($cateType = '') {
        if(empty($cateType)) return get_instance()->taxonomy['list_cat'];
        if(static::hasCategory($cateType)) {
            return get_instance()->taxonomy['list_cat_detail'][$cateType];
        }
        return [];
    }
    static public function getCategoryDetail() {
        return get_instance()->taxonomy['list_cat_detail'];
    }
    static public function getCategoryByPost($object, $output = 'names') {
        $ci =& get_instance();
        $postType = (!empty($object->post_type)) ? $object->post_type : $object;
        if(static::hasPost($postType)) {
            $post = static::getPost($postType);
            if($output == 'names') return $post['taxonomies'];
            $taxonomy = [];
            foreach ($post['taxonomies'] as $val) {
                if(isset($ci->taxonomy['list_cat_detail'][$val])) $taxonomy[$val] = (object)$ci->taxonomy['list_cat_detail'][$val];
            }
            return (object) $taxonomy;
        }
        if($postType == 'products') {
            $taxonomy = [];
            foreach ($ci->taxonomy['list_cat_detail'] as $taxonomy_key => $taxonomy_value) {
                if( $taxonomy_value['post_type'] == $postType ) {
                    $taxonomy[$taxonomy_key] = (object)$taxonomy_value;
                }
            }
            return (object) $taxonomy;
        }
        return [];
    }
    static public function addCategory($cateType, $postType, $arg) {

        if(empty($postType) || empty($cateType)) return false;

        $ci =& get_instance();

        if(!static::hasCategory($cateType)) {

            $ci->taxonomy['list_cat'][] = $cateType;

            $arg = array_merge([
                'labels'            => [],
                'public'            => true,
                'menu_position'     => 3,
                'menu_icon'         => '',
                'parent'            => true,
                'show_admin_column' => false,
                'post_type'         => '',
                'supports'          => [],
                'capabilities'       => [
                    'edit'      => 'manage_categories',
                    'delete'    => 'delete_categories',
                ],
            ], $arg);

            $arg['labels']      = array_merge(
                [
                    'name'          => 'Danh sách chuyên mục',
                    'singular_name' => '',
                    'add_new_item'  => 'Thêm chuyên mục',
                ],
                $arg['labels']
            );

            $arg['supports']    = array_merge(
                [
                    'group'     => array( 'info', 'media', 'seo', 'theme', 'category' ),
                    'field'     => array( 'name', 'excerpt', 'content', 'image', 'public', 'slug', 'seo_title', 'seo_keywords', 'seo_description', 'theme_layout', 'theme_view', 'parent_id'),
                ],
                $arg['supports']
            );

            $arg['capabilities'] = array_merge(
                [
                    'edit'      => 'manage_categories',
                    'delete'    => 'delete_categories',
                ],
                $arg['capabilities']
            );

            if(isset($arg['admin_nav_header'])) { $arg['labels']['name'] = $arg['admin_nav_header']; unset($arg['admin_nav_header']); }

            if(isset($arg['name'])) { $arg['labels']['singular_name'] = $arg['name']; unset($arg['name']); }

            //exclude_from_search : loại bỏ khỏi kết quả tìm kiếm khi search bên ngoài frondend
            if(!isset($arg['exclude_from_search'])) $arg['exclude_from_search'] = $arg['public'];
            //show_in_nav_menus : Nếu có giá trị TRUE thì nó sẽ hiển thị bên trang quản lý menu.
            if(!isset($arg['show_in_nav_menus']))   $arg['show_in_nav_menus']   = 0;
            //show_in_admin_bar : Nếu có giá trị TRUE thì sẽ hiển thị một đường link trên thanh Admin Menu Bar
            if(!isset($arg['show_in_nav_admin']))   $arg['show_in_nav_admin']   = $arg['public'];
            $ci->taxonomy['list_cat_detail'][$cateType] = $arg;
            $ci->taxonomy['list_cat_detail'][$cateType]['post_type'] = $postType;
            if( isset($ci->taxonomy['list_post_detail'][$postType])) {
                $ci->taxonomy['list_post_detail'][$postType]['cate_type']      = $cateType;
                $ci->taxonomy['list_post_detail'][$postType]['taxonomies'][]   = $cateType;
            }
            return true;
        }

        return false;

    }
    static public function hasCategory($cateType) {
        if(empty($cateType)) return false;
        if(!have_posts(get_instance()->taxonomy['list_cat'])) return false;
        if(in_array($cateType, get_instance()->taxonomy['list_cat'] ) === false) return false;
        return true;
    }
    static public function removeCategory($cateType, $postType = '') {
        if(empty($cateType)) return false;
        $ci =& get_instance();
        foreach ($ci->taxonomy['list_cat'] as $key => $value) {
            if( $value == $cateType ) unset($ci->taxonomy['list_cat'][$key]); break;
        }
        unset($ci->taxonomy['list_cat_detail'][$cateType]);
        if(!empty($postType)) {
            unset($ci->taxonomy['list_post_detail'][$postType]['cate_type']);
        }
        return true;
    }
    static public function getPost($postType = '') {
        if(empty($postType)) return get_instance()->taxonomy['list_post'];
        if(static::hasPost($postType)) {
            return get_instance()->taxonomy['list_post_detail'][$postType];
        }
        return [];
    }
    static public function getPostDetail() {
        return get_instance()->taxonomy['list_post_detail'];
    }
    static public function addPost($postType, $arg) {
        if(empty($postType)) return false;
        if(!static::hasPost($postType)) {
            $ci =& get_instance();

            $ci->taxonomy['list_post'][] = $postType;

            $arg = array_merge(array(
                'labels' => array(
                    'name'          => '',
                    'singular_name' => ''
                ),
                'public'        => true,
                'menu_position' => 3,
                'menu_icon'     => '<img src="'.Admin::imgTemplateLink('icon-post.png').'" />',
                'cate_type'     => false,
                'taxonomies'    => [],
                'supports'      => [],
                'capabilities' => [
                    'view'      => 'view_posts',
                    'add'       => 'add_posts',
                    'edit'      => 'edit_posts',
                    'delete'    => 'delete_posts',
                ],
                'count'         => 0
            ), $arg);

            $arg['labels'] = array_merge(
                array(
                    'name'          => 'Bài viết',
                    'singular_name' => 'Bài viết',
                    'add_new_item'  => 'Thêm bài viết',
                    'edit_item'     => 'Thêm bài viết',
                ),
                $arg['labels']
            );

            $arg['supports'] = array_merge(
                array(
                    'group'     => array( 'info', 'media', 'seo', 'theme' ),
                    'field'     => array( 'title', 'excerpt', 'content', 'image', 'public', 'slug', 'seo_title', 'seo_keywords', 'seo_description', 'theme_layout', 'theme_view'),
                ),
                $arg['supports']
            );

            $arg['capabilities'] = array_merge(
                [
                    'view'      => 'view_posts',
                    'add'       => 'add_posts',
                    'edit'      => 'edit_posts',
                    'delete'    => 'delete_posts',
                ],
                $arg['capabilities']
            );

            if(isset($arg['admin_nav_header'])) { $arg['labels']['name'] = $arg['admin_nav_header']; unset($arg['admin_nav_header']); }

            if(isset($arg['name'])) { $arg['labels']['singular_name'] = $arg['name']; unset($arg['name']); }

            //exclude_from_search : loại bỏ khỏi kết quả tìm kiếm khi search bên ngoài frondend
            if(!isset($arg['exclude_from_search'])) $arg['exclude_from_search'] = $arg['public'];
            //show_in_nav_menus : Nếu có giá trị TRUE thì nó sẽ hiển thị bên trang quản lý menu.
            if(!isset($arg['show_in_nav_menus']))   $arg['show_in_nav_menus']   = 0;
            //show_in_nav_admin : Nếu có giá trị TRUE thì sẽ hiển thị một đường link trên thanh Admin Menu Bar
            if(!isset($arg['show_in_nav_admin']))   $arg['show_in_nav_admin']   = $arg['public'];

            $ci->taxonomy['list_post_detail'][$postType] = $arg;

            return true;
        }
        return false;
    }
    static public function hasPost($postType) {
        if(empty($postType)) return false;
        if(!have_posts(get_instance()->taxonomy['list_post'])) return false;
        if(in_array($postType, get_instance()->taxonomy['list_post'] ) === false) return false;
        return true;
    }
    static public function removePost($postType) {
        if(empty($postType)) return false;
        if(!static::hasPost($postType)) return false;
        $ci =& get_instance();
        foreach ($ci->taxonomy['list_post'] as $key => $value) {
            if( $value == $postType ) unset($ci->taxonomy['list_post'][$key]); break;
        }
        unset($ci->taxonomy['list_post_detail'][$postType]);
        return true;
    }
}
