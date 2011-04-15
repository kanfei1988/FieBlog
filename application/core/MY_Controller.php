<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $_data = array();

	function __construct()
	{
		parent::__construct();

		if (settings('blog_status') == 'off')
		{
			show_error(settings('offline_reason'));
			exit();
		}

		$this->load->set_theme(settings('current_theme'));

		if (intval(settings('cache_enabled')))
		{
			$cache_expired = settings('cache_expire_time');

			$cache_expired = ($cache_expired && is_numeric($cache_expired) ? intval($cache_expired) : 60);

			$this->output->cache($cache_expired);
		}
	}
}

class MY_Admin_Controller extends CI_Controller
{
	protected $_data = array();

	function __construct()
	{
		parent::__construct();

		$this->load->library(array('form_validation','session', 'auth', 'assets'));

		$this->session->set_flashdata('ref', $this->uri->uri_string());

		if (!$this->auth->has_login())
		{	
			redirect('admin/login');
		}
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */