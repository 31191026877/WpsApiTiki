<?php

if( !isset($category)) $category = [];

$model = get_model('products');

$breadcrumb = $model->breadcrumb($category);

$breadcrumb[] = (object)array('name' => $object->title );

echo '<div class="breadcrumb">'.Breadcrumb($breadcrumb).'</div>';