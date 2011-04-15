<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends MY_Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');

		$this->_data['parentPage'] = 'dashboard';
	}

	/**
	 * 修改个人设置
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		if ($this->form_validation->run('profile') == FALSE)
		{
			$this->_data['title'] = '个人设置';
			$this->_data['user'] = $this->user_model->get_user('uid', $this->auth->get('uid'));

			$this->load->view('profile', $this->_data);
		}
		else
		{
			foreach (array('password', 'screenName', 'mail', 'url') as $item)
			{
				$user_data[$item] = $this->input->post($item, TRUE);
			}

			if ($user_data['password'])
				$user_data['password'] = $this->user_model->do_hash($user_data['password']);

			if (empty($user_data['password'])) unset($user_data['password']);
			$update = $this->user_model->update_user($this->auth->get('uid'), $user_data);
			$this->session->set_msg(($update >= 0), '个人设置已更新', '个人设置更新失败');

			redirect('admin/profile');
		}
	}
}

/* End of file profile.php */
/* Location: ./application/controllers/admin/profile.php */