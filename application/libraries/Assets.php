<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* Assets:: a class that stores an array of css and js scripts to be output in the view
*
* @author qi_ruo <qi_ruo@sina.com>
* @refer http://www.codeofficer.com/blog/entry/codeigniter_javascript_library/
* @version 1.0
* you should write the assets config in your application/config/assets.php
*/

class Assets 
{
	var $CI;
	var $assets = array('js' => array(), 'css' => array());
	var $path = array('js' => '', 'css' => '');

	function __construct($params = array())
	{
		$this->CI =& get_instance();

		$this->clear_asset();

		foreach (array('js', 'css') as $type) {
			// 脚本路径
			if ($params && isset($params[$type.'_path']))
			{
				$this->path[$type] = $params[$type.'_path'];
			}
			
			// 从配置文件或构造函数加载脚本
			if ($params && isset($params[$type])) {
				$this->add_asset($type, $params[$type]);
			}
		}
	}

	// 添加一个asset数组
	function add_asset($type, $items)
	{
		foreach ($items as $item) {
			if (is_array($item)) {
				$name = $item[0];
				$path = isset($item[1]) ? $item[1] : '';
				$extra = isset($item[2]) ? $item[2] : '';
				$this->add_a_asset($type, $name, $path, $extra);
			} else {
				$this->add_a_asset($type, $item);
			}
		}
		return $this;
	}

	// 添加一个asset文件
	function add_a_asset($type, $name, $path = NULL, $extra = NULL)
	{
		if (empty($path)) {
			$path = $this->path[$type];
		}

		if ($path) {
			$path = rtrim($path, '/') . '/';
		}

		$file = $path . $name;

		if (!array_key_exists($file, $this->assets[$type])) {
			$this->assets[$type][$file] = $extra;
		}
		return $this;
	}

	// 添加一个js数组
	function add_js($items) {
		return $this->add_asset('js', $items);
	}

	// 添加一个css数组
	function add_css($items) {
		return $this->add_asset('css', $items);
	}

	// 添加一个js文件
	function add_a_js($name, $path = NULL, $extra = NULL) {
		return $this->add_a_asset('js', $name, $path, $extra);
	}

	// 添加一个css文件
	function add_a_css($name, $path = NULL, $extra = NULL) {
		return $this->add_a_asset('css', $name, $path, $extra);
	}

	// 获取全部assets
	function get_asset() {
		return $this->assets;
	}

	// 获取全部js
	function get_js() {
		return $this->assets['js'];
	}

	// 获取全部css
	function get_css() {
		return $this->assets['css'];
	}

	// 清除所有assets
	function clear_asset() {
		$this->assets = array('js' => array(), 'css' => array());
		return $this;
	}
}
// END Assets Class

/* End of file Assets.php */
/* Location: ./application/libraries/Assets.php */