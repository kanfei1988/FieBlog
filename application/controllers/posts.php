<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends MY_Controller
{
	private $_size;

	private $_count = 0;

	private $_where = array();

	private $_extra = array(
		'published', 'titleLink', 'authorLink', 'commentLink',
		'content', 'excerpt', 'metas'
	);

	function __construct()
	{
		parent::__construct();

		$this->load->model('post_model');

		$this->_size = settings('posts_page_size');

		$this->_where['limit'] = $this->_size;
	}

	/**
	 * 获取文章并显示
	 *
	 * @access	private
	 * @param	string	$base_url			分页基址
	 * @param	integer	$uri_segment		分页的段序号
	 * @param	bool	$page_query_string	是否开启分段的QueryString
	 * @param	bool	$is_archive			是否获取归档文章
	 * @return	void
	 */
	private function _display($base_url, $uri_segment, $page_query_string = FALSE, $is_archive = FALSE)
	{
		$this->_data['posts'] = ($is_archive) ? 
			$this->post_model->get_archives($this->_where, $this->_count, $this->_extra) :
			$this->post_model->get_posts($this->_where, $this->_count, $this->_extra);

		$this->_data['extra_css'] = '';
		$this->_data['extra_js'] = '';

		foreach ($this->_data['posts'] as $post)
		{
			if ($post['css']) $this->_data['extra_css'] .= $post['css'] . "\n";
			if ($post['js']) $this->_data['extra_js'] .= $post['js'] . "\n";
		}

		$this->load->library('pagination');

		$config = array(
			'base_url'			=> site_url($base_url),
			'uri_segment'		=> $uri_segment,
			'total_rows'		=> $this->_count,
			'per_page'			=> $this->_size,
			'page_query_string'	=> $page_query_string
		);

		$this->pagination->initialize($config);

		$this->_data['pagination'] = $this->pagination->create_links();

		$this->load->view('posts', $this->_data);
	}

	/**
	 * 显示文章列表
	 *
	 * @access	public
	 * @param	string	$offset		文章偏移量
	 * @return	void
	 */
	function index($offset = '0')
	{
		$this->_where['offset'] = $offset;

		$this->_display('posts/', 2);
	}

	/**
	 * 显示指定分类的文章列表
	 *
	 * @access	public
	 * @param	string	$slug		分类名
	 * @param	string	$offset		文章偏移量
	 * @return	void
	 */
	function category($slug = '', $offset = '0')
	{
		$this->load->model('meta_model');
		$category = $this->meta_model->get_meta('category', 'slug', $slug);

		if (empty($category)) show_error('不存在该分类的文章');

		$this->_where['meta_type'] = 'category';
		$this->_where['meta_slug'] = $slug;
		$this->_where['offset'] = $offset;

		$this->_display('category/'.$slug, 3);
	}

	/**
	 * 显示指定标签的文章列表
	 *
	 * @access	public
	 * @param	string	$slug		标签名
	 * @param	string	$offset		文章偏移量
	 * @return	void
	 */
	function tag($slug = '', $offset = '0')
	{
		$this->load->model('meta_model');
		$tag = $this->meta_model->get_meta('tag', 'slug', $slug);

		if (empty($tag)) show_error('不存在该标签的文章');

		$this->_where['meta_type'] = 'tag';
		$this->_where['meta_slug'] = $slug;
		$this->_where['offset'] = $offset;

		$this->_display('tag/'.$slug, 3);
	}

	/**
	 * 显示指定作者的文章列表
	 *
	 * @access	public
	 * @param	string	$name		用户名
	 * @param	string	$offset		文章偏移量
	 * @return	void
	 */
	function author($name = '', $offset = '0')
	{
		$this->load->model('user_model');
		$user = $this->user_model->get_user('name', $name);

		if (empty($user)) show_error('不存在该用户的文章');

		$this->_where['uid'] = 'tag';
		$this->_where['offset'] = $offset;

		$this->_display('author/'.$name, 3);
	}

	/**
	 * 显示归档的文章列表
	 *
	 * @access	public
	 * @param	string	$year		年
	 * @param	string	$month		月
	 * @param	string	$day		日
	 * @return	void
	 */
	function archive($year = '', $month = '', $day = '')
	{
		$year = intval($year);
		$month = intval($month);
		$day = intval($day);

		$url = date_str($year, $month, $day);

		if (empty($url)) redirect('posts/index');

		$this->_where['year'] = $year;
		$this->_where['month'] = $month;
		$this->_where['day'] = $day;
		$this->_where['offset'] = isset($_GET['offset']) ? $this->input->get('offset', TRUE) : 0;

		$this->_display('archive/'.$url.'?', NULL, TRUE, TRUE);
	}

	/**
	 * 显示搜索的文章列表
	 *
	 * @access	public
	 * @return	void
	 */
	function search()
	{
		if (!isset($_GET['s'])) redirect('posts/index');

		$keywords = $this->input->get('s', TRUE);

		$this->_where['keywords'] = $keywords;
		$this->_where['offset'] = isset($_GET['offset']) ? $this->input->get('offset', TRUE) : 0;

		$this->_display('search?s='.$keywords, NULL, TRUE);
	}
}

/* End of file posts.php */
/* Location: ./application/controllers/posts.php */