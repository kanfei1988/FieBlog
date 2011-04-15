<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends MY_Controller
{
	private $_size;

	private $_count = 0;

	private $_where = array();

	private $_extra = array(
		'published', 'titleLink', 'authorLink', 'commentLink',
		'content', 'excerpt', 'metas', 'prevLink', 'nextLink'
	);	

	private $_comment_extra = array(
		'published', 'permalink', 'authorLink', 'thread', 'content'
	);

	function __construct()
	{
		parent::__construct();

		$this->load->model('post_model');
		$this->load->model('comment_model');
		$this->load->library('htmlfixer');

		$this->_size = settings('comments_page_size');

		$this->_where['limit'] = $this->_size;
	}

	/**
	 * 显示文章
	 *
	 * @access	public
	 * @param	string	$slug		文章所率名
	 * @param	string	$offset		评论偏移量
	 * @return	void
	 */
	function index($slug = '', $offset = '0')
	{
		if (empty($slug)) redirect(site_url());

		// 获取文章
		$this->_data['post'] = $this->post_model->get_post('slug', $slug, $this->_extra);

		if ($this->_data['post']['status'] != 'publish') show_error('该文章未发布');

		if (empty($this->_data['post'])) show_error('该文章不存在');

		if ($this->_data['post']['css']) $this->_data['extra_css'] = $this->_data['post']['css']."\n";
		if ($this->_data['post']['js']) $this->_data['extra_js'] = $this->_data['post']['js']."\n";

		// 获取评论
		$this->_where['pid'] = $this->_data['post']['pid'];
		$this->_where['status'] = 'approved';
		$this->_where['offset'] = $offset;

		$this->_data['comments'] = 
			$this->comment_model->get_comments($this->_where, $this->_count, $this->_comment_extra);

		// 获取评论分页
		$this->load->library('pagination');
		$config = array(
			'base_url'			=> site_url('post/'.$slug),
			'uri_segment'		=> 3,
			'total_rows'		=> $this->_count,
			'per_page'			=> $this->_size,
		);
		$this->pagination->initialize($config);
		$this->_data['pagination'] = $this->pagination->create_links();

		$this->load->view('post', $this->_data);
	}
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */