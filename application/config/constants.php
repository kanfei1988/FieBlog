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
| Themes Path
|--------------------------------------------------------------------------
|
| 主题文件夹相对于网站根目录的路径
|
*/
define('THEMEPATH', 'themes');

/*
|--------------------------------------------------------------------------
| Posts Per Page
|--------------------------------------------------------------------------
|
| 后台文章管理每页显示的文章数
|
*/
define('POSTS_PER_PAGE', 10);

/*
|--------------------------------------------------------------------------
| Comments Per Page
|--------------------------------------------------------------------------
|
| 后台评论管理每页显示的评论数
|
*/
define('COMMENTS_PER_PAGE', 10);

/*
|--------------------------------------------------------------------------
| RSS Version
|--------------------------------------------------------------------------
|
| Used in Feedwriter Class
|
*/
define('RSS1', 'RSS 1.0');
define('RSS2', 'RSS 2.0');
define('ATOM', 'ATOM');

/*
|--------------------------------------------------------------------------
| 摘要分隔符
|--------------------------------------------------------------------------
|
| Used when you want to show just excerpt of articles in the homepage
|
*/
define('CONTENT_BREAK', '[--break--]');

/*
|--------------------------------------------------------------------------
| 程序版本
|--------------------------------------------------------------------------
|
| The Version Of Fieblog
|
*/
define('APP_VERSION', '1.0');


/* End of file constants.php */
/* Location: ./application/config/constants.php */