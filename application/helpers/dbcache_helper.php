<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 获取站点设置值
 *
 * @access	public
 * @param	string	$item	设置名
 * @return	string
 */
function settings($item)
{
	static $settings = array();

	if (!isset($settings[$item]))
	{
		$CI =& get_instance();

		$CI->load->driver('cache');
		
		if (!$settings = $CI->cache->file->get('settings'))
		{
			$query = $CI->db->get('settings');

			foreach ($query->result_array() as $row)
			{
				$settings[$row['name']] = $row['value'];
			}

			$query->free_result();

			$CI->cache->file->save('settings', $settings, settings('dbcache_expire_time'));
		}
	}

	return $settings[$item];
}

/**
 * 清除数据缓存
 *
 * @access	public
 * @return	void
 */
function clean_dbcache()
{
	$CI =& get_instance();

	$CI->load->driver('cache');

	$CI->cache->file->clean();
}

/**
 * 清除所有缓存
 *
 * @access	public
 * @return	void
 */
function clean_cache()
{
	$CI =& get_instance();

	$CI->load->helper('file');

	$path = $CI->config->item('cache_path');

	delete_files($path);

	@copy(APPPATH . 'index.html', $CI->config->item('cache_path') . '/index.html');
	@copy(APPPATH . '.htaccess', $CI->config->item('cache_path') . '/.htaccess');
}

/**
 * 获取统计值并缓存
 *
 * @access	public
 * @return	string
 */
function get_stats()
{
	$CI =& get_instance();
	$CI->load->driver('cache');

	if (!$stats = $CI->cache->file->get('stats'))
	{
		$stats = array(
			'posts' => 
				$CI->db->where('type', 'post')->where('status', 'publish')->count_all_results('posts'),

			'pages' => 
				$CI->db->where('type', 'page')->where('status', 'publish')->count_all_results('posts'),

			'categories' => 
				$CI->db->where('type', 'category')->count_all_results('metas'),

			'tags' => 
				$CI->db->where('type', 'tag')->count_all_results('metas'),

			'comments' => 
				$CI->db->where('type', 'comment')->count_all_results('comments'),

			'approved_comments'	=> 
				$CI->db->where('type', 'comment')->where('status', 'approved')->count_all_results('comments'),

			'waiting_comments' => 
				$CI->db->where('type', 'comment')->where('status', 'waiting')->count_all_results('comments'),

			'spam_comments' => 
				$CI->db->where('type', 'comment')->where('status', 'spam')->count_all_results('comments')
		);

		$CI->cache->file->save('stats', $stats, settings('dbcache_expire_time'));
	}

	return $stats;
}

/**
 * 获取META并缓存
 *
 * @access	public
 * @param	string	$type	META类型
 * @return	string
 */
function get_metas($type = 'category')
{
	$CI =& get_instance();
	$CI->load->driver('cache');
	$CI->load->model('meta_model');

	if (!$metas = $CI->cache->file->get($type))
	{
		$metas = $CI->meta_model->get_metas($type, array('titleLink'));

		$CI->cache->file->save($type, $metas, settings('dbcache_expire_time'));
	}

	return $metas;
}

/**
 * 获取导航并缓存
 *
 * @access	public
 * @return	string
 */
function get_navigations()
{
	$CI =& get_instance();
	$CI->load->driver('cache');
	$CI->load->model('navigation_model');

	if (!$navigations = $CI->cache->file->get('navigations'))
	{
		$CI->load->model('navigation_model');
		$navigations = $CI->navigation_model->get_navigations();

		$CI->cache->file->save('navigations', $navigations, settings('dbcache_expire_time'));
	}

	return $navigations;
}

/**
 * 获取归档并缓存
 *
 * @access	public
 * @return	string
 */
function get_archives()
{
	$CI =& get_instance();
	$CI->load->driver('cache');
	$CI->load->model('post_model');

	if (!$archives = $CI->cache->file->get('archives'))
	{
		$archives = $CI->post_model->get_archive_count();

		$CI->cache->file->save('archives', $archives, settings('dbcache_expire_time'));
	}

	return $archives;
}

/**
 * 获取最新日志并缓存
 *
 * @access	public
 * @return	string
 */
function get_recent_posts()
{
	$CI =& get_instance();
	$CI->load->driver('cache');
	$CI->load->model('post_model');

	if (!$recent_posts = $CI->cache->file->get('recent_posts'))
	{
		$recent_posts = $CI->post_model->get_recent_posts(settings('posts_list_size'));

		$CI->cache->file->save('recent_posts', $recent_posts, settings('dbcache_expire_time'));
	}

	return $recent_posts;
}

/**
 * 获取最新评论并缓存
 *
 * @access	public
 * @return	string
 */
function get_recent_comments()
{
	$CI =& get_instance();
	$CI->load->driver('cache');
	$CI->load->model('comment_model');

	if (!$recent_comments = $CI->cache->file->get('recent_comments'))
	{
		$recent_comments = $CI->comment_model->get_recent_comments(settings('comments_list_size'));

		$CI->cache->file->save('recent_comments', $recent_comments, settings('dbcache_expire_time'));
	}

	return $recent_comments;
}

/* End of file dbcache_helper.php */
/* Location: ./application/helpers/dbcache_helper.php */