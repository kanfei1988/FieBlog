<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Admin_Controller
{
	private $_title = array(
		'general'		=> '基本设置',
		'discussion'	=> '评论设置',
		'reading'		=> '文章设置',
		'cache'			=> '缓存设置'
	);

	// 设置名称
	private $_settings = array(
		'general'		=> array(
			'blog_title', 'blog_slogan', 'blog_description',
			'blog_keywords', 'homepage_type', 'homepage_pid',
			'current_theme', 'current_theme_format', 'blog_status', 
			'offline_reason', 'upload_dir', 'upload_max_size', 
			'upload_exts', 'upload_img_exts'
		),
		'discussion'	=> array(
			'comments_date_format', 'comments_page_size', 'comments_list_size', 
			'comments_url_no_follow', 'comments_require_moderation', 'comments_auto_close', 
			'comments_require_mail', 'comments_require_url', 'comments_allowed_html'
		),
		'reading'		=> array(
			'post_date_format', 'posts_page_size', 'posts_list_size', 
			'posts_full_text', 'feed_full_text'
		),
		'cache'			=> array(
			'cache_enabled', 'cache_expire_time', 'dbcache_expire_time'
		)
	);
	
	// 设置默认值
	private $_default = array(
		'upload_max_size'			=> 5120,
		'comments_date_format'		=> 'Y-m-d H:i',
		'comments_page_size'		=> 10,
		'comments_list_size'		=> 5,
		'post_date_format'			=> 'Y-m-d H:i',
		'posts_page_size'			=> 10,
		'posts_list_size'			=> 5,
		'cache_expire_time'			=> 10,
		'cache_file_limit'			=> 200
	);

	function __construct()
	{
		parent::__construct();

		$this->auth->exceed('administrator');

		$this->load->model('setting_model');

		$this->_data['parentPage'] = 'settings';
	}

	/**
	 * 修改设置
	 *
	 * @access	public
	 * @param	string	$type	设置类型
	 * @return	void
	 */
	function index($type = 'general')
	{
		if ( ! array_key_exists($type, $this->_title)) show_404();

		if ($this->form_validation->run('settings_'.$type) == FALSE)
		{
			if ($type == 'general')
			{
				$this->load->model('post_model');
				$this->_data['posts'] = $this->post_model->get_posts(array('type' => 'page', 'status' => 'publish'));
				$this->_data['themes'] = $this->setting_model->get_themes();
			}
			
			$this->_data['title'] = $this->_title[$type];
			$this->_data['settings'] = $this->setting_model->get_settings();

			$this->load->view('settings_'.$type, $this->_data);
		}
		else
		{
			foreach ($this->_settings[$type] as $item)
			{
				$settings[$item] = $this->input->post($item, TRUE);

				if (empty($settings[$item]) && isset($this->_default[$item]))
					$settings[$item] = $this->_default[$item];
			}

			if ($type == 'general')
			{
				if (substr($settings['upload_dir'], -1) != '/')
					$settings['upload_dir'] .= '/';

				if ($settings['homepage_type'] == 'posts')
					$settings['homepage_pid'] = '';
			}

			$updated = $this->setting_model->update_settings($settings);

			clean_dbcache();
			
			$this->session->set_msg(($updated >= 0), '配置已更新', '配置更新失败');

			redirect('admin/settings/index/'.$type);
		}
	}

	/**
	 * 清除缓存
	 *
	 * @access	public
	 * @return	void
	 */
	function clean_cache()
	{
		clean_cache();

		$this->session->set_flashdata('success', '缓存已刷新');

		go_back();
	}

	/**
	 * 备份数据库
	 *
	 * @access	public
	 * @return	void
	 */
	function backup()
	{
		$this->load->dbutil();

		$backup =& $this->dbutil->backup();

		$this->load->helper('download');

		$file_name = 'fieblog-'.date('Y-m-d').'.sql.gz';

		force_download($file_name, $backup);
	}
}

/* End of file settings.php */
/* Location: ./application/controllers/admin/settings.php */