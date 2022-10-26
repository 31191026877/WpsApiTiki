<?php
class FormAdmin {
    public FormAdminLocation $leftTop;
    public FormAdminLocation $tabs;
    public FormAdminLocation $lang ;
    public FormAdminLocation $leftBottom;
    public FormAdminLocation $right;
    public array $fieldValue = [];
    public array $params = [];

    public function __construct() {
        $this->leftTop = new FormAdminLocation();
        $this->tabs = new FormAdminLocation();
        $this->lang = new FormAdminLocation();
        $this->leftBottom = new FormAdminLocation();
        $this->right = new FormAdminLocation();
    }
    public function getLocation($groupKey): FormAdminLocation|bool {
        if($this->leftTop->hasGroup($groupKey)) return $this->leftTop;
        if($this->tabs->hasGroup($groupKey)) return $this->tabs;
        if($this->lang->hasGroup($groupKey)) return $this->lang;
        if($this->leftBottom->hasGroup($groupKey)) return $this->leftBottom;
        if($this->right->hasGroup($groupKey)) return $this->right;
        return false;
    }
    public function hasGroup($groupKey = null): bool {
        if($this->leftTop->hasGroup($groupKey)) return true;
        if($this->leftBottom->hasGroup($groupKey)) return true;
        if($this->right->hasGroup($groupKey)) return true;
        if($this->lang->hasGroup($groupKey)) return true;
        if($this->tabs->hasGroup($groupKey)) return true;
        return false;
    }
    public function removeGroup($group): FormAdmin {
        if(is_string($group)) $group = [$group];
        if(is_array($group)) {
            foreach ($group as $groupKey) {
                if($this->leftTop->hasGroup($groupKey)) {
                    $this->leftTop->removeGroup($groupKey); continue;
                }
                if($this->leftBottom->hasGroup($groupKey)) {
                    $this->leftBottom->removeGroup($groupKey); continue;
                }
                if($this->right->hasGroup($groupKey)) {
                    $this->right->removeGroup($groupKey); continue;
                }
                if($this->lang->hasGroup($groupKey)) {
                    $this->lang->removeGroup($groupKey); continue;
                }
                if($this->tabs->hasGroup($groupKey)) {
                    $this->tabs->removeGroup($groupKey); continue;
                }
            }
        }
        return $this;
    }
    public function getAllField(): array {
        $fields = [];
        foreach ($this->leftTop->group() as $group) {
            $fields = array_merge($fields, $group->field);
        }
        foreach ($this->leftBottom->group() as $group) {
            $fields = array_merge($fields, $group->field);
        }
        foreach ($this->right->group() as $group) {
            $fields = array_merge($fields, $group->field);
        }
        foreach ($this->lang->group() as $group) {
            $fields = array_merge($fields, $group->field);
        }
        foreach ($this->tabs->group() as $group) {
            $fields = array_merge($fields, $group->field);
        }
        return $fields;
    }
    public function setFieldValue($name, $value): FormAdmin {
        $this->fieldValue[$name] = $value;
        return $this;
    }
    public function removeField($inputs): static {
        if(is_string($inputs)) $inputs = [$inputs];
        if(!have_posts($inputs)) return $this;
        $listLocation['leftTop'] = $this->leftTop->group();
        $listLocation['leftBottom'] = $this->leftBottom->group();
        $listLocation['lang'] = $this->lang->group();
        $listLocation['tabs'] = $this->tabs->group();
        $listLocation['right'] = $this->right->group();
        foreach ($listLocation as $listGroup) {
            foreach ($listGroup as $group) {
                $group->removeField($inputs);
            }
        }
        return $this;
    }
    public function setParams($key, $value): static {
        $this->params[$key] = $value;
        return $this;
    }
    public function support($class, $supportGroup = [], $supportField = []): void {
        $template 	= template();
        $removeGroup = ['theme'];
        $removeField = ['public', 'theme_layout', 'theme_view'];
        if($class == 'page') {
            $removeGroup[] = 'media';
            $removeField[] = 'excerpt';
            $removeField[] = 'image';
        }
        if($class == 'post_categories') {
            $removeField[] = 'excerpt';
        }
        if(!empty($supportGroup)) {
            $removeGroup[] = 'seo';
            $removeGroup[] = 'media';
        }
        if(!empty($supportField)) {
            $removeField[] = 'content';
            $removeField[] = 'excerpt';
            $removeField[] = 'image';
        }
        if(isset($template->support[$class]['group']) && have_posts($template->support[$class]['group'])) {
            $supportGroup = array_merge($supportGroup, $template->support[$class]['group']);
        }
        if(isset($template->support[$class]['field']) && have_posts($template->support[$class]['field'])) {
            $supportField = array_merge($supportField, $template->support[$class]['field']);
        }
        $supportGroup = array_unique($supportGroup);
        $supportField = array_unique($supportField);

        //remove group các chức năng
        if(have_posts($supportGroup)) {
            foreach ($supportGroup as $group) {
                if (($key = array_search($group, $removeGroup)) !== false) unset($removeGroup[$key]);
            }
        }
        if(have_posts($supportField)) {
            foreach ($supportField as $group) {
                if (($key = array_search($group, $removeField)) !== false) unset($removeField[$key]);
            }
        }
        if(have_posts($removeGroup)) $this->removeGroup($removeGroup);
        if(have_posts($removeField)) $this->removeField($removeField);
    }
    public function toHtml($location): array {

        $FormHtml = [];

        $FormBuilder = new FormBuilder();

        if(empty($this->$location)) return $FormHtml;

        if($location == 'lang') {
            foreach ($this->$location->group() as $id => $group) {
                $FormHtml[$id] = ['name' => $group->name, 'html' => ''];
                foreach (Language::list() as $key_lang => $lang) {
                    $FormBuilder->add('', 'html', '<div class="tab-pane fade '.(($key_lang == Language::default()) ? 'active show' : '').'" id="lang_'.$key_lang.'_panel" role="tabpanel" aria-labelledby="tab_'.$key_lang.'" tabindex="0"><div class="row m-1">');
                    if(!empty($group->field)) {
                        foreach ($group->field as $keyItem => $item) {
                            if (isset($item['lang']) && $item['lang'] == $key_lang) {
                                $FormBuilder->add($item['field'], $item['type'], $item, (isset($this->fieldValue[$keyItem])) ? $this->fieldValue[$keyItem] : null);
                            }
                        }
                    }
                    $FormBuilder->add('', 'html', '</div></div>');
                    $FormHtml[$id]['html'] .= $FormBuilder->html();
                }
            }
        }
        else {
            foreach ($this->$location->group() as $id => $group) {
                if(!empty($group->field)) {
                    foreach ($group->field as $item) {
                        $FormBuilder->add($item['field'], $item['type'], $item, (isset($this->fieldValue[$item['field']])) ? $this->fieldValue[$item['field']] : null);
                    }
                }

                $FormHtml[$id] = [
                    'name' => $group->name,
                    'html' => $FormBuilder->html()
                ];
            }
        }

        return $FormHtml;
    }
}
class FormAdminLocation {
    public array $group = [];
    public function group($groupKey = null): FormAdminGroup|array {
        if($groupKey == null) return $this->group;
        return $this->group[$groupKey];
    }
    public function hasGroup($groupKey = null): bool {
        return Arr::has($this->group, $groupKey);
    }
    public function addGroup($groupKey, $groupName, $position = null): FormAdminGroup {

        if(isset($this->group[$groupKey])) {
            throw new Exception('Group is exits');
        }

        if($position == null) {
            $this->group[$groupKey] = new FormAdminGroup($groupKey, $groupName);
            return $this->group[$groupKey];
        }

        $listGroup = [];

        foreach ($this->group as $key => $group) {
            if($key == $position) $listGroup[$groupKey] = new FormAdminGroup($groupKey, $groupName);
            $listGroup[$key] = $group;
        }

        if(!isset($listGroup[$groupKey])) {
            $listGroup[$groupKey] = new FormAdminGroup($groupKey, $groupName);
        }

        $this->group = $listGroup;

        return $this->group[$groupKey];
    }
    public function removeGroup($group): static {
        if(is_string($group)) $group = [$group];
        if(is_array($group)) {
            foreach ($group as $groupKey) {
                if(Arr::has($this->group, $groupKey)) {
                    unset($this->group[$groupKey]); continue;
                }
            }
            return $this;
        }
        return $this;
    }
}
class FormAdminGroup {
    public string $name;
    public string $id;
    public array $field = [];
    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
    public function addField($name, $type, $args = [], $position = null): static {

        $fieldId = (String)Str::of($name)->replace('[', '_')->replace(']','');

        if(isset($args['lang']) ) {
            $fieldId  = $args['lang'].'_'.$name;
            $name = $args['lang'].'['.$name.']';
        }

        if(!empty($position) && ($position == 'title' || $position == 'name' || $position == 'excerpt' || $position == 'content') ) {
            if( isset($args['lang']) ) $position = $args['lang'].'_'.$position;
            else $position = 'vi_'.$position;
        }

        $args['field'] = $name;

        $args['type'] = $type;

        if($position === null) {
            $this->field[$fieldId] = $args;
            return $this;
        }
        else {
            $temp = [];
            foreach ($this->field as $k => $value) {
                if($k == $position ) {
                    $temp[$fieldId] = $args;
                }
                $temp[$k] = $value;
            }
            $this->field = $temp;
        }
        return $this;
    }
    public function addFieldLang($name, $type, $args = [], $position = null): static {
        foreach (Language::listKey() as $key) {
            $args['lang'] = $key;
            $this->addField($name, $type, $args, $position);
        }
        return $this;
    }
    public function removeField($fields): static {
        if(is_string($fields)) $fields = [$fields];
        if(is_array($fields)) {
            foreach ($fields as $item) {
                if(Arr::has($this->field, $item)) {
                    unset($this->field[$item]); continue;
                }
                foreach (Language::listKey() as $key) {
                    if(Arr::has($this->field, $key.'_'.$item)) {
                        unset($this->field[$key.'_'.$item]); continue;
                    }
                }
            }
            return $this;
        }
        return $this;
    }
    public function renameField($inputs): static {
        if(!have_posts($inputs)) return $this;
        foreach ($this->field as $key => &$value) {
            foreach ($inputs as $name => $label) {
                if(isset($value['lang'])) {
                    $key = $value['lang'].'['.$name.']';
                }
                if($value['field'] == $key) {
                    $value['label'] = $label;
                    unset($inputs[$name]);
                    break;
                }
            }
        }
        return $this;
    }
}

function formAdmin() {
    if(isset(get_instance()->adminForm)) return get_instance()->adminForm;
    return get_instance()->adminForm = new FormAdmin();
}