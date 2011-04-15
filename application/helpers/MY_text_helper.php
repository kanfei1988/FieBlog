<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * UTF8 Word Limiter
 *
 * Limits a string to X number of words.
 *
 * @access	public
 * @param	string	$str		处理的字符串
 * @param	integer	$n			截取的字符数
 * @param	string	$end_char	结束字符
 * @return	string
 */
if ( ! function_exists('mb_word_limiter'))
{
	function mb_word_limiter($str, $n = 100, $end_char = '&#8230;')
	{
		if (mb_strlen($str, 'utf-8') < $n)
		{
			return $str;
		}

		return mb_substr($str, 0, $n, 'utf-8') . $end_char;
	}
}

/**
 * 修整缩略名
 *
 * @access	public
 * @param	string	$str		需要生成缩略名的字符串
 * @param	string	$default	默认的缩略名
 * @param	integer	$maxLength	缩略名最大长度
 * @param	string	$charset	字符编码
 * @return	string
 */
function repair_slugName($str, $default = NULL, $maxLength = 200, $charset = 'UTF-8')
{
	$str = str_replace(array("'", ":", "\\", "/"), "", $str);
	$str = str_replace(array("+", ",", " ", ".", "?", "=", "&", "!", "<", ">", "(", ")", "[", "]", "{", "}"), "_", $str);
	$str = trim($str, '_');
	$str = empty($str) ? $default : $str;
	
	return function_exists('mb_get_info') ? mb_strimwidth($str, 0, 128, '', $charset) : substr($str, $maxLength);
}


/**
 * 是否存在分割符标记
 *
 * @access	public
 * @param	string $content 输入串
 * @return	bool
 */
if ( ! function_exists('has_break'))
{
	function has_break($content)
	{
		if(strpos($content, CONTENT_BREAK) !== FALSE)
		{
			return TRUE;
		}
		
		return FALSE;
	}
}

/**
 * 去除内容中的分割符标记
 *
 * @access	public
 * @param	string $content 输入串
 * @return	string
 */
if ( ! function_exists('remove_break'))
{
	function remove_break($content)
	{
		$content = str_replace(CONTENT_BREAK, '', $content);
		
		return $content;
	}
}

/**
 * 获取文章的内容
 *
 * @access	public
 * @param	string $content 输入串
 * @return	string
 */
if ( ! function_exists('get_post_content'))
{
	function get_post_content($string)
	{
		if (empty($string)) return '';

		if (has_break($string))
		{
			$string = remove_break($string);
		}

		return $string;
	}
}

/**
 * 根据分割符的位置获取摘要
 *
 * @access	public
 * @param	string $content 输入串
 * @return	string
 */
if ( ! function_exists('get_post_excerpt'))
{
	function get_post_excerpt($string)
	{
		if (has_break($string))
		{
			list($excerpt) = explode(CONTENT_BREAK, $string);

			if (empty($excerpt)) return $string;

			$CI =& get_instance();
			$CI->load->library('htmlfixer');
			return $CI->htmlfixer->getFixedHtml($excerpt);
		}

		return $string;
	}
}

/**
 * 获取评论的内容
 *
 * @access	public
 * @param	string $content 输入串
 * @return	string
 */
if ( ! function_exists('get_comment_content'))
{
	function get_comment_content($string)
	{
		$string = strip_tags($string, settings('comments_allowed_html'));

		$CI =& get_instance();
		$CI->load->library('htmlfixer');
		return $CI->htmlfixer->getFixedHtml($string);
	}
}

/* End of file MY_text_helper.php */
/* Location: ./application/helpers/MY_text_helper.php */