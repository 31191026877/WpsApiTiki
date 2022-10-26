<?php
function popover_supplier_search($object, $keyword) {
    return Suppliers::gets(Qr::set('name', 'like', '%'.$keyword.'%'));
}
add_filter('input_popover_supplier_search', 'popover_supplier_search', 10, 2);