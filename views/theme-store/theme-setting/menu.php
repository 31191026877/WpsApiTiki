<?php
/**
 * [store_register_nav_menus đăng ký các vị trí menu]
 * @return [type] [description]
 */
class store_bootstrap_nav_menu extends walker_nav_menu {

    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {

        if(!empty($item->data['target'])) {
            $item->target = $item->data['target'];
        }

        $ci =& get_instance();

        $class          = have_posts($item->child) ? 'has-dropdown ':'';

        $class          .= isset($item->class) ? $item->class :'';

        $slug           = $ci->uri->segment('1');

        if(Language::hasMulti()) {
            $slug  = $ci->uri->segment('2');
        }

        $slug           = Str::clear($slug);

        $slug           = (empty($slug)) ? 'trang-chu' : $slug;

        $check          = false;

        $icon = '';

        if($slug == $item->slug ) {
            $class .= ' active';
            $check = true;
        }
        if($depth == 0) {

            if(!empty($item->data['menuDisplay']) && $item->data['menuDisplay'] == 'mega') {
                $class .= ' mega-menu';
            }

            if(!empty($item->data['icon'])) {
                $icon = '<div class="icon">'.Template::img($item->data['icon'], $item->name, ['return' => true]).'</div>';
            }
        }

        if( !$check && is_page('products_index') && !empty($ci->data['category']) ) {

            $category = $ci->data['category'];

            while ($category->parent_id != 0 ) {
                $category = ProductCategory::get($category->parent_id);
            }

            if( $category->slug == $item->slug ) $class .= ' active';
        }

        $output         .= '<li class="'.$class.'">';

        $atts           = [];

        $atts['title']  = isset( $item->attr )   ? $item->attr       : '';
        $atts['target'] = isset( $item->target ) ? $item->target     : '';
        $atts['rel']    = isset( $item->xfn )    ? $item->xfn        : '';
        $atts['href']   = isset( $item->slug )   ? get_url($item->slug)       : '';
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $output .= '<a '.$attributes.'>'.$icon.'<span>'.$item->name.'</span></a>' ;
    }

    function end_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $output .= '</li>';
    }

    function start_lvl(&$output, $depth = 0, $arg = []) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"submenu\">\n";
    }
}

class store_bootstrap_nav_menu_footer extends walker_nav_menu {

    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {

        if(!empty($item->data['target'])) {
            $item->target = $item->data['target'];
        }

        $ci =& get_instance();

        $class          = have_posts($item->child) ?'dropdown ':'';

        $class          .= isset($item->class) ?$item->class:'';

        $slug           = $ci->uri->segment('1');

        if(Language::hasMulti()) {
            $slug  = $ci->uri->segment('2');
        }

        $slug           = Str::clear($slug);

        $slug           = (empty($slug)) ? 'trang-chu' : $slug;

        $check          = false;

        if( $slug == $item->slug ) {

            $class .= ' active';

            $check = true;
        }

        if( $check == false && is_page('products_index') && !empty($ci->data['category']) ) {

            $category = $ci->data['category'];

            while ( $category->parent_id != 0 ) {
                $category = wcmc_get_category( $category->parent_id );
            }

            if( $category->slug == $item->slug ) $class .= ' active';
        }

        $output         .= '<li class="'.$class.'">';

        $atts           = [];

        $atts['title']  = isset( $item->attr )   ? $item->attr       : '';
        $atts['target'] = isset( $item->target ) ? $item->target     : '';
        $atts['rel']    = isset( $item->xfn )    ? $item->xfn        : '';
        $atts['href']   = isset( $item->slug )   ? get_url($item->slug)       : '';
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $icon = '';

        $output .= '<a '.$attributes.'>'.$icon.'<span>'.$item->name.'</span></a>' ;
    }

    function end_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $output .= '</li>';
    }
}

class store_mobile_nav_menu extends walker_nav_menu {

    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $class          = isset($item->class) ?$item->class:'';
        $atts           = [];
        $atts['title']  = isset( $item->attr )   ? $item->attr       : '';
        $atts['target'] = isset( $item->target ) ? $item->target     : '';
        $atts['rel']    = isset( $item->xfn )    ? $item->xfn        : '';
        $atts['href']   = isset( $item->slug )   ? $item->slug       : '';
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $output .= '<li class="'.$class.'">';
        $output .= '<a '.$attributes.'>'.$item->name.'</a>' ;
    }

    function end_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
         $output .= '</li>';
    }

    function start_lvl(&$output, $depth = 0, $arg = []) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"\">\n";
    }
}

class store_mega_nav_menu extends walker_nav_menu {

    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {

        $class          = 'parent parent-megamenu ';
        $class          .= isset($item->class) ?$item->class:'';

        $ci = &get_instance();
        $slug = $ci->uri->segment(1);

        if($slug == $item->slug ) $class .= ' active';

        if($slug == '' && $item->slug == '#') $class .= ' active';

        $atts           = [];

        $atts['title']  = isset( $item->attr )   ? $item->attr       : '';
        $atts['target'] = isset( $item->target ) ? $item->target     : '';
        $atts['rel']    = isset( $item->xfn )    ? $item->xfn        : '';
        $atts['href']   = isset( $item->slug )   ? $item->slug       : '';
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $output .= '<li class="'.$class.'">';


        if(have_posts($item->child)) {
            $output .= '<a '.$attributes.'>'.$item->name.'</a><span class="toggle-submenu"></span>' ;
            $output .= '<div class="megamenu drop-menu " style="right: auto; left: 0px;">';
            $output .= '<ul>';
            $this->start_el_sub($output, $item, $item->child);
            $output .= '</ul>';
            $output .= '</div>';
            unset($item->child);
        }
        else  {
            $output .= '<a '.$attributes.'>'.$item->name.'</a>' ;
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $output .= '</li>';
    }

    function start_el_sub(&$output, $item, $subs) {
        foreach ($subs as $key => $value) {
            $image = '';
            $output .= '<li class="col-md-3">';
            $output .= '<strong class="title"><a href="'.get_url($value->slug).'" title="">'.$image.'<span>'.$value->name.'</span></a></strong>';
            $output .= '<ul class="list-submenu">';
            if(isset($value->child) && have_posts($value->child)) {
                foreach ($value->child as $key => $sub) {
                    $output .= '<a href="'.get_url($sub->slug).'">'.$sub->name.'</a>' ;
                }
            }
            $output .= '</ul>';
            $output .= '</li>';
        }
        
    }
}

$mobile_category_icon = Option::get('mobile_category_icon');

if(!empty($mobile_category_icon)) {
    ThemeMenu::addLocation('mobile-category-icon', 'Menu icon mobile');
}
