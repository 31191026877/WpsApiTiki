<?php
class skd_attribute_list_table extends SKD_list_table {

    function get_columns() {

        $this->_column_headers = array(
            'cb'               => 'cb',
            'title'            => 'Tên',
            'option_type'      => 'Loại',
            'order'            => 'Thứ tự',
            'action'           => 'Thao tác',
        );
        //ver 2.7.0
        $this->_column_headers = apply_filters( "manage_attribute_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    function column_title($item, $column_name) {
        ?>
        <strong><a href="<?php echo Url::admin('plugins?page=attribute&view=edit&id='.$item->id);?>"><?php echo $item->title;?></a></strong>
        <?php
    }

    function column_option_type($item, $column_name) {
        echo Attributes::type($item->option_type, 'label');
    }

    function column_action($item, $column_name) {
        $url = Url::admin('plugins?page=attribute&view=edit&id='.$item->id);
        if(Auth::hasCap('attributes_edit')) { ?>
            <a href="<?php echo $url;?>" class="btn btn-blue"><?php echo Admin::icon('edit');?></a>
        <?php }
        if(Auth::hasCap('attributes_delete')) { ?>
            <button class="btn btn-red js_btn_confirm" data-trash="disable" data-action="delete" data-ajax="Cms_Ajax_Action::delete" data-id="<?php echo $item->id;?>" data-module="Attributes" data-heading="Xóa Dữ liệu" data-description="Bạn chắc chắn muốn xóa thuộc tính <b><?php echo html_escape($item->title);?></b> ?"><?php echo Admin::icon('delete');?></button>
        <?php }
    }

    function column_default($column_name, $item, $global) {
        //ver 2.7.0
        do_action( 'manage_attribute_custom_column', $column_name, $item, $global);
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<p><a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a></p>';
    }
}