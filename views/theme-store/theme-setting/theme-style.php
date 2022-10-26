<?php
if(!function_exists('theme_add_assets'))  {
	function theme_add_assets() {
	    if(Admin::is()) return false;
		$assets         = Path::theme('assets/');
        $addOn         = $assets.'add-on/';
        if (!Device::isGoogleSpeed()) {
            $fontFamily = get_font_family();
            $fontGoogle = '';
            if (have_posts($fontFamily)) {
                foreach ($fontFamily as $key => $font) {
                    if ($font['type'] == 'google') {
                        $fontGoogle .= $font['load'] . '&';
                    }
                }
                $fontGoogle = trim($fontGoogle, '&');
                if (!empty($fontGoogle)) {
                    if (strpos($fontGoogle, 'wght')) {
                        $fontGoogle = str_replace('&', '&family=', $fontGoogle);
                        $fontGoogle = 'https://fonts.googleapis.com/css2?family=' . $fontGoogle;
                    } else {
                        $fontGoogle = 'https://fonts.googleapis.com/css?family=' . $fontGoogle;
                    }
                    Template::asset()->location('header')->add('fontGoogle', $fontGoogle);
                }
            }
            Template::asset()->location('header')->add('font-awesome', PLUGIN.'/font-awesome/css/all.min.css', ['minify' => false, 'path' => ['webfonts' => Url::base().'scripts/font-awesome']]);
        }
        Template::asset()->location('header')->add('reset',         $assets.'css/reset.css', ['minify' => true]);
        Template::asset()->location('header')->add('ToastMessages', PLUGIN.'/ToastMessages/jquery.toast.css', ['minify' => true]);
        Template::asset()->location('header')->add('bootstrap',     $addOn.'bootstrap-5.1.3/css/bootstrap.min.css', ['minify' => true]);
        Template::asset()->location('header')->add('bootstrap',     $addOn.'bootstrap-3.4.1/css/bootstrap.min.css', ['minify' => true]);
        Template::asset()->location('header')->add('dropdownhover', $addOn.'bootstrap-dropdownhover/bootstrap-dropdownhover.min.css', ['minify' => true]);
        Template::asset()->location('header')->add('slick',         $addOn.'slick/slick.css', ['minify' => true, 'path' => ['fonts' => Url::base().$addOn.'slick']]);
        Template::asset()->location('header')->add('slick',         $addOn.'slick/slick-theme.css', ['minify' => true, 'path' => ['fonts' => Url::base().$addOn.'slick']]);
        Template::asset()->location('header')->add('swiper',        $addOn.'swiper/swiper.min.css', ['minify' => true]);
        Template::asset()->location('header')->add('aos',           $addOn.'aos/aos.css', ['minify' => true]);
        Template::asset()->location('header')->add('fancybox',      $addOn.'fancybox-3/jquery.fancybox.min.css', ['minify' => true]);
        Template::asset()->location('header')->add('animate',       $addOn.'animate/animate.css', ['minify' => true]);
        Template::asset()->location('header')->add('mmenu',         $addOn.'mmenu/mmenu.css', ['minify' => true]);
        Template::asset()->location('header')->add('style',         $assets.'css/style.css', ['minify' => true]);
        Template::asset()->location('footer')->add('jquery-ui',     $assets.'js/jquery-ui-1.13.2.min.js', ['minify' => true]);
        Template::asset()->location('footer')->add('ToastMessages', PLUGIN.'/ToastMessages/jquery.toast.js', ['minify' => true]);
        Template::asset()->location('footer')->add('bootstrap',     $addOn.'bootstrap-5.1.3/js/bootstrap.bundle.min.js', ['minify' => true]);
        Template::asset()->location('footer')->add('bootstrap',     $addOn.'bootstrap-3.4.1/js/bootstrap.min.js', ['minify' => true]);
        Template::asset()->location('footer')->add('aos',           $addOn.'aos/aos.js', ['minify' => true]);
        Template::asset()->location('footer')->add('dropdownhover', $addOn.'bootstrap-dropdownhover/bootstrap-dropdownhover.js', ['minify' => true]);
        Template::asset()->location('footer')->add('slick',         $addOn.'slick/slick.min.js', ['minify' => true]);
        Template::asset()->location('footer')->add('swiper',        $addOn.'swiper/swiper.min.js', ['minify' => false]);
        Template::asset()->location('footer')->add('fancybox',      $addOn.'fancybox-3/jquery.fancybox.min.js', ['minify' => true]);
        Template::asset()->location('footer')->add('mmenu',         $addOn.'mmenu/mmenu.polyfills.js', ['minify' => true]);
        Template::asset()->location('footer')->add('mmenu',         $addOn.'mmenu/mmenu.js', ['minify' => true]);
        Template::asset()->location('footer')->add('lazy',          $addOn.'lazy/jquery.lazy.min.js', ['minify' => true]);
        Template::asset()->location('footer')->add('script',        $assets.'js/script.js', ['minify' => true]);
        return true;
    }
    add_action('init', 'theme_add_assets');
}