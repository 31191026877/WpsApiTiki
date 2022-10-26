<?php
function _form_wg_box($param, $value = []): string {
    return WidgetInputType::boxContainer($param, $value);
}

function _form_size_box($param, $value = []): string {
    return WidgetInputType::boxSize($param, $value);
}

function _form_col($param, $value = []): string {
    return WidgetInputType::columnNumber($param, $value);
}

function animate_css_option() {
    $option = [];
    $option['fade']             = 'fade';
    $option['fade-up']          = 'fade up';
    $option['fade-down']        = 'fade down';
    $option['fade-left']        = 'fade left';
    $option['fade-right']       = 'fade right';
    $option['fade-up-right']    = 'fade up right';
    $option['fade-up-left']     = 'fade up left';
    $option['fade-down-right']  = 'fade down right';
    $option['fade-down-left']   = 'fade down left';
    $option['flip-up']          = 'flip up';
    $option['flip-down']        = 'flip down';
    $option['flip-left']        = 'flip left';
    $option['flip-right']       = 'flip right';
    $option['slide-up']         = 'slide up';
    $option['slide-down']       = 'slide down';
    $option['slide-left']       = 'slide left';
    $option['slide-right']      = 'slide right';
    $option['zoom-in']          = 'zoom in';
    $option['zoom-in-up']       = 'zoom in up';
    $option['zoom-in-down']     = 'zoom in down';
    $option['zoom-in-left']     = 'zoom in left';
    $option['zoom-in-right']    = 'zoom in right';
    $option['zoom-out']         = 'zoom out';
    $option['zoom-out-up']      = 'zoom out up';
    $option['zoom-out-down']    = 'zoom out down';
    $option['zoom-out-left']    = 'zoom out left';
    $option['zoom-out-right']   = 'zoom out right';
    $option['top-bottom']       = 'top bottom';
    $option['top-center']       = 'top center';
    $option['top-top']          = 'top top';
    $option['center-bottom']    = 'center bottom';
    $option['center-center']    = 'center center';
    $option['center-top']       = 'center top';
    $option['bottom-bottom']    = 'bottom bottom';
    $option['bottom-center']    = 'bottom center';
    $option['bottom-top']       = 'bottom top';
    $option['linear']           = 'linear';
    $option['ease']             = 'ease';
    $option['ease-in']          = 'ease in';
    $option['ease-out']         = 'ease out';
    $option['ease-in-out']      = 'ease in out';
    $option['ease-in-back']     = 'ease in back';
    $option['ease-out-back']    = 'ease out back';
    $option['ease-in-out-back'] = 'ease in back';
    $option['ease-in-sine']     = 'ease in sine';
    $option['ease-out-sine']    = 'ease out sine';
    $option['ease-in-out-sine'] = 'ease in out sine';
    $option['ease-in-quad']     = 'ease in quad';
    $option['ease-out-quad']    = 'ease out quad';
    $option['ease-in-out-quad'] = 'ease in out quad';
    $option['ease-in-cubic']    = 'ease in cubic';
    $option['ease-out-cubic']   = 'ease out cubic';
    $option['ease-in-out-cubic']= 'ease in out cubic';
    $option['ease-in-quart']    = 'ease in quart';
    $option['ease-out-quart']   = 'ease out quart';
    $option['ease-in-out-quart']= 'ease in out quart';
    return $option;
}
/**
 * [gets_theme_font lấy danh sách font cần sử dụng]
 * @singe  3.0.0
 */
function gets_theme_font() {
    $font_family = Template::fonts();
    $fonts = [];
    if(have_posts($font_family)) {
        foreach ($font_family as $key => $font) {
            $fonts[$font['key']] = $font['label'];
        }
    }

    return $fonts;
}
/**
 * [get_theme_social lấy danh sách mạng xã hội cần sử dụng]
 * @singe  3.0.0
 */
if( !function_exists('get_theme_social') ) {

    function get_theme_social() {

        $socials = [
            array(	
                'label' 	=> 'Facebook Fanpage',
                'note'		=> 'Đường dẫn facebook fanpage',
                'field' 	=> 'social_facebook',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Twitter',
                'note'		=> 'Đường dẫn Twitter',
                'field' 	=> 'social_twitter',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Youtube',
                'note'		=> 'Đường dẫn kênh youtube',
                'field' 	=> 'social_youtube',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Instagram',
                'note'		=> 'Đường dẫn Instagram',
                'field' 	=> 'social_instagram',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Pinterest',
                'note'		=> 'Đường dẫn Pinterest',
                'field' 	=> 'social_pinterest',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Zalo',
                'note'		=> 'Số điện thoại liên kết Zalo',
                'field' 	=> 'social_zalo',
                'type' 		=> 'text',
                'group'     => 'social',
            ),
        ];

        return $socials;
    }
}
/**
 * [get_theme_social lấy danh sách input seo cần sử dụng]
 * @singe  3.0.0
 */
if(!function_exists('get_theme_seo_input') ) {

    function get_theme_seo_input() {
        $seo_input = array(
            ['label' => 'Favicon', 'field' => 'seo_favicon', 'type' => 'image'],
            ['label' => 'Meta title (shop)', 'field' => 'general_title', 'type' => 'text'],
            ['label' => 'Meta description (Mô tả trang chủ)', 'field' => 'general_description', 'type' => 'textarea'],
            ['label' => 'Meta keyword (Từ khóa trang chủ)', 'field' => 'general_keyword', 'type' => 'textarea'],
            ['label' => 'Google Master key', 'field' => 'seo_google_masterkey', 'type' => 'text'],
			['label' => 'Script Header', 'field' => 'header_script', 'type' => 'code', 'language'  => 'javascript', 'note' => 'Chèn script vào header (google analytic code, google master code..)'],
            ['label' => 'Script Body', 'field' => 'body_script', 'type' => 'code', 'language'  => 'javascript', 'note' => 'Chèn script vào ngay sau thẻ body'],
			['label' => 'Script Footer', 'field' => 'footer_script', 'type' => 'code', 'language' => 'javascript', 'note' => 'Chèn script vào footer (chat code, thống kê code..)'],
		);
        return apply_filters('get_theme_seo_input', $seo_input);
    }
}