<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		is_administrator();

		$this->load->model('user_model');

		$this->_data['parentPage'] = 'users';

		$this->_rules = array(
			array(
				'field' => 'name',
				'label' => '用户名',
				'rules' => 'required|trim|alpha_numeric|unique[users.name.uid]|strip_tags'
			),
			array(
				'field' => 'password',
				'label' => '用户密码',
				'rules' => 'min_length[6]|trim'
			),
			array(
				'field' => 'confirm',
				'label' => '确认密码',
				'rules' => 'min_length[6]|trim|matches[password]'
			),
			array(
				'field' => 'screenName',
				'label' => '用户昵称',
				'rules' => 'required|trim|unique[users.screenName.uid]|strip_tags'
			),
			array(
				'field' => 'mail',
				'label' => '电子邮箱',
				'rules' => 'required|trim|valid_email|unique[users.mail.uid]'
			),
			array(
				'field' => 'url',
				'label' => '个人主页',
				'rules' => 'trim|prep_url'
			),
			array(
				'field' => 'group',
				'label' => '用户组', 
				'rules' => 'required|trim'
			)
		);
	}

	/**
	 * Users Index
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		redirect('admin/users/manage');
	}

	/**
	 * 用户管理
	 *
	 * @access	public
	 * @param	string	$type	Meta类型
	 * @param	string	$mid	Meta的MID
	 * @return	void
	 */
	function manage()
	{
		$this->_data['users'] = $this->user_model->get_users();

		$this->_data['title'] = '用户管理';

		$this->load->view('users_manage', $this->_data);
	}

	/**
	 * 用户编辑
	 *
	 * @access	public
	 * @param	string	$uid	用户的UID
	 * @return	void
	 */
	function user($uid = NULL)
	{
		if ($uid && is_numeric($uid))
		{
			$this->_data['user'] = $this->user_model->get_user('uid', $uid);
		}
		else
		{
			$this->_rules[1]['rules'] = 'required|' . $this->_rules[1]['rules'];
			$this->_rules[2]['rules'] = 'required|' . $this->_rules[2]['rules'];
		}

		$this->form_validation->set_rules($this->_rules);

		if ($this->form_validation->run() == FALSE)
		{
			$this->_data['title'] = ($uid ? '编辑' : '添加') . '用户';
			$this->load->view('users_add', $this->_data);
		}
		else
		{
			foreach (array('name', 'password', 'screenName', 'mail', 'url', 'group') as $item)
			{
				$user_data[$item] = $this->input->post($item, TRUE);
			}

			if ($user_data['password'])
				$user_data['password'] = $this->user_model->do_hash($user_data['password']);

			if ($uid)
			{
				if (empty($user_data['password'])) unset($user_data['password']);
				$update = $this->user_model->update_user($uid, $user_data);
				$this->session->set_msg(($update >= 0), '成功更新一个用户', '更新用户失败');
			}
			else
			{
				$insert = $this->user_model->insert_user($user_data);
				$this->session->set_msg($insert, '成功添加一个用户', '添加用户失败');
			}

			redirect('admin/users/manage');
		}
	}

	/**
	 * 用户操作
	 *
	 * @access	public
	 * @return	void
	 */
	function operate()
	{
		foreach (array('op', 'uids') as $item)
		{
			$$item = $this->input->post($item, TRUE);
		}

		if ($uids && is_array($uids))
		{
			$deleted = 0;

			foreach ($uids as $uid)
			{
				if (empty($uid)) continue;

				$user = $this->user_model->get_user('uid', $uid);

				if (empty($user)) continue;

				if ($op == 'delete')
				{
					if ($uid == $this->auth->get('uid'))
					{
						$this->session->set_flashdata('error', '管理员不能删除自己');
						go_back();
					}

					$deleted += $this->user_model->delete_user($uid);
				}
			}

			if ($op == 'delete')
			{
				$this->session->set_msg($deleted, $deleted . '个用户被删除', '没有用户被删除');
			}
		}
		else
		{
			$this->session->set_flashdata('error', '请选择需要管理的用户');
		}

		go_back();
	}
}

/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */