<?php
class AdminMenu {
    static public function add($key, $title, $slug, $args) {
        $ci =&get_instance();
        if(!Arr::exists($ci->adminNav, $key)) {
            Arr::set($ci->adminNav, $key, array(
                'key' 		=> $key,
                'name' 		=> str::clear($title),
                'icon'  	=> (!empty($args['icon'])) ? xss_clean($args['icon']) : null,
                'url'   	=> str::clear($slug),
                'callback'  => (!empty($args['callback'])) ? str::clear($args['callback']) : null,
                'hidden'	=> !empty($args['hidden']) && (bool)str::clear($args['hidden']),
                'position'	=> (!empty($args['position'])) ? str::clear($args['position']) : null,
                'count'     => apply_filters('admin_nav_'.$key.'_count', (!empty($args['count'])) ? (int)$args['count'] : 0)
            ));
        }
        return false;
    }
    static public function addSub($parent_key ,$key, $title, $slug, $args = []) {

        $ci =& get_instance();

        Arr::set($ci->adminNavSub, $parent_key.'.'.$key , array(
            'key' 		=> $key,
            'name' 		=> str::clear($title),
            'url'   	=> str::clear($slug),
            'position'	=> (!empty($args['position'])) ? str::clear($args['position']) : null,
            'callback'  => (!empty($args['callback'])) ? str::clear($args['callback']) : null,
            'count'     => apply_filters('admin_subnav_'.$key.'_count', (!empty($args['count'])) ? (int)$args['count'] : 0)
        ));

        return true;
    }
    static public function remove($key, $parent_key = '') {
        if(empty($key)) return false;
        $ci =& get_instance();
        if(!empty($parent_key)) {
            if(Arr::exists($ci->adminNav, $parent_key) && Arr::exists($ci->adminNavSub[$parent_key], $key)) {
                unset($ci->adminNav[$parent_key][$key]);
                return true;
            }
        }
        elseif(Arr::exists($ci->adminNav, $key)) {
            unset($ci->adminNav[$key]);
            unset($ci->adminNavSub[$key]);
            return true;
        }
        return false;
    }
    static public function render($group = '', $active = '') {

        $ci =& get_instance();

        $cateType = $ci->taxonomy['list_cat_detail'];

        $cate_type_remove = [];

        $postType = $ci->taxonomy['list_post_detail'];

        foreach ($cateType as $cate_key => $cate ) {

            if( !empty($cate['capabilities']['edit']) ) {

                if(!Auth::hasCap($cate['capabilities']['edit'])) {
                    $cate_type_remove[] = $cate_key;
                }
            }
        }

        foreach ($postType as $key => $post) {

            if( $post['show_in_nav_admin'] == true) {

                if($key != 'post') {

                    if(Auth::hasCap($post['capabilities']['view'])) {
                        static::add($key, $post['labels']['name'], 'post/?post_type='.$key, [
                            'position' => 'page',
                            'icon' => $post['menu_icon'],
                            'count'=> (!empty($post['count'])) ? $post['count'] : 0
                        ]);
                    }

                    if(Auth::hasCap($post['capabilities']['view'])) {
                        static::addSub($key, $key, $post['labels']['singular_name'],'post/?post_type='.$key);
                    }

                    if(Auth::hasCap($post['capabilities']['add']))  {
                        static::addSub($key, $key.'-add','Thêm '.$post['labels']['singular_name'],'post/add/?post_type='.$key);
                    }
                }

                foreach ($post['taxonomies'] as $key_taxonomy) {
                    if( in_array( $key_taxonomy, $cate_type_remove) !== false ) continue;
                    if( isset($ci->taxonomy['list_cat_detail'][$key_taxonomy]) ) {
                        $taxonomy = $ci->taxonomy['list_cat_detail'][$key_taxonomy];
                        if( $taxonomy['show_in_nav_admin'] == true ){
                            static::addSub( $key, $key_taxonomy, $taxonomy['labels']['name'], 'post/post-categories?cate_type='.$key_taxonomy.'&post_type='.$key);
                        }
                    }
                }
            }
        }

        if(have_posts($ci->adminNav)) {

            /*****************************************************************
             * SORT NAV ADMIN
             * ***************************************************************/
            $temp_position 		= [];

            $temp_no_position 	= [];

            foreach ($ci->adminNav as $key_admin_nav => $admin_nav_value) {
                if( !empty($admin_nav_value['position']) ) {
                    $temp_position[$key_admin_nav] = $admin_nav_value;
                }
                else {
                    $temp_no_position[$key_admin_nav] = $admin_nav_value;
                }
            }

            foreach ($temp_position as $key_position => $value_position) {

                if( isset($temp_no_position[$value_position['position']]) ) {

                    $temp = [];

                    foreach ($temp_no_position as $position => $temp_no_position_value) {

                        $temp[$position] = $temp_no_position_value;

                        if( $value_position['position'] == $position ) {
                            $temp[$key_position] = $value_position;
                            unset($temp_position[$key_position]);
                        }

                    }

                    $temp_no_position = $temp;
                }
            }

            if( have_posts($temp_position) ) {

                foreach ($temp_position as $key_position => $value_position) {

                    if( isset($temp_no_position[$value_position['position']]) ) {

                        $temp = [];

                        foreach ($temp_no_position as $position => $temp_no_position_value) {

                            $temp[$position] = $temp_no_position_value;

                            if( $value_position['position'] == $position ) {
                                $temp[$key_position] = $value_position;
                            }

                        }

                        $temp_no_position = $temp;
                    }
                }

                $temp_no_position = array_merge($temp_no_position, $temp_position);
            }

            $ci->adminNav = $temp_no_position;

            /*****************************************************************
             * SORT SUBNAV ADMIN
             * ***************************************************************/

            $temp_position 		= [];

            $temp_no_position 	= [];

            foreach ($ci->adminNavSub as $nav =>  $subnav) {

                foreach ($subnav as $key_subnav => $value_subnav ) {

                    if( !empty($value_subnav['position']) ) {
                        $temp_position[$nav][$key_subnav] = $value_subnav;
                    }
                    else {
                        $temp_no_position[$nav][$key_subnav] = $value_subnav;
                    }

                }

            }

            foreach ($temp_position as $key_nav => $subnav) {

                foreach ( $subnav as $key_position => $value_position ) {

                    if( isset($temp_no_position[$key_nav][$value_position['position']]) ) {

                        $temp = [];

                        foreach ($temp_no_position[$key_nav] as $position => $temp_no_position_value) {

                            $temp[$position] = $temp_no_position_value;

                            if( $value_position['position'] == $position ) {
                                $temp[$key_position] = $value_position;
                                unset($temp_position[$key_nav][$key_position]);
                            }

                        }

                        $temp_no_position[$key_nav] = $temp;
                    }

                }
            }

            foreach ($temp_position as $key_nav => $subnav) {

                if( have_posts($subnav) ) {

                    foreach ( $subnav as $key_position => $value_position ) {

                        if( isset($temp_no_position[$key_nav][$value_position['position']]) ) {

                            $temp = [];

                            foreach ($temp_no_position[$key_nav] as $position => $temp_no_position_value) {

                                $temp[$position] = $temp_no_position_value;

                                if( $value_position['position'] == $position ) {
                                    $temp[$key_position] = $value_position;
                                    unset($temp_position[$key_nav][$key_position]);
                                }

                            }

                            $temp_no_position[$key_nav] = $temp;
                        }

                    }
                }
            }

            $ci->adminNavSub = $temp_no_position;

            $output = '';

            $adminNav 		= $ci->adminNav;

            $adminNavSub 	= $ci->adminNavSub;

            $slug = str_replace( base_url().URL_ADMIN.'/', '', Url::current() );

            $group_active = '';

            $sub_active = '';

            foreach ($adminNav as $key => $nav) {

                if( $nav['url'] == $slug ) {

                    $group_active = $key;

                    break;
                }
            }

            foreach ($adminNavSub as $key_nav => $nav_sub) {

                foreach ($nav_sub as $key_sub => $sub) {

                    if( $sub['url'] == $slug ) {

                        $sub_active = $key_sub;

                        if( $group_active == '' ) $group_active = $key_nav;

                        break;
                    }

                }
            }

            if($group_active != '') $group = $group_active;

            if($sub_active != '' ) $active = $sub_active;

            foreach ($adminNav as $key => $item) {

                if( isset($item['hidden']) && $item['hidden'] == true ) continue;

                $item = (object)$item;

                $current = (isset($group) && ($key == $group))?'has-current-submenu':'not-current-submenu';

                $output .= '<li class="has-submenu menu-top '.$current.'">';
                $output .= '<a href="'.URL_ADMIN.'/'.$item->url.'" class="has-submenu '.$current.' menu-top">';
                $output .= '<div class="menu-arrow"><div></div></div>';
                $output .= '<div class="menu-image">'.$item->icon.'</div>';
                $output .= '<div class="menu-name">'.$item->name.'</div>';
                if(!empty($item->count)) {
                    $output .= '<div class="menu-count">'.$item->count.'</div>';
                }
                $output .= '</a>';

                if( isset( $adminNavSub[$key] ) ) {

                    $output .= '<ul class="submenu submenu-wrap">';

                    foreach ($adminNavSub[$key] as $sub_key => $sub ): $sub = (object)$sub;

                        $current_sub = (isset($active) && ($sub_key == $active))?'current':'';

                        $output .= '<li class="'.$current_sub.' '.$sub_key.'---'.$active.'">';

                        $output .= '<a class="'.$current_sub.'" href="'.URL_ADMIN.'/'.$sub->url.'">';
                        $output .= $sub->name;

                        if(!empty($sub->count)) {

                            $output .= '<div class="menu-count">'.$sub->count.'</div>';
                        }

                        $output .= '</a>';

                        $output .= '</li>';

                    endforeach;

                    $output .= '</ul>';

                }

                $output .= '</li>';
            }
            echo $output;
        }
    }
}
class ThemeMenu {
    static public function addLocation($key, $label = null) {
        $ci =& get_instance();
        if(empty($label) && Arr::accessible($key)) {
            foreach ($key as $menu_loca_key => $name) {
                $ci->navigation[$menu_loca_key] = $name;
            }
            return true;
        }
        else {
            $ci->navigation[$key] = $label;
            return true;
        }
        return false;

    }
    static public function get($args = [] ) {

        if(is_numeric($args)) {
            $cacheId = 'menu_location_'.(int)$args;
            if(CacheHandler::has($cacheId)) return apply_filters('get_menu', CacheHandler::get($cacheId), $args);
            $args = Qr::set('id', $args);
        }

        if(is_array($args)) {
            $args['where']['object_type'] = 'menu';
            $args = Qr::convert($args);
        }

        if(!$args instanceof Qr) return [];

        if(empty($args->orders)) $args->orderBy('order')->orderBy('created');

        $args->where('object_type', 'menu');

        $menu = model('group')->get($args);

        if(have_posts($menu)) {
            if(Str::isSerialized($menu->options)) {
                $menu->options = unserialize($menu->options);
                if(have_posts($menu->options)) {
                    $options = [];
                    foreach ($menu->options as $keyMenu => $option) {
                        $option = trim($option, '{');
                        $option = trim($option, '}');
                        $options[$keyMenu] = $option;
                    }
                    $menu->options = $options;
                }
            }
        }

        if(!empty($cacheId) && have_posts($menu)) {
            CacheHandler::save($cacheId, $menu);
        }

        return apply_filters('get_menu', $menu, $args);
    }
    static public function gets($args = []) {

        if(is_array($args)) {
            $args['where']['object_type'] = 'menu';
            $args = Qr::convert($args);
        }

        if(!$args instanceof Qr) return [];

        if(empty($args->orders)) $args->orderBy('order')->orderBy('created');

        $args->where('object_type', 'menu');

        $menus = model('group')->gets($args);

        if(have_posts($menus)) {
            foreach ($menus as $keyMenus => $menu) {
                if(isset($menu->options) && Str::isSerialized($menu->options)) {
                    $menu->options = unserialize($menu->options);
                    if(have_posts($menu->options)) {
                        $options = [];
                        foreach ($menu->options as $keyMenu => $option) {
                            $option = trim($option, '{');
                            $option = trim($option, '}');
                            $options[$keyMenu] = $option;
                        }
                        $menus[$keyMenus]->options = $options;
                    }
                }
            }
        }

        return apply_filters( 'gets_menu', $menus, $args );
    }
    static public function toSql($args = []) {

        if(is_array($args)) {
            $args['where']['object_type'] = 'menu';
            $args = Qr::convert($args);
        }

        if(!$args instanceof Qr) return '';

        if(empty($args->orders)) $args->orderBy('order')->orderBy('created');

        $args->where('object_type', 'menu');

        return  model('group')->toSql($args);
    }
    static public function getByID($id) {
        $args = Qr::set('id', $id)->where('object_type', 'menu');
        return model('group')->get($args);
    }
    static public function getByLocation($location) {
        if(empty($location)) return [];
        $cacheId = 'menu_location_'.$location;
        if(CacheHandler::has($cacheId)) return CacheHandler::get($cacheId);
        $args = Qr::set('options', 'like', '%{'.$location.'}%');
        $menu = self::get($args);
        if(have_posts($menu)) CacheHandler::save($cacheId, $menu);
        return $menu;
    }
    static public function getItem($args = []) {

        $menuItem = [];

        if(is_numeric($args) || $args instanceof Qr) {
            $args = self::handleParamsItem($args);
            $menuItem = model('menu')->get($args);
        }

        if(is_array($args)) {
            $args = self::handleParamsItemArr($args);
            $menuItem = model('menu')->get_data($args, 'menu');
        }

        if(have_posts($menuItem)) {
            $menuItem = static::setItemDataOptions($menuItem);
        }
        return apply_filters('get_menu_item', $menuItem);
    }
    static public function getsItem($args = []) {
        $menuItem = [];

        if(is_numeric($args) || $args instanceof Qr) {
            $args = self::handleParamsItem($args);
            $menuItem = model('menu')->gets($args);
        }

        if(is_array($args)) {
            $args = self::handleParamsItemArr($args);
            $menuItem = model('menu')->gets_data($args, 'menu');
        }

        return apply_filters('gets_menu_item', $menuItem);
    }
    static public function getData($id) {

        if(is_string($id) && !is_numeric($id)) {
            $group = ThemeMenu::getByLocation($id);
        }
        else {
            $group = ThemeMenu::get($id);
        }

        $menuItems = [];

        if(have_posts($group)) {

            $cacheID = 'menu_items_'.$group->id;

            $cacheID = apply_filters( 'get_data_menu_capcheID', $cacheID);

            if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);

            $menuItems = static::getsItem(Qr::set('menu_id', $group->id)->where('parent_id', 0)->select('menu.id', 'name', 'slug', 'type', 'object_type', 'menu_id', 'parent_id', 'object_id'));
            $menuItems = static::getItems($menuItems, Qr::set('menu_id', $group->id)->select('menu.id', 'name', 'slug', 'type', 'object_type', 'menu_id', 'parent_id', 'object_id')->orderBy('order')->orderBy('created'));
            if (have_posts($menuItems)) {
                foreach ($menuItems as &$menuItem) {
                    $menuItem = static::setItemDataOptions($menuItem);
                }
                $menuItems = apply_filters('get_data_menu', $menuItems);
            }

            if(have_posts($menuItems)) CacheHandler::save($cacheID, $menuItems);
        }

