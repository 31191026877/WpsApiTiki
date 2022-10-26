<nav class="navigation d-none d-lg-block">
    <div class="container">
        <ul class="main-menu <?php echo Option::get('nav_position');?>">
            <?php ThemeMenu::render(['theme_location' => 'main-nav', 'walker' => 'store_bootstrap_nav_menu']);?>
        </ul>
    </div>
</nav>


