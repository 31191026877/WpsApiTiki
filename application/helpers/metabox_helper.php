<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Metabox {
    static public function add($id, $title, $callback, $args = []) {
        $module         = (!empty($args['module'])) ? $args['module'] : null;
        $position       = (!empty($args['position'])) ? $args['position'] : 1;
        $content        = (!empty($args['content'])) ? $args['content'] : 'leftb';
        $content_box    = (!empty($args['content_box'])) ? $args['content_box'] : '';
        $ci             =& get_instance();
        if(!static::has($id)) {
            $ci->metaBox[$id] = array(
                'label' 	    => $title,
                'callback' 	    => $callback,
                'module'	    => $module,
                'position'      => $position,
                'content'       => $content,
                'content_box'   => $content_box,
            );

            $metaBox = [];
            foreach ($ci->metaBox as $metabox_id => $item) {
                $metaBox[$metabox_id] = $item['position'];
            }
            asort($metaBox);
            $metaBox_sort = [];
            foreach ($metaBox as $metabox_id => $position) {
                $metaBox_sort[$metabox_id] = $ci->metaBox[$metabox_id];
            }
            $ci->metaBox = $metaBox_sort;
            return true;
        }
        return false;
    }
    static public function remove($id) {
        $ci =& get_instance();
        if( isset($ci->metaBox[$id]) ) unset($ci->metaBox[$id]);
    }
    static public function has($id) {
        if(isset(get_instance()->metaBox[$id])) return true;
        return false;
    }
    static public function get($id) {
        if(static::has($id)) return get_instance()->metaBox[$id];
        return false;
    }
    static public function gets() {
        return get_instance()->metaBox;
    }
    static public function render($id, $object = []) {
        $metabox = static::get($id);
        if(function_exists($metabox['callback'])) {
            call_user_func($metabox['callback'], $object, $metabox);
        }
        else {
            $callback =  explode('::', $metabox['callback']);
            if(count($callback) == 2 && method_exists($callback[0], $callback[1])) {
                call_user_func($metabox['callback'], $object, $metabox);
            }
            else {
                echo notice('warning', 'Callback of metabox do\'nt exits!');
            }
        }
    }
}

class Metadata {

    static public function count($object_type, $args = []) {

        $query = Qr::set();

        $model 	= model('metabox');

        if(is_array($args)) {
            if(!empty($args['object_id'])) {
                $query->where('object_id', (int)$args['object_id']);
                unset($args['object_id']);
            }

            if(!empty($args['meta_key'])) {
                $query->where('meta_key', Str::clear($args['meta_key']));
                unset($args['meta_key']);
            }

            if(!empty($args['meta_value'])) {
                $query->where('meta_value', Str::clear($args['meta_value']));
                unset($args['meta_value']);
            }

            $query = Qr::convert($args);

            if(!$query instanceof Qr) return 0;
        }

        if(is_numeric($args)) $query = Qr::set('id', (int)$args);

        if($args instanceof Qr) $query = clone $args;

        if(model()::schema()->hasTable( $object_type.'_metadata' ) ) {
            $model->setTable($object_type.'_metadata');
        }
        else {
            $query->where('object_type', $object_type);
        }

        return apply_filters( 'count_metadata', $model->count($query), $args);
    }

