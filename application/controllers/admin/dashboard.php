<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->auth->exceed('contributor');

		$this->load->model('post_model');
		$this->load->model('comment_model');
		$this->load->model('meta_model');

		$this->_data['parentPage'] = 'dashboard';
	}

	/**
	 * Dashboard Index
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		$where = array(
			'uid'		=> $this->auth->get('uid'),
			'limit'		=> 8,
			'offset'	=> 0
		);
		$this->_data['posts'] = $this->post_model->get_posts($where, $count, array('permalink', 'writeLink', 'commentsManageLink', 'viewLink'));
		$this->_data['comments'] = $this->comment_model->get_comments($where, $count, array('authorLink', 'permalink', 'manageLink', 'viewLink'));

		$this->_data['type'] = 'post';
		$this->_data['uid'] = $this->auth->get('uid');
		$this->_data['categories'] = $this->meta_model->get_metas('category');
		$this->_data['count'] = get_stats();

		$this->_data['title'] = '网站概要';

		$this->load->view('dashboard', $this->_data);
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */