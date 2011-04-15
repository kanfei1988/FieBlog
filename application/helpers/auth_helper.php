<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 是否是管理员
 *
 * @access	public
 * @param	bool	$return	是否是返回模式
 * @return	mixed
 */
if ( ! function_exists('is_administrator'))
{
	function is_administrator($return = FALSE)
	{
		$CI =& get_instance();
		$CI->load->library('auth');

		return $CI->auth->exceed('administrator', $return);
	}
}

/**
 * 是否是编辑
 *
 * @access	public
 * @param	bool	$return	是否是返回模式
 * @return	mixed
 */
if ( ! function_exists('is_editor'))
{
	function is_editor($return = FALSE)
	{
		$CI =& get_instance();
		$CI->load->library('auth');

		return $CI->auth->exceed('editor', $return);
	}
}

/**
 * 是否是贡献者
 *
 * @access	public
 * @param	bool	$return	是否是返回模式
 * @return	mixed
 */
if ( ! function_exists('is_contributor'))
{
	function is_contributor($return = FALSE)
	{
		$CI =& get_instance();
		$CI->load->library('auth');

		return $CI->auth->exceed('contributor', $return);
	}
}

/**
 * 是否是当前用户的UID
 *
 * @access	public
 * @param	integer	$uid	用户的UID
 * @param	bool	$return	是否是返回模式
 * @return	mixed
 */
function is_self($uid, $return = FALSE)
{
	$CI =& get_instance();
	$CI->load->library('auth');

	if ($uid == $CI->auth->get('uid')) return TRUE;

	if ($return) return FALSE;

	show_error('禁止访问：您的权限不足');

	return;
}

/* End of file auth_helper.php */
/* Location: ./application/helpers/auth_helper.php */