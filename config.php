<?php
/**
 * sikidoVNCore
 * An mini source application cms for codeigniter 2.x.x
 * @package		sikidoVNCore
 * @author		SKDSoftware Dev Team
 * @link		http://sikido.vn
 */
/////////////////////////////////////
// Application database infomation //
/////////////////////////////////////
/** MySQL database username */
define('CLE_DBUSER','khatoan988_9998');

/** The name of the database */
define('CLE_DBNAME','khatoan988_9998');

/** MySQL database password */
define('CLE_DBPASSWORD','sTjaByhy');

/** MySQL hostname */
define('CLE_DBHOST','localhost');

/** MySQL database driber */
define('CLE_DBDRIVER','mysqli');

/** folder path source */
define('SPATH', 	'hdtest/');

define('DEBUG', false);

define('DEBUG_LOG', true);

/**
 * SKIDO Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
define('CLE_PREFIX','cle_');

/////////////////////////////////////
// Application upload filemanager  //
/////////////////////////////////////
$_SESSION['thum_w']     = 150;

$_SESSION['thum_h']     = 150;

$_SESSION['medium_w']   = 380;

$_SESSION['medium_h']   = 380;

$_SESSION['large_w']   = 580;

$_SESSION['large_h']   = 580;

$_SESSION['op_thum']    = 'auto'; //crop

$_SESSION['op_medium']  = 'auto'; //crop

$_SESSION['op_large']  = 'auto'; //crop

$_SESSION['upload_dir'] = '/'.SPATH.'uploads/source/';

define('SOURCE', 	'uploads/source/');

define('LARGE', 	'uploads/large/');

define('MEDIUM', 	'uploads/medium/');

define('THUMBAIL', 	'uploads/thumbnail/');
/////////////////////////////////////
// Application system              //
/////////////////////////////////////
define('SESSION_KEY', 'clevietnam'); //key session dùng cho ci

define('PLUGIN', 'scripts');

/**
 * For developers: SIKIDO debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 */

if( DEBUG_LOG == true ) {

	ini_set('display_errors', 1);

	ini_set('display_startup_errors', 1);

	error_reporting(E_ALL);
}

//url trang quản trị
define('URL_ADMIN', 	'admin');

//url trang chủ
define('URL_HOME', 		'trang-chu');

//url danh mục sản phẩm
define('URL_PRODUCT', 	'san-pham');

//thời gian cache 1 tháng
define('TIME_CACHE', 	30*86400);

$contractID = 'hdtest';

$dev = 'ktoan';