    static public function get($object_type, $object_id = '', $meta_key = '', $single = false) {

        if (!$object_type || !is_numeric($object_id)) return false;

        $check = apply_filters( "get_{$object_type}_metadata", null, $object_id, $meta_key, $single );

        if ($check !== null) {
            if ($single && is_array($check))
                return $check[0];
            else
                return $check;
        }

        //load dữ liệu
        $cache_id = $object_id.'_'.$object_type;

        if(!empty($meta_key)) $cache_id .= '_'.$meta_key;

        if($single) $cache_id .= '_single';

        $cache_id = 'metabox_'.md5($cache_id);

        if(CacheHandler::has($cache_id)) {
            $temp = CacheHandler::get($cache_id);
            return ( Str::isSerialized($temp) ) ? unserialize($temp) : $temp ;
        }
        //không tồn tại cache
        $query = Qr::set('object_id', $object_id);

        if (!empty($meta_key)) $query->where('meta_key', $meta_key);

        $model = model('metabox');

        if(model()::schema()->hasTable( $object_type.'_metadata' )) {
            $model->settable($object_type.'_metadata');
        }
        else {
            $query->where('object_type', $object_type);
        }

        if($single)
            $meta = $model->get($query);
        else {
            $meta = $model->gets($query);
        }


        //set cache
        if(have_posts($meta)) {

            if ($single) {
                CacheHandler::save($cache_id, $meta->meta_value);
                return ( Str::isSerialized($meta->meta_value) ) ? unserialize($meta->meta_value) : $meta->meta_value ;
            }
            else {
                $temp = (object)[];
                foreach ($meta as $value) {
                    $temp->{$value->meta_key} = (Str::isSerialized($value->meta_value)) ? unserialize($value->meta_value) : $value->meta_value ;
                }
                CacheHandler::save($cache_id, $temp);
                return $temp;
            }
        }

        if ($single)
            return '';
        else
            return [];
    }

    static public function add($object_type, $object_id, $meta_key, $meta_value) {

        if (!$object_type || !$meta_key || ! is_numeric($object_id)) return false;

        $check = apply_filters( "add_{$object_type}_metadata", null, $object_id, $meta_key, $meta_value );

        if (null !== $check) return $check;

        do_action( "add_{$object_type}_meta", $object_id, $meta_key, $meta_value );

        $model = model('metabox');

        //kiểm tra có table
        if(model()::schema()->hasTable( $object_type.'_metadata' ) ) {
            $model->settable( $object_type.'_metadata' );
            $mid = $model->add(array(
                'created'       => gmdate('Y-m-d H:i:s', time() + 7*3600),
                'object_id' 	=> $object_id,
                'meta_key' 		=> $meta_key,
                'meta_value' 	=> ( is_array($meta_value) || is_object($meta_value) ) ? serialize($meta_value) : $meta_value
            ));
        }
        else {
            $mid = $model->add( array(
                'created'       => gmdate('Y-m-d H:i:s', time() + 7*3600),
                'object_id' 	=> $object_id,
                'object_type' 	=> $object_type,
                'meta_key' 		=> $meta_key,
                'meta_value' 	=> ( is_array($meta_value) || is_object($meta_value) ) ? serialize($meta_value) : $meta_value
            ));
        }

        if (!$mid) return false;

        CacheHandler::delete( 'metabox_', true );

        do_action( "added_{$object_type}_meta", $mid, $object_id, $meta_key, $meta_value );

        return $mid;
    }

    static public function update($object_type, $object_id, $meta_key, $meta_value) {

        if (!$object_type || !$meta_key || !is_numeric($object_id)) return false;

        $check = apply_filters("update_{$object_type}_metadata", null, $object_id, $meta_key, $meta_value);

        if (null !== $check) return (bool) $check;

        $model = model('metabox');

        if(model()::schema()->hasTable( $object_type.'_metadata')) {
            $model->settable($object_type.'_metadata');
            $where = Qr::set('object_id', $object_id)->where('meta_key',$meta_key);
        }
        else {
            $model->settable('metabox');
            $where = Qr::set('object_id', $object_id)->where('object_type',$object_type)->where('meta_key',$meta_key);
        }

        $meta_ids = $model->gets($where);

        if (!have_posts($meta_ids)) {
            $mid = static::add( $object_type, $object_id, $meta_key, $meta_value );
            return $mid;
        }

        foreach ($meta_ids as $meta_id) {
            do_action( "update_{$object_type}_meta", $meta_id, $object_id, $meta_key, $meta_value );
        }

        $data['meta_value'] = (is_array($meta_value) || is_object($meta_value)) ? serialize($meta_value) : $meta_value;

        $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);

