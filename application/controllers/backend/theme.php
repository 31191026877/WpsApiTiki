<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() {

		$this->load->helper('directory');

		$theme_path = FCPATH.'views/';
		
		$theme = directory_map($theme_path,true);

		$template = null;

		unset($theme['backend']);

		foreach ($theme as $key => $value) {
			if(is_string($value) && $value != 'backend' && $this->template->exist($value)) {
				$id = ($value == $this->system->theme_current) ? 0 : $key;
				$template[$id] = new template($value);
			}
		}

		ksort($template);

		$this->data['list_template'] = $template;

		$this->template->render();
	}

	public function option() {
		if(Auth::hasCap('edit_theme_options')) {
            foreach ($this->themeOptions['group'] as $groupKey => $groupItem) {
                $groupSubTemp = [$groupKey => ['label' => $groupItem['label']]];
                $groupSubTemp =  array_merge($groupSubTemp, ((!empty($groupItem['sub'])) ? $groupItem['sub'] : []));
                $groupSub = [];
                foreach ($groupSubTemp as $keySub => $subValue) {
                    $groupSub[$keySub] = $subValue;
                    $groupSub[$keySub]['form'] = new FormBuilder();
                }
                foreach ($this->themeOptions['option'] as $key => $item) {
                    if($item['group'] == $groupKey) {
                        if(!empty($item['sub']) && isset($groupSub[$item['sub']])) {
                            $groupSub[$item['sub']]['form']->add($item['field'], $item['type'], $item, Option::get($item['field']));
                        }
                        else {
                            $groupSub[$groupKey]['form']->add($item['field'], $item['type'], $item, Option::get($item['field']));
                        }
                        unset($this->themeOptions['option'][$key]);
                    }
                }
                $this->themeOptions['group'][$groupKey]['sub'] = $groupSub;
		    }
			$this->template->render();
		}
		else $this->template->error('404');
	}
}