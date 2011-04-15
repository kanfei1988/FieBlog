<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
	private $_user = array();

	private $_has_login = NULL;

	private $_CI;

	private $_groups = array(
		'administrator'	=> 0,
		'editor'		=> 1,
		'contributor'	=> 2
	);

	private $user_expire = 86500;

	function __construct($params = array())
	{
		foreach ($params as $key => $value)
		{
			$this->$key = $value;
		}

		$this->_CI =& get_instance();
		$this->_CI->load->model('user_model');
		$this->_CI->load->helper('cookie');

		// 如果存在session
		if ($this->_CI->session->userdata('user'))
		{
			$this->_user = unserialize($this->_CI->session->userdata('user'));
		}
		else if (get_cookie('identity') && get_cookie('remember_code'))
		{
			$query = $this->_CI->db->where('name', get_cookie('identity'))
				->where('rememberCode', get_cookie('remember_code'))
				->limit(1)
				->get('users');

			// 更新最后登录时间和密钥
			if ($query->num_rows() == 1)
			{
				$user = $query->row_array();

				$user['logged'] = time();
				$user['activated'] = $user['logged'];
				$user['token'] = sha1(time().rand());

				if ($this->_CI->user_model->update_user($user['uid'], $user))
				{
					$this->_CI->session->set_userdata('user', serialize($user));

					$this->_user = $user;
				}
			}
		}
	}

	function get($key)
	{
		if (in_array($key, array('uid', 'uname', 'mail', 'screenName', 'created', 'activated', 'logged', 'group', 'token'), TRUE) && isset($this->_user[$key]))
		{
			return $this->_user[$key];
		}
		return FALSE;
	}

	function exceed($group, $return = FALSE)
	{
		if (array_key_exists($group, $this->_groups) && $this->_groups[$this->_user['group']] <= $this->_groups[$group])
		{
			return TRUE;
		}

		if ($return)
		{
			return FALSE;
		}

		show_error('禁止访问：您的权限不足');
	}

	// 是否登录
	function has_login()
	{
		if ($this->_has_login !== NULL)
		{
			return $this->_has_login;
		}
		else
		{
			if ($this->_user && $this->_user['uid'])
			{
				$user = $this->_CI->user_model->get_user('uid', $this->_user['uid']);

				// session中的token与数据库中的相同
				if ($user && $user['token'] == $this->_user['token'])
				{
					$user['activated'] = time();

					$this->_CI->user_model->update_user($user['uid'], $user);

					return ($this->_has_login = TRUE);
				}
			}

			return ($this->_has_login = FALSE);
		}
	}
}
// END Auth Class

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */