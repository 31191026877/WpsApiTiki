<form method="post" accept-charset="utf-8" id="form-input" data-module="brands">
    <?php
        Admin::partial('include/form/form', ['object' => (isset($object) && have_posts($object)) ? $object : []]);
    ?>
</form>