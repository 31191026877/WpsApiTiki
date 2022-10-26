<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class walker_nav_menu{

	private $ci;

    function __construct() {

    }

    /**
    * Phương thức start_lvl()
    * Được sử dụng để hiển thị các thẻ bắt đầu cấu trúc của một cấp độ mới trong menu. (ví dụ: <ul class="sub-menu">)
    * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
    * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
    * @param array $args | Các tham số trong hàm wp_nav_menu()
    **/
    function start_lvl(&$output, $depth = 0, $arg = []) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu dropdownhover-bottom\">\n";
    }
    /**
    * Phương thức end_lvl()
    * Được sử dụng để hiển thị đoạn kết thúc của một cấp độ mới trong menu. (ví dụ: </ul> )
    * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
    * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
    * @param array $args | Các tham số trong hàm wp_nav_menu()
    **/
    function end_lvl(&$output, $depth = 0, $arg = []) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    /**
    * Phương thức start_el()
    * Được sử dụng để hiển thị đoạn bắt đầu của một phần tử trong menu. (ví dụ: <li id="menu-item-5"> )
    * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
    * @param string $item | Dữ liệu của các phần tử trong menu
    * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
    * @param array $args | Các tham số trong hàm wp_nav_menu()
    * @param interger $id | ID của phần tử hiện tại
    **/
    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {

        $class = (isset($item->child) && have_posts($item->child)) ?'dropdown ':'';
        $class .= isset($item->class) ?$item->class:'';

        $output .= '<li class="'.$class.'">';
        $atts = [];

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

        $output .= '<a '.$attributes.'>'.$item->name.'</a>';
    }
    /**
    * Phương thức end_el()
    * Được sử dụng để hiển thị đoạn kết thúc của một phần tử trong menu. (ví dụ: </li> )
    * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
    * @param string $item | Dữ liệu của các phần tử trong menu
    * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
    * @param array $args | Các tham số trong hàm wp_nav_menu()
    * @param interger $id | ID của phần tử hiện tại
    **/
    function end_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $output .= '</li>';
    }
}