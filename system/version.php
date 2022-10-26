<?php
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
/**
 * The version string
 *
 * @global string $cmsVersion
 */
$cmsVersion = '6.2.0';

/**
 * Holds the DB revision, increments when changes are made to the WordPress DB schema.
 *
 * @global int $skd_db_version
 */
$skd_db_version = 2022.1;

/**
 * Holds the required PHP version
 *
 * @global string $required_php_version
 */
$required_php_version = '8.0.0';

/**
 * Holds the required MySQL version
 *
 * @global string $required_mysql_version
 */
$required_mysql_version = '5.0';

/**
 * Holds the required MySQL version
 *
 * @global string $required_mysql_version
 */
$database = new DB;

$database->addConnection([
    'driver' => 'mysql',
    'host'      => CLE_DBHOST,
    'database'  => CLE_DBNAME,
    'username'  => CLE_DBUSER,
    'password'  => CLE_DBPASSWORD,
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => CLE_PREFIX,
]);

$database->setEventDispatcher(new Dispatcher(new Container));

$database->setAsGlobal();

$database->bootEloquent();

if(DEBUG) DB::enableQueryLog();