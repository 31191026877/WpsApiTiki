<?php
if(isset($category) && have_posts($category) && !empty($category)) {
    echo '<div class="product-category-content-top">'.$category->excerpt.'</div>';
}