<?php
Class Builder_Ajax {
    public static function widgetLoad($ci, $model) {

        $result['message'] 	= 'Load widget không thành công';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $wg_cate = CacheHandler::get('widget_service_category');

            if(!have_posts($wg_cate) || !CacheHandler::has('widget_service_category')) {
                $wg_cate = SKDService::widgetCategory()->all();
                if($wg_cate->status == 'success') {
                    $wg_cate = $wg_cate->data;
                    CacheHandler::save('widget_service_category', $wg_cate, 8*60*60 ); //Lưu cache trong 8h
                }
            }

            if(have_posts($wg_cate)) {
                foreach ($wg_cate as &$item) {
                    if( !CacheHandler::has( 'widget_service_item_'.$item->id ) ) {
                        $wg 	= SKDService::widget()->all($item->id);
                        if($wg->status == 'success') {
                            $wg = $wg->data;
                            CacheHandler::save( 'widget_service_item_'.$item->id, $wg, 8*60*60 ); //Lưu cache trong 8h
                        }
                    }
                    else $wg = CacheHandler::get( 'widget_service_item_'.$item->id );
                    $item->widgets = $wg;
                }
            }

            $result['data'] 	= $wg_cate;

            $result['message'] 	= 'Load widget thành công';

            $result['status'] 	= 'success';
        }

        echo json_encode($result);
    }
    public static function widgetAdd($ci, $model) {

        $result['message'] 	= 'Add widget không thành công';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $widgetId = (int)Request::post('widget_id');

            $widgetKey = Request::post('widget_add');

            $widget = Widget::get($widgetId);

            if(have_posts($widget)) {

                if(empty($widgetKey)) $widgetKey = $widget->widget_id;

                $widgetTemplate = $ci->template->getWidget($widgetKey);

                $data['name'] = $widgetTemplate->name;

                $data['template'] = $ci->data['template']->name;

                $data['widget_id'] = $widgetKey;

                $data['sidebar_id'] = $widget->sidebar_id;

                $data['options'] = serialize($widgetTemplate->getOption());

                $data['order'] = $widget->order + 1;

                $widgetSidebar = Widget::gets(Qr::set('sidebar_id', $widget->sidebar_id));

                $order = 0;

                $model->settable('widget');

                foreach ($widgetSidebar as $item) {
                    if ($order == 1) {
                        $model->update(['order' => $item->order + 1], Qr::set('id', $item->id));
                    }
                    if ($item->id == $widget->id) $order = 1;
                }

                $data['id'] = $model->add($data);

                $data['widget_name'] = $widgetTemplate->name;

                if ($data['id']) {

                    $cache_id = 'sidebar_' . md5($widget->sidebar_id . '_' . $ci->data['template']->name);

                    CacheHandler::delete($cache_id);

                    $result['id'] = $data['id'];

                    $result['sidebar_id'] = $widget->sidebar_id;

                    $result['status'] = 'success';

                    $result['message'] = 'Thêm widget thành công!';
                }
            }
        }

        echo json_encode($result);
    }
    public static function elementLoad($ci, $model) {
        $result['message'] 	= 'Load widget không thành công';
        $result['status'] 	= 'error';
        if(Request::post()) {
            $type = Request::post('type');

            if($type == 'header') $service 	    = SKDService::header()->all();
            if($type == 'top-bar') $service 	= SKDService::topBar()->all();
            if($type == 'navigation') $service 	= SKDService::navigation()->all();

            if( isset($service->status) &&  $service->status == 'success' ) {
                $active  = Option::get('header_style_active', []);
                $path 	 = FCPATH.VIEWPATH.$ci->data['template']->name.'/theme-header/'.$type.'-style';
                $service = $service->data;
                $temp    = [];
                foreach ($service as $value) {
                    $temp[$value->folder] = ['id' => $value->id, 'title' => $value->title, 'image' => $value->image, 'folder' => $value->folder, 'type' => $type];
                    if(!is_dir($path.'/'.$value->folder)) {
                        $temp[$value->folder]['button'] = '<button type="button" class="btn-blue btn btn-block btn-download" data-builder-action="downloadElement"><i class="fal fa-long-arrow-down"></i></button>';
                    }
                    else {
                        if(empty($active[$type][$value->folder])) {
                            $temp[$value->folder]['button'] = '<button type="button" class="btn-green btn btn-block btn-active" data-builder-action="activeElement"><i class="fal fa-power-off"></i></button>';
                            $temp[$value->folder]['button'] .= '<button type="button" class="btn-red btn btn-block btn-delete" data-builder-action="deleteElement"><i class="fal fa-trash"></i></button>';
						} else {
                            $temp[$value->folder]['button'] = '<button type="button" class="btn-black btn btn-block btn-deactivate" data-builder-action="deactivateElement"><i class="fal fa-power-off"></i></button>';
						}
                    }
                }
                $service = $temp;
            }
            else $service = [];
            $result['element'] 	= $service;
            $result['message'] 	= 'Load element thành công';
            $result['status'] 	= 'success';
        }
        echo json_encode($result);
    }
    public static function optionsLoad($ci, $model) {
        $result['message'] 	= 'Load widget không thành công';
        $result['status'] 	= 'error';
        if(Request::post()) {
            $themeOptions = get_instance()->themeOptions;
            foreach ($themeOptions['group'] as $group_key => &$group_item) {

                $FormBuilder = new FormBuilder();

                foreach ($themeOptions['option'] as $key => $item) {
                    if($item['group'] == $group_key) {
                        $FormBuilder->add($item['field'], $item['type'], $item, option::get($item['field']));
                        unset($themeOptions['option'][$key]);
                    }
                }

                $group_item['html'] = $FormBuilder->html();

                if(empty($group_item['html'])) {
                    unset($themeOptions['group'][$group_key]);
                }
            }
            $result['html'] 	= Admin::partial('function/builder/views/builder-options', ['themeOptions' => $themeOptions['group']], true);
            $result['message'] 	= 'Load element thành công';
            $result['status'] 	= 'success';
        }
        echo json_encode($result);
    }
}

Ajax::admin('Builder_Ajax::widgetLoad');
Ajax::admin('Builder_Ajax::widgetAdd');
Ajax::admin('Builder_Ajax::elementLoad');
Ajax::admin('Builder_Ajax::optionsLoad');