<?php
Class ThemeHeadingStyle11 {
    static public function register($heading) {
        $heading['heading-style-11'] = ['label' => 'Heading Style 11', 'class' => 'ThemeHeadingStyle11'];
        return $heading;
    }
    static public function html($name, $options) {
        $txtPosition = (!empty($options['txtPosition'])) ? $options['txtPosition'] : 'center';
        $headingStyle = Template::cssText((isset($options['headingStyle']) && have_posts($options['headingStyle'])) ? $options['headingStyle'] : []);
        $txtTopStyle  = Template::cssText((isset($options['txtTop']) && have_posts($options['txtTop'])) ? $options['txtTop'] : [], ['color' => '#000']);
        ?>
        <div class="header-title header-title-style-11">
            <?php if(!empty($options['txtTop']['txt'])) { ?><p class="header-text-top" style="<?php echo $txtTopStyle['css'];?>"><?php echo $options['txtTop']['txt'];?></p><?php } ?>
            <p class="header <?php echo $txtPosition;?>" style="<?php echo $headingStyle['css'];?>"><?= $name;?></p>
            <?php if(!empty($options['desContent'])) { ?><p class="header-description header-description-<?php echo $txtPosition;?>"><?php echo $options['desContent'];?></p><?php } ?>
        </div>
        <?php
    }
    static public function css($options, $id = '') {
        $txtTopMargin   = (isset($options['txtTopMargin']) && is_numeric($options['txtTopMargin'])) ? (int)$options['txtTopMargin'] : '15';
        $txtPosition    = (!empty($options['txtPosition'])) ? $options['txtPosition'] : 'center';
        $brColor        = (!empty($options['brColor'])) ? $options['brColor'] : 'var(--theme-color)';
        $marginTop      = (isset($options['marginTop']) && is_numeric($options['marginTop'])) ? $options['marginTop'] : '30';
        $marginBottom   = (isset($options['marginBottom']) && is_numeric($options['marginBottom']))  ? $options['marginBottom'] : '20';
        $desColor   = (!empty($options['desColor'])) ? $options['desColor'] : '#000';
        $desWidth   = (!isset($options['desWidth'])) ? '600px' : ((!empty($options['desWidth'])) ? $options['desWidth'].'px' : '100%');
        $desTopMargin = (isset($options['desTopMargin']) && is_numeric($options['desTopMargin'])) ? (int)$options['desTopMargin'] : '15';
        ?>
        <style>
            body <?php echo $id;?> {
                --heading-top-margin:<?php echo $txtTopMargin;?>px;
                --heading-bg:<?php echo $brColor;?>;
                --heading-position:<?php echo $txtPosition;?>;
                --heading-margin:<?php echo $marginTop;?>px 0 <?php echo $marginBottom;?>px 0;
                --heading-margin-bottom:<?php echo $marginBottom;?>px;
                --heading-des-color:<?php echo $desColor;?>;
                --heading-des-width:<?php echo $desWidth;?>;
                --heading-des-margin:<?php echo $desTopMargin;?>px;
            }
        </style>
        <?php
    }
    static public function form() {
        $Form = [
            ['name' => 'headingHr1', 'type' => 'html', 'html' => '<div class="col-md-12"><h6>Ch??? tr??n ti??u ?????</h6> <hr/></div>'],
            ['name' => 'txtTopMargin', 'type' => 'number', 'label' => 'C??ch tr??n', 'start' => 3],
            ['name' => 'txtTop', 'type' => 'textBuilding', 'label' => 'Ch??? tr??n ti??u ?????', 'start' => 9],

            ['name' => 'headingHr2', 'type' => 'html', 'html' => '<div class="col-md-12"><h6>Ti??u ?????</h6> <hr/></div>'],
            ['name' => 'headingStyle', 'type' => 'textBuilding', 'label' => 'Style Ti??u ?????', 'start' => 3, 'txtInput' => false],
            ['name' => 'txtPosition', 'type' => 'tab', 'label' => 'V??? tr??', 'options' => ['left' => 'Tr??i', 'center' => 'Gi???a', 'right' => 'Ph???i'], 'start' => 9],
            ['name' => 'brColor', 'type' => 'color', 'label' => 'M??u g???ch ngang', 'start' => 3,],
            ['name' => 'marginTop', 'type' => 'number', 'label' => 'C??ch tr??n', 'start' => 4],
            ['name' => 'marginBottom', 'type' => 'number', 'label' => 'C??ch d?????i', 'start' => 5],

            ['name' => 'headingHr3', 'type' => 'html', 'html' => '<div class="col-md-12"><h6>M?? t??? d?????i ti??u ?????</h6> <hr/></div>'],
            ['name' => 'desContent', 'type' => 'textarea', 'label' => 'N???i dung m?? t???'],
            ['name' => 'desColor', 'type' => 'color', 'label' => 'M??u ch??? m?? t???', 'start' => 3],
            ['name' => 'desWidth', 'type' => 'number', 'label' => '????? r???ng m?? t???', 'start' => 4, 'value' => 600],
            ['name' => 'desTopMargin', 'type' => 'number', 'label' => 'Kho???ng c??ch d?????i ti??u ?????', 'start' => 5],
        ];
        return $Form;
    }
    static public function less(): void {
        include_once 'assets/heading-style-11.less';
    }
}

add_filter('theme_widget_heading', 'ThemeHeadingStyle11::register');
add_filter('theme_sidebar_heading', 'ThemeHeadingStyle11::register');
add_action('theme_custom_less', 'ThemeHeadingStyle11::less');