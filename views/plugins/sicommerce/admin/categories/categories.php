<?php
include_once 'action-bar.php';
include_once 'ajax.php';
include_once 'form.php';
include_once 'menu.php';
include_once 'popover.php';
include_once 'table.php';
include_once 'quick-add.php';

function controller_products_categories_save($id, $insertData) {
    return ProductCategory::insert($insertData);
}

add_filter('form_submit_products_categories', 'controller_products_categories_save', 10, 2);