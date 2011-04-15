<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	private $_data = array();

	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		$this->load->helper('cookie');
		$this->load->library(array('session', 'auth', 'form_validation'));
	}

	/**
	 * 用户登录
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		$ref = $this->session->flashdata('ref');

		if (empty($ref)) $ref = 'admin/dashboard';

		if ($this->auth->has_login()) redirect($ref);

		$this->form_validation->set_rules('name', '用户名', 'required|min_length[2]|trim');
		$this->form_validation->set_rules('password', '密码', 'required|trim');
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->keep_flashdata('ref');
			$this->load->view('login', $this->_data);
		}
		else
		{
			$name = $this->input->post('name', TRUE);
			$password = $this->input->post('password', TRUE);
			$remember = $this->input->post('remember', TRUE);

			// 判断name和password
			$user = $this->user_model->validate_user($name, $password);

			// 正确
			if($user)
			{
				// 更新用户数据
				$user['logged'] = time();
				$user['activated'] = $user['logged'];
				$user['token'] = sha1(time().rand());
				if ($this->user_model->update_user($user['uid'], $user))
				{
					$this->session->set_userdata('user', serialize($user));
				}

				// 如果选择记住我
				if ($remember == '1')
				{
					// 更新RememberCode
					$remember_code = sha1(time().rand());
					$user['rememberCode'] = $remember_code;
					$this->user_model->update_user($user['uid'], $user);

					if ($this->db->affected_rows() > 0)
					{
						// 生成COOKIE
						set_cookie(array(
							'name'		=> 'identity',
							'value'		=> $user['name'],
							'expire'	=> $this->config->item('user_expire')
						));

						set_cookie(array(
							'name'		=> 'remember_code',
							'value'		=> $remember_code,
							'expire'	=> $this->config->item('user_expire')
						));
					}
				}
				else
				{
					// 删除RememberCode
					$user['rememberCode'] = '';
					$this->user_model->update_user($user['uid'], $user);

					// 删除Cookie
					if (get_cookie('identity')) delete_cookie('identity');
					if (get_cookie('remember_code')) delete_cookie('remember_code');
				}

				redirect($ref);
			}
			else
			{
				$this->session->set_flashdata('error', '用户名或密码错误');
				$this->session->keep_flashdata('ref');
				redirect('admin/login');
			}
		}
	}

	/**
	 * 用户退出
	 *
	 * @access	public
	 * @return	void
	 */
	function logout()
	{
		// 删除RememberCode
		$user = unserialize($this->session->userdata('user'));
		if ($user)
		{
			$this->user_model->update_user($user['uid'], array('rememberCode' => ''));
		}

		// 删除Session
		$this->session->unset_userdata('user');
		$this->session->sess_destroy();

		// 删除Cookie
		if (get_cookie('identity')) delete_cookie('identity');
		if (get_cookie('remember_code')) delete_cookie('remember_code');

		redirect('admin/login');
	}
}

/* End of file login.php */
/* Location: ./application/controllers/admin/login.php */