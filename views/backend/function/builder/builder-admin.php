<?php
Class Builder_Admin {
    static public function page() {
        if(Admin::is() && Template::isPage('plugins_index') && Request::get('page') == 'builder') {
            Builder_Admin::builder();
            die;
        }
    }
    static public function builder() {

        if(version_compare(get_instance()->data['template']->version, '2.7.0') < 0) {
            show_error('Chức năng chỉ hỗ trợ theme-store phiên bản 2.7.0 trở lên');
        }

        $sidebar = Sidebar::gets();

        $themeOptions = get_instance()->themeOptions;

        foreach ($themeOptions['group'] as $groupKey => $groupItem) {
            $groupSubTemp = [$groupKey => ['label' => $groupItem['label']]];
            $groupSubTemp =  array_merge($groupSubTemp, ((!empty($groupItem['sub'])) ? $groupItem['sub'] : []));
            $groupSub = [];
            foreach ($groupSubTemp as $keySub => $subValue) {
                $groupSub[$keySub] = $subValue;
                $groupSub[$keySub]['form'] = new FormBuilder();
            }
            foreach ($themeOptions['option'] as $key => $item) {
                if($item['group'] == $groupKey) {
                    if(!empty($item['sub']) && isset($groupSub[$item['sub']])) {
                        $groupSub[$item['sub']]['form']->add($item['field'], $item['type'], $item, Option::get($item['field']));
                    }
                    else {
                        $groupSub[$groupKey]['form']->add($item['field'], $item['type'], $item, Option::get($item['field']));
                    }
                    unset($themeOptions['option'][$key]);
                }
            }
            $themeOptions['group'][$groupKey]['sub'] = $groupSub;
        }

        Admin::partial('function/builder/views/builders', ['sidebar' => $sidebar, 'themeOptions' => $themeOptions['group']]);
    }
}

add_action('template_redirect', 'Builder_Admin::page', 1);