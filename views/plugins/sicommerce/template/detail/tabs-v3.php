<?php
/**
 * product_tabs filters
 * @hook product_detail_tab_default filters
 */
$tabs = apply_filters( 'product_tabs', []);
if(have_posts($tabs)): ?>
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <?php foreach ( $tabs as $key => $tab ) : ?>
                <li class="nav-item <?php echo ($key == 'content') ? 'active' : '';?>">
                    <a class="nav-link" href="#tab-<?= $key;?>" aria-controls="<?= $key;?>" role="tab" data-toggle="tab"><?= __($tab['title'], 'product_tab_title_'.$key);?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <?php foreach ( $tabs as $key => $tab ) : ?>
                <div role="tabpanel" class="tab-pane <?= ($key == 'content')?'active':'';?>" id="tab-<?= $key;?>">
                    <?php call_user_func( $tab['callback'], $object ) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif;?>