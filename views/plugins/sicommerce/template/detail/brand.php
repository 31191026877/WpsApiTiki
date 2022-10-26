<?php if(!empty($object->brand_id)) {
    $brand = Brands::get(Qr::set($object->brand_id)->select('name'));
    echo '<p class="product-detail-brand">'.$brand->name.'</p>';
}