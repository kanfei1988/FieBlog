<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 网站首页
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		if (settings('homepage_type') == 'homepage')
		{
			if (settings('homepage_pid'))
			{
				$this->load->model('post_model');
				$post = $this->post_model->get_post('pid', settings('homepage_pid'));

				if ($post && $post['status'] == 'publish')
				{
					redirect('post/'.$post['slug']);
				}
			}
		}

		redirect('posts');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */