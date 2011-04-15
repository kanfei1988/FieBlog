<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 返回当前主题的URL
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('theme_url'))
{
	function theme_url()
	{
		$CI =& get_instance();
		return $CI->config->slash_item('base_url') . rtrim(THEMEPATH, '/'). '/' . 
			settings('current_theme') . '/';
	}
}

/**
 * 跳转到上一页的URL
 *
 * @access	public
 * @return	void
 */
if ( ! function_exists('go_back'))
{
	function go_back()
	{
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();

		redirect($referer);
	}
}

/**
 * 从数组构建查询串
 *
 * @access	public
 * @param	$params		关联数组
 * @return	void		返回查询串
 */
if ( ! function_exists('query_str'))
{
	function query_str ($params)
	{
		if ( ! is_array($params) || count($params) == 0 ) return '';

		$fga = func_get_args();

		$akey = ( !isset($fga[1]) ) ? false : $fga[1];

		static $out = Array();
		
		foreach ($params as $key => $val)
		{
			if (is_array($val))
			{
				query_str($val, $key);
				continue;
			}

			$thekey = ( ! $akey) ? $key : $akey. '[' . $key . ']';
			$out[] = $thekey . "=" . $val;
		}
		
		return implode("&", $out);
	}
}

/**
 * 为URL构建日期串
 *
 * @access	public
 * @param	integer	$year	年
 * @param	integer	$month	月
 * @param	integer	$day	日
 * @return	void			返回日期串
 */
if ( ! function_exists('date_str'))
{
	function date_str($year, $month, $day)
	{
		$url = '';

		if ($year >= 2000 && $year <= 2099)
		{
			if ($month >= 1 && $month <= 13)
			{
				if ($day >= 1 && $day <= intval(date('t', mktime(0, 0, 0, $month, 1, $year))))
				{
					$url = sprintf('%4d/%02d/%02d', $year, $month, $day);
				}
				else
				{
					$url = sprintf('%4d/%02d', $year, $month);
				}
			}
			else
			{
				$url = $year;
			}
		}

		return $url;
	}
}

/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */