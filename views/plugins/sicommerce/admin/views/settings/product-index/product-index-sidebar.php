<div class="box">
    <div class="header"> <h2>Sidebar</h2> </div>
    <div class="box-content">
        <table class="table table-hover">
            <tbody>
                <tr class="active">
                    <td colspan="3"><strong>Danh mục sản phẩm</strong></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        foreach (Language::list() as $languageKey => $language) {
                            $label = 'Tiêu đề';
                            $key = 'title';
                            if($languageKey != Language::default()) $key .= '_'.$languageKey;
                            if(Language::hasMulti()) $label .= '('.$language['label'].')';
                            $input = array('field' => 'product_sidebar[category]['.$key.']', 'type' => 'text', 'label' => $label);
                            echo FormBuilder::render($input, sicommerce::config('product_sidebar.category.'.$key));
                        }
                        ?>
                    </td>
                    <td>
                        <?php  $input = array('field' => 'product_sidebar[category][enable]', 'type' => 'switch', 'label' => 'Bật tắt item'); ?>
                        <?php echo FormBuilder::render($input, sicommerce::config('product_sidebar.category.enable'));?>
                    </td>
                    <td>
                        <?php  $input = array('field' => 'product_sidebar[category][order]', 'type' => 'number', 'label' => 'Thứ tự hiển thị'); ?>
                        <?php echo FormBuilder::render($input, sicommerce::config('product_sidebar.category.order'));?>
                    </td>
                </tr>
                <tr class="active">
                    <td colspan="3"><strong>Sản phẩm bán chạy</strong></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        foreach (Language::list() as $languageKey => $language) {
                            $label = 'Tiêu đề';
                            $key = 'title';
                            if($languageKey != Language::default()) $key .= '_'.$languageKey;
                            if(Language::hasMulti()) $label .= '('.$language['label'].')';
                            $input = array('field' => 'product_sidebar[selling]['.$key.']', 'type' => 'text', 'label' => $label);
                            echo FormBuilder::render($input, sicommerce::config('product_sidebar.selling.'.$key));
                        }
                        ?>
                    </td>
                    <td>
                        <?php  $input = array('field' => 'product_sidebar[selling][enable]', 'type' => 'switch', 'label' => 'Bật tắt item'); ?>
                        <?php echo FormBuilder::render($input, sicommerce::config('product_sidebar.selling.enable'));?>
                    </td>
                    <td>
                        <?php  $input = array('field' => 'product_sidebar[selling][order]', 'type' => 'number', 'label' => 'Thứ tự hiển thị'); ?>
                        <?php echo FormBuilder::render($input, sicommerce::config('product_sidebar.selling.order'));?>
                    </td>
                </tr>

                <tr class="active">
                    <td colspan="3"><strong>Sản phẩm nổi bật</strong></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        foreach (Language::list() as $languageKey => $language) {
                            $label = 'Tiêu đề';
                            $key = 'title';
                            if($languageKey != Language::default()) $key .= '_'.$languageKey;
                            if(Language::hasMulti()) $label .= '('.$language['label'].')';
                            $input = array('field' => 'product_sidebar[hot]['.$key.']', 'type' => 'text', 'label' => $label);
                            echo FormBuilder::render($input, sicommerce::config('product_sidebar.hot.'.$key));
                        }
                        ?>
                    </td>
                    <td>
                        <?php  $input = array('field' => 'product_sidebar[hot][enable]', 'type' => 'switch', 'label' => 'Bật tắt item'); ?>
                        <?php echo FormBuilder::render($input, sicommerce::config('product_sidebar.hot.enable'));?>
                    </td>
                    <td>
                        <?php  $input = array('field' => 'product_sidebar[hot][order]', 'type' => 'number', 'label' => 'Thứ tự hiển thị'); ?>
                        <?php echo FormBuilder::render($input, sicommerce::config('product_sidebar.hot.order'));?>
                    </td>
                </tr>

                <tr class="active">
                    <td colspan="3"><strong>Sản phẩm khuyễn mãi</strong></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        foreach (Language::list() as $languageKey => $language) {
                            $label = 'Tiêu đề';
                            $key = 'title';
                            if($languageKey != Language::default()) $key .= '_'.$languageKey;
                            if(Language::hasMulti()) $label .= '('.$language['label'].')';
                            $input = array('field' => 'product_sidebar[sale]['.$key.']', 'type' => 'text', 'label' => $label);
                            echo FormBuilder::render($input, sicommerce::config('product_sidebar.sale.'.$key));
                        }
                        ?>
                    </td>
                    <td>
                        <?php  $input = array('field' => 'product_sidebar[sale][enable]', 'type' => 'switch', 'label' => 'Bật tắt item'); ?>
                        <?php echo FormBuilder::render($input, sicommerce::config('product_sidebar.sale.enable'));?>
                    </td>
                    <td>
                        <?php  $input = array('field' => 'product_sidebar[sale][order]', 'type' => 'number', 'label' => 'Thứ tự hiển thị'); ?>
                        <?php echo FormBuilder::render($input, sicommerce::config('product_sidebar.sale.order'));?>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<style>
    table.table tr.active td {
        background-color: #eeeff0;
    }
</style>