        return apply_filters( 'get_data_menu' , $menuItems );
    }
    static public function getItems($items = [], $args = []) {
        if(Arr::accessible($items)) {
            foreach ($items as $key => $value) {
                if($args->isWhere('parent_id')) { $args->removeWhere('parent_id'); }
                $args->where('parent_id', $value->id);
                $items[$key]->child = model('menu')->gets($args);
                ThemeMenu::getItems($items[$key]->child, $args);
            }
        }
        else {
            if($args->isWhere('parent_id')) $args->removeWhere('parent_id');
            $args->where('parent_id', $items->id);
            $items->child = model('menu')->gets($args);
            ThemeMenu::getItems($items->child, $args);
            return $items->child;
        }
        return $items;
    }
    static public function setKeyToString($items) {

        $itemKey = [];

        if(have_posts($items)) {
            foreach ($items as $key => $item) {

                $itemKey[$key.'a'] = $item;

                if(have_posts($item->child)) {

                    $itemKey[$key.'a']->child = ThemeMenu::setKeyToString($item->child);
                }
            }
        }

        return $itemKey;
    }
    static public function setItemDataOptions($item) {
        $item->data = (array)Metadata::get('menu', $item->id);
        if(isset($item->child) && have_posts($item->child)) {
            foreach ($item->child as $item_child_key => $itemChild) {
                $item->child[$item_child_key] = ThemeMenu::setItemDataOptions($itemChild);
            }
        }

        return $item;
    }
    static public function addItemOption($module = 'menu', $args = []) {

        if(empty($args['field']) && empty($args['name'])) return false;

        $key = empty($args['field']) ? Str::clear($args['name']) : Str::clear($args['field']);

        $module = (!empty($module)) ? Str::clear($module) : 'menu';

        $ci =& get_instance();

        if($module == 'menu' || $module == 'page') {
            $ci->menuOptions[$module][$key] = $args;
        }
        else if($module == 'post') {

            $postType = (!empty($args['post_type'])) ? Str::clear($args['post_type']) : 'all';

            $ci->menuOptions[$module][$postType][$key] = $args;
        }
        else if($module == 'post_categories') {

            $cateType = (!empty($args['cate_type'])) ? Str::clear($args['cate_type']) : 'all';

            $ci->menuOptions[$module][$cateType][$key] = $args;
        }
        else {
            $ci->menuOptions = apply_filters('theme_menu_add_item_'.$module.'_option', $ci->menuOptions, $args);
        }

        return true;
    }
    static public function getItemOption($object = '', $object_type = '') {

        $ci =& get_instance();

        if(empty($object_type)) {
            if(empty($object) && isset($ci->menuOptions['menu'])) {
                return $ci->menuOptions['menu'];
            }
            else if($object == 'post' && isset($ci->menuOptions['post']['all'])) {
                return $ci->menuOptions['post']['all'];
            }
            else if($object == 'categories' && isset($ci->menuOptions['post_categories']['all'])) {
                return $ci->menuOptions['post_categories']['all'];
            }
            else if(isset($ci->menuOptions[$object]['all'])) {
                return $ci->menuOptions[$object]['all'];
            }
        }
        else {

            if($object == 'post' && isset($ci->menuOptions['post'][$object_type])) {

                return $ci->menuOptions['post'][$object_type];
            }
            else if($object == 'categories' && isset($ci->menuOptions['post_categories'][$object_type])) {

                return $ci->menuOptions['post_categories'][$object_type];
            }
            else if(isset($ci->menuOptions[$object][$object_type])) {

                return $ci->menuOptions[$object][$object_type];
            }
        }
        return [];
    }
    static public function render($args) {
        $def    = ['theme_location' => 0, 'walker' => 'walker_nav_menu'];
        $args   = array_merge($def, $args);
        $menu 	= [];
        if(!empty($args['theme_location'])) {
            $menu = ThemeMenu::getByLocation($args['theme_location']);
        }
        else if(!empty($args['theme_id'])) {
            $menu = ThemeMenu::get($args['theme_id']);
        }

        if(have_posts($menu)){

            $items 	=  static::getData($menu->id);

            if(!class_exists($args['walker'])) $args['walker'] = 'walker_nav_menu';

            if(have_posts($items)) {
                $walker = new $args['walker'];
                $output = null;
                $depth  = 0;
                foreach($items as $key => $item) {
                    $walker->start_el($output, $item, $depth, [], $key);
                    if(isset($item->child) && have_posts($item->child)) static::renderSub($output, $item->child,$walker, $depth+1);
                    $walker->end_el($output, $item, $depth, [], $key);
                }
                echo $output;
            }
        }
        else echo "no menu";
    }
    static public function renderSub(&$output, $items, $walker, $depth) {
        if(have_posts($items)) {
            $walker->start_lvl($output, $depth);
            foreach($items as $item) {
                $walker->start_el($output, $item, $depth);
                if(isset($item->child) && have_posts($item->child)) static::renderSub($output, $item->child,$walker, $depth+1);
                $walker->end_el($output, $item, $depth);
            }
            $walker->end_lvl($output, $depth);
        }
    }
    static public function insert($insertData = []) {

        $columnsTable = [
            'name'         => ['string'],
            'object_type'  => ['string', 'menu'],
            'options'      => ['string'],
            'order'        => ['int', 0],
            'public'       => ['int', 1],
        ];

        $columnsTable = apply_filters('columns_db_menu', $columnsTable);

        if(!empty($insertData['id'])) {

            $id             = (int) $insertData['id'];
            $update        = true;
            $oldObject = static::get($id);
            if (!$oldObject) return new SKD_Error('invalid_page_id', __('ID menu không chính xác.'));
        }
        else {

            if(empty($insertData['name'])) return new SKD_Error('empty_menu_name', __('Không thể thêm menu khi tên menu để trống.', 'empty_menu_name'));
            $update = false;
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $object_type    = 'menu';

        if(is_array($options)) {
            foreach ($options as $key => $option) {
                $option = '{'.$option.'}';
                $options[$key] = $option;
            }

            $options = serialize($options);
        }

        $data = compact(array_keys($columnsTable));

        $data = apply_filters('pre_insert_menu_data', $data, $insertData, $update ? $oldObject : null);

        $model = model('group');

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set('id', $id));
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $id = $model->add($data);
        }

        CacheHandler::delete('menu_location_', true);

        return apply_filters('after_insert_menu', $id, $insertData, $data, $update ? $oldObject : null);
    }
    static public function update($update, $args) {

        if(!have_posts($update)) {
            return new SKD_Error('invalid_update', __('Không có trường dữ liệu nào được cập nhật.'));
        }

        if(!have_posts($args)) {
            return new SKD_Error('invalid_update', __('Không có điều kiện cập nhật.'));
        }

        if(is_array($args)) $args = Qr::convert($args);

        $update['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);

        return apply_filters('update_menu', model('group')->update($update, $args), $update, $args);
    }
    static public function delete($menuID = 0) {

        $menuID = (int)Str::clear($menuID);

        if($menuID == 0) return false;

        $model = model('group');

        $menu  = static::get($menuID);

        if(have_posts($menu)) {

            do_action('delete_menu', $menuID);

            if($model->delete(Qr::set('id', $menuID))) {

                do_action('delete_menu_success', $menuID);

                $menuItems = ThemeMenu::getsItem(Qr::set('menu_id', $menuID));

                foreach ($menuItems as $menuItem) {
                    Metadata::deleteByMid('menu', $menuItem->id);
                }

                $model->settable('menu')->delete(Qr::set('menu_id', $menuID));

                CacheHandler::delete('menu_items_'.$menuID, true);
                CacheHandler::delete('menu_location_', true);

                return [$menuID];
            }
        }

        return false;
    }
    static public function insertItem($menuItem = [] ) {

        $model = model('menu');

        if (!empty($menuItem['id'])) {

            $id            = (int)$menuItem['id'];

            $update        = true;

            $old_menuItem = static::getItem($id);

            if (!$old_menuItem) return new SKD_Error('invalid_post_id', __('ID menuItem không chính xác.'));

            $menuItem['name'] = (!empty($menuItem['name'])) ? $menuItem['name'] : $old_menuItem->name;

            $slug = empty($menuItem['slug']) ? $old_menuItem->slug : $menuItem['slug'];

            if(!Url::is($slug)) {
                $slug = Str::ascii($slug);
                $slug = str_replace(' ', '-', $slug);
            }

            $menuItem['type'] = (isset($menuItem['type'])) ? $menuItem['type'] : $old_menuItem->type;

            $menuItem['object_type'] = (isset($menuItem['object_type'])) ? $menuItem['object_type'] : $old_menuItem->object_type;

            $menuItem['data'] = (isset($menuItem['data'])) ? $menuItem['data'] : $old_menuItem->data;

            $menuItem['edit'] = (isset($menuItem['edit'])) ? $menuItem['edit'] : $old_menuItem->edit;

            $menuItem['menu_id'] = (isset($menuItem['menu_id'])) ? $menuItem['menu_id'] : $old_menuItem->menu_id;

            $menuItem['parent_id'] = (isset($menuItem['parent_id'])) ? $menuItem['parent_id'] : $old_menuItem->parent_id;

            $menuItem['level'] = (isset($menuItem['level'])) ? $menuItem['level'] : $old_menuItem->level;

            $menuItem['object_id'] = (isset($menuItem['object_id'])) ? $menuItem['object_id'] : $old_menuItem->object_id;

            $menuItem['order'] = (isset($menuItem['order'])) ? $menuItem['order'] : $old_menuItem->order;
        }
        else {

            $update = false;

            if(empty($menuItem['name'])) return new SKD_Error('empty_menuItem_title', __('Không thể cập nhật menuItem khi tiêu đề để trống.') );

            if(!empty($menuItem['slug'])) {
                $slug = $menuItem['slug'];
            }
            else {
                $slug = $menuItem['name'];
            }

            if(!Url::is($slug)) {
                $slug = Str::ascii($slug);
                $slug = str_replace(' ', '-', $slug);
            }
        }

        $name           = Str::clear($menuItem['name']);

        $type = (isset($menuItem['type'])) ? $menuItem['type'] : '';

        $object_type = (isset($menuItem['object_type'])) ? $menuItem['object_type'] : '';

        $data = (isset($menuItem['data'])) ? $menuItem['data'] : [];

        if(is_array($data)) $data = serialize($data);

        $edit = (isset($menuItem['edit'])) ? $menuItem['edit'] : 0;

        $menu_id = (isset($menuItem['menu_id'])) ? $menuItem['menu_id'] : 0;

        $parent_id = (isset($menuItem['parent_id'])) ? $menuItem['parent_id'] : 0;

        $level = (isset($menuItem['level'])) ? $menuItem['level'] : 0;

        $object_id = (isset($menuItem['object_id'])) ? $menuItem['object_id'] : 0;

        $order = (isset($menuItem['order'])) ? $menuItem['order'] : 0;

        $data = compact( 'name', 'slug', 'type', 'object_type', 'data', 'edit', 'menu_id', 'parent_id', 'level', 'object_id', 'order');

        $data = apply_filters( 'pre_insert_menu_item_data', $data, $menuItem, $update ? (int) $id : null );

        if ($update) {
            $model->settable('menu')->update( $data, Qr::set('id', $id));
            $menuItem_id = (int) $id;
        }
        else {
            $menuItem_id = $model->settable('menu')->add($data);
        }

        $model->settable('group');

        $menuItem_id  = apply_filters('after_insert_menuItem', $menuItem_id, $menuItem, $data, $update ? (int) $id : null);

        CacheHandler::delete('menu_items_'.$menu_id, true);

        return $menuItem_id;
    }
    static public function handleParamsItem($args) {
        if(is_numeric($args)) $args = Qr::set('id', $args);
        if(empty($args->orders)) $args->orderBy('order')->orderBy('created');
        return $args;
    }
    static public function handleParamsItemArr($args): array {
        if(!have_posts($args)) $args = ['where' => [], 'params' => []];
        $args = array_merge(['where' => [], 'params' => ['orderby' => 'order, created']], (is_array($args)) ? $args : []);
        return $args;
    }
}