        $result = $model->update($data, $where);

        if (!$result) return false;

        CacheHandler::delete( 'metabox_', true );

        return true;
    }

    static public function delete($object_type, $object_id, $meta_key = '', $meta_value = '', $delete_all = false) {

        if (!$object_type || !is_numeric($object_id) && !$delete_all ) return false;

        $check = apply_filters( "delete_{$object_type}_metadata", null, $object_id, $meta_key, $meta_value, $delete_all );

        if (null !== $check) return (bool) $check;

        $query = Qr::set();

        if(!$delete_all) $query->where('object_id', $object_id);

        if(!empty($meta_key)) $query->where('meta_key', $meta_key);

        if(!empty($meta_value)) $query->where('meta_value', $meta_value);

        do_action( "delete_{$object_type}_meta", $query, $object_id, $meta_key, $meta_value );

        $model = model('metabox');

        if( model()::schema()->hasTable( $object_type.'_metadata' ) ) {
            $model->settable( $object_type.'_metadata' );
        }
        else {
            $query->where('object_type', $object_type);
        }

        $count =  $model->delete($query);

        if (!$count) return false;

        CacheHandler::delete( 'metabox_', true );

        do_action( "deleted_{$object_type}_meta", $query, $object_id, $meta_key, $meta_value );

        return true;
    }

    static public function deleteAll($object_type, $meta_key = '') {

        if (!$object_type) return false;

        $check = apply_filters( "delete_all_{$object_type}_metadata", null, $object_type );

        if ( null !== $check ) return (bool) $check;

        $model = model('metabox');

        $query = Qr::set();

        if( model()::schema()->hasTable( $object_type.'_metadata' ) ) {
            $model->settable( $object_type.'_metadata' );
        }
        else {
            $query->where('object_type', $object_type);
        }

        if(!empty($meta_key)) $query->where('meta_key', $meta_key);

        do_action( "delete_all_{$object_type}_meta", $query, $object_type );

        $count =  $model->delete($query);

        if (!$count) return false;

        CacheHandler::delete( 'metabox_', true );

        do_action( "deleted_all_{$object_type}_meta", $query, $meta_key );

        return true;
    }

    static public function deleteByMkey($object_type, $meta_key = '') {

        if (!$object_type) return false;

        $check = apply_filters( "deleted_{$object_type}_metadata_by_mkey", null, $object_type );

        if ( null !== $check ) return (bool) $check;

        $model = model('metabox');

        $query = Qr::set();

        if( model()::schema()->hasTable( $object_type.'_metadata' ) ) {
            $model->settable( $object_type.'_metadata' );
        }
        else {
            $query->where('object_type', $object_type);
        }

        if(!empty($meta_key)) $query->where('meta_key', $meta_key);

        do_action( "delete_all_{$object_type}_meta", $query, $object_type );

        $count =  $model->delete($query);

        if (!$count) return false;

        CacheHandler::delete( 'metabox_', true );

        do_action( "deleted_{$object_type}_metadata_by_mkey", $query, $meta_key );

        return true;
    }

    static public function deleteByMid( $object_type, $mid ) {

        if (!$object_type) return false;

        if (!$mid) return false;

        $query = Qr::set('object_id', $mid);

        $check = apply_filters( "delete_{$object_type}_metadata_by_mid", null, $object_type, $mid );

        if ( null !== $check ) return (bool) $check;

        $model = model('metabox');

        if( model()::schema()->hasTable( $object_type.'_metadata' ) ) {
            $model->settable( $object_type.'_metadata' );
        }
        else {
            $query->where('object_type', $object_type);
        }

        $count = $model->delete($query);

        if (!$count) return false;

        CacheHandler::delete( 'metabox_', true );

        do_action( "deleted_{$object_type}_metadata_by_mid", $object_type, $mid );

        return true;
    }
}