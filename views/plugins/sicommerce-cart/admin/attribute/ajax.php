<?php
Class Admin_attributes_Ajax {
    static public function save($ci, $model) {

        $result['message'] = 'Cập nhật dữ liệu thất bại.';

        $result['status'] = 'error';

        if(Request::post()) {

            $id = (int)Request::post('id');

            $attribute = Request::post();

            if(!isset($attribute['option_type'])) {
                $result['message'] = 'Loại thuộc tính sản phẩm không được để trống';
                echo json_encode($result);
                return true;
            }

            $attribute['option_type'] = trim(Str::clear($attribute['option_type']));

            if(isset($attribute['attribute']) && have_posts($attribute['attribute'])) {

                $attribute_items = $attribute['attribute'];

                foreach ($attribute_items as &$item) {

                    if(empty($item['title'])) {
                        $result['message'] = 'Tên attribute item không được để trống';
                        echo json_encode($result);
                        return true;
                    }

                    if($attribute['option_type'] == 'color' && empty($item['color'])) {
                        $result['message'] = 'Màu attribute item không được để trống';
                        echo json_encode($result);
                        return true;
                    }

                    if($attribute['option_type'] == 'image' && empty($item['image'])) {
                        $result['message'] = 'Ảnh attribute item không được để trống';
                        echo json_encode($result);
                        return true;
                    }
                    $item['title']   = trim(Str::clear($item['title']));
                    $item['value']  = trim(Str::clear($item['color'])); unset($item['color']);
                    $item['image']  = FileHandler::handlingUrl(trim(Str::clear($item['image'])));
                }

                unset($attribute['attribute']);
            }
            else {
                $result['message'] = 'Attribute item không được để trống';
                echo json_encode($result);
                return true;
            }

            $sort = $attribute['sort']; unset($attribute['sort']);

            if(empty($id)) {

                $id = Attributes::insert($attribute);

                if(!is_skd_error($id)) {

                    AdminActiveLog::writeLog([
                        'username'     => Auth::user()->username,
                        'fullname'     => Auth::user()->firstname.' '.Auth::user()->lastname,
                        'ip'           => AdminActiveLog::getIp(),
                        'action'       => 'add',
                        'time'         => time(),
                        'agent_string' => Device::string(),
                        'message'      => 'thêm mới nhóm thuộc tính <b>'.(isset($attribute[Language::default()]['title'])) ? : $attribute['title'].'</b>'
                    ]);

                    if(have_posts($attribute_items)) {

                        $logMessage = 'thêm mới thuộc tính';

                        foreach ($attribute_items as $att_item_id => $att_item) {

                            $att_item['option_id']  = $id;

                            $att_item['type']       = $attribute['option_type'];

                            foreach ($sort as $key => $s) {
                                if($s == $att_item_id) {
                                    $att_item['order'] = $key;
                                    unset($sort[$key]);
                                    break;
                                }
                            }

                            Attributes::insertItem($att_item);

                            $logMessage .= ' <b>'.$att_item['title'].'</b>,';
                        }

                        AdminActiveLog::writeLog([
                            'username'     => Auth::user()->username,
                            'fullname'     => Auth::user()->firstname.' '.Auth::user()->lastname,
                            'ip'           => AdminActiveLog::getIp(),
                            'action'       => 'add',
                            'time'         => time(),
                            'agent_string' => Device::string(),
                            'message'      => $logMessage
                        ]);
                    }

                    $result['message']  = 'Cập nhật dữ liệu thành công.';

                    $result['status']   = 'success';
                }
                else {
                    foreach ($id->errors as $error) {
                        $result['message'] = $error;
                    }
                }
            }
            else {
                $attribute_old = Attributes::get($id);

                $attribute_items_update = [];

                $attribute_items_delete = Attributes::getsItem(Qr::set('option_id', $id));

                if(have_posts($attribute_old)) {

                    $attribute['id'] = $id;

                    //Update attr
                    $insert_result = Attributes::insert($attribute);

                    if(!is_skd_error($insert_result)) {

                        foreach ($attribute_items_delete as $item_delete_key => $item_delete) {
                            if (!empty($attribute_items[$item_delete->id])) {
                                $attribute_items_update[$item_delete->id] = $attribute_items[$item_delete->id];
                                unset($attribute_items[$item_delete->id]);
                                unset($attribute_items_delete[$item_delete_key]);
                                continue;
                            }
                        }

                        //add
                        if (have_posts($attribute_items)) {

                            $logMessage = 'thêm mới thuộc tính';

                            foreach ($attribute_items as $item_id => $item) {
                                $item['option_id'] = $id;
                                $item['type'] = $attribute['option_type'];
                                foreach ($sort as $key => $s) {
                                    if($s == $item_id) {
                                        $item['order'] = $key;
                                        unset($sort[$key]);
                                        break;
                                    }
                                }
                                Attributes::insertItem($item);

                                $logMessage .= ' <b>'.$item['title'].'</b>,';
                            }

                            AdminActiveLog::writeLog([
                                'username'     => Auth::user()->username,
                                'fullname'     => Auth::user()->firstname.' '.Auth::user()->lastname,
                                'ip'           => AdminActiveLog::getIp(),
                                'action'       => 'add',
                                'time'         => time(),
                                'agent_string' => Device::string(),
                                'message'      => $logMessage
                            ]);
                        }

                        //Update
                        if (have_posts($attribute_items_update)) {
                            foreach ($attribute_items_update as $item_update_id => $item_update) {
                                $item_update['id'] = $item_update_id;
                                $item_update['type'] = $attribute['option_type'];
                                foreach ($sort as $key => $s) {
                                    if($s == $item_update_id) {
                                        $item_update['order'] = $key;
                                        unset($sort[$key]);
                                        break;
                                    }
                                }
                                Attributes::insertItem($item_update);
                            }
                        }

                        //Delete
                        if (have_posts($attribute_items_delete)) {

                            $logMessage = 'xóa thuộc tính';

                            foreach ($attribute_items_delete as $item_delete) {
                                Attributes::deleteItem($item_delete->id);
                                $logMessage .= ' <b>'.$item_delete->title.'</b>,';
                            }

                            AdminActiveLog::writeLog([
                                'username'     => Auth::user()->username,
                                'fullname'     => Auth::user()->firstname.' '.Auth::user()->lastname,
                                'ip'           => AdminActiveLog::getIp(),
                                'action'       => 'delete',
                                'time'         => time(),
                                'agent_string' => Device::string(),
                                'message'      => $logMessage
                            ]);
                        }

                        $result['message']  = 'Cập nhật dữ liệu thành công.';

                        $result['status']   = 'success';
                    }
                    else {

                        foreach ($insert_result->errors as $error) {
                            $result['message'] = $error;
                        }
                    }
                }
            }
        }

        echo json_encode($result);

        return true;
    }
}

Ajax::admin('Admin_attributes_Ajax::save');