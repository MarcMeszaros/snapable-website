<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Snapable
|--------------------------------------------------------------------------
|
| Defines used by the Snapable code.
|
*/
function env_isset($name) {
  $val = getenv($name);
  return ($val && strlen($val) > 0);
}

function env_str($name, $default='') {
    $val = getenv($name);
    return ($val) ? $val : $default;
}

function env_bool($name, $default=false) {
    $val = getenv($name);
    if ($val && strlen($val) > 0) {
        $val_sanitized = strtolower($val.trim());
        $char = substr($val_sanitized, 0, 1);
        if (array_intersect(array($char), array('1', 't', 'y'))) {
            return true;
        } else {
            return false;
        }
    } else {
        return $default;
    }
}

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

define('DEBUG', env_bool('DEBUG', false));
define('SSL_REDIRECT', env_bool('SSL_REDIRECT', false)); // probably the VM that doesn't have SSL certs...

if (env_isset('API_PORT')) {
  define('API_HOST', 'http://' . env_str('API_PORT_80_TCP_ADDR') . ':' . env_str('API_PORT_80_TCP_PORT'));
} else {
  define('API_HOST', env_str('API_HOST', 'https://devapi.snapable.com'));
}
define('API_VERSION', env_str('API_VERSION', 'private_v1'));
define('API_KEY', env_str('API_KEY', 'key123'));
define('API_SECRET', env_str('API_SECRET', 'sec123'));

define('STRIPE_KEY_PUBLIC', env_str('STRIPE_KEY_PUBLIC'));

if (env_isset('SENTRY_DSN')) {
    define('SENTRY_DSN', env_str('SENTRY_DSN'));
}

/* End of file constants.php */
/* Location: ./application/config/constants.php */
