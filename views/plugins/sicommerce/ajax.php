<?php
use Illuminate\Database\Capsule\Manager as DB;
if(!function_exists('ajax_product_controller')) {
    function ajax_product_controller( $ci, $model ) {
        $data = productControllerIndex('post');
        $result['status']  = 'success';
        $result['message'] = __('Lưu dữ liệu không thành công');
        $result['pagination']   = is_object($data['pagination']) ? $data['pagination']->frontend() : '';
        $result['count']    = $data['total'];;
        $result['list']     = '';

        if(have_posts($data['objects'])) {
            foreach ($data['objects'] as $object) {
                $category_row_count        = option::get('category_row_count');
                $category_row_count_tablet = option::get('category_row_count_tablet');
                $category_row_count_mobile = option::get('category_row_count_mobile');
                $col = [];
                $col['lg'] = ( $category_row_count != 5) ? 12/$category_row_count : 15;
                $col['md'] = ( $category_row_count != 5) ? 12/$category_row_count : 15;
                $col['sm'] = ( $category_row_count_tablet != 5) ? 12/$category_row_count_tablet : 15;
                $col['xs'] = ( $category_row_count_mobile != 5) ? 12/$category_row_count_mobile : 15;
                $col = 'col-xs-'.$col['xs'].' col-sm-'.$col['sm'].' col-md-'.$col['md'].' col-lg-'.$col['lg'].'';
                $result['list'] .= '<div class="'.$col.'">'.Prd::template( 'loop/item_product', ['val' => $object], true ).'</div>';
            }
        }
        echo json_encode($result);
        return true;
    }
    Ajax::client('ajax_product_controller');
}