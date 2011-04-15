<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{

	private $_unique_key = array('uid', 'name', 'mail', 'screenName');

	private $_salt_length = 10;

	private $_group_map = array('administrator' => '管理员', 'editor' => '编辑', 'contributor' => '贡献者');

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 加密密码
	 *
	 * @access	public
	 * @param	string	$string	原密码
	 * @param	string	$salt	密钥
	 * @return	string			返回加密的密码
	 */
	function do_hash($string, $salt = NULL)
	{
		if ($salt)
		{
			$salt = substr($salt, 0, $this->_salt_length);
		}
		else
		{
			$salt = substr(md5(uniqid(rand(), TRUE)), 0, $this->_salt_length);
		}
		
		return $salt . substr(sha1($salt . $string), 0, -$this->_salt_length);
	}

	// --------------------------------- Retrieve ---------------------------------

	/**
	 * 获取一个用户
	 *
	 * @access	public
	 * @param	string	$key	索引列
	 * @param	string	$value	索引值
	 * @return	array			以关联数组的形式返回一个记录
	 */
	function get_user($key, $value)
	{
		$user = array();

		$query = $this->db->where($key, $value)->get('users');

		if ($query->num_rows() > 0) $user = $query->row_array();

		$query->free_result();

		return $user;
	}

	/**
	 * 获取所有的用户
	 *
	 * @access	public
	 * @return	array	以关联数组的形式返回多个记录
	 */
	function get_users()
	{
		$users = array();

		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $user)
			{
				$user['groupName'] = $this->_group_map[$user['group']];
				$users[] = $user;
			}
		}

		$query->free_result();

		return $users;
	}

	/**
	 * 检查一个用户是否存在
	 *
	 * @access	public
	 * @param	string	$key			索引列
	 * @param	string	$value			索引值
	 * @param	string	$exclude_uid	需要忽略的UID
	 * @return	bool					用户是否存在
	 */
	function check_user_exist($key, $value, $exclude_uid)
	{
		if (in_array($key, $this->_unique_key) && $value)
		{
			$this->db->where($key, $value);

			if ($exclude_uid && is_numeric($exclude_uid))
			{
				$this->db->where('uid <>', $exclude_uid);
			}

			return ($this->db->count_all_results('users') > 0);
		}

		return FALSE;
	}

	/**
	 * 根据用户名和密码验证一个用户
	 *
	 * @access	public
	 * @param	string	$name		用户名
	 * @param	string	$password	密码
	 * @return	bool				是否通过验证
	 */
	function validate_user($name, $password)
	{
		if (empty($name) || empty($password)) return FALSE;

		$user = array();

		$query = $this->db->where('name', $name)->get('users');

		if ($query->num_rows() > 0) $user = $query->row_array();

		$query->free_result();

		if ($user && ($this->do_hash($password, $user['password']) === $user['password']))
		{
			return $user;
		}

		return FALSE;
	}
	// ---------------------------------- Create ----------------------------------

	/**
	 * 添加一个用户
	 *
	 * @access	public
	 * @param	array	$user_data	用户数据
	 * @return	integer				返回新添加用户的UID
	 */
	function insert_user($user_data)
	{
		if (empty($user_data['screenName'])) $user_data['screenName'] = $user_data['name'];

		$user_data['created'] = time();

		$user_data['password'] = $this->do_hash($user_data['password']);

		$this->db->insert('users', $user_data);

		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
	}
	
	// ---------------------------------- Update ----------------------------------

	/**
	 * 更新一个用户
	 *
	 * @access	public
	 * @param	integer	$uid		用户的UID
	 * @param	array	$user_data	用户数据
	 * @return	integer				返回更新的行数
	 */
	function update_user($uid, $user_data)
	{
		$this->db->update('users', $user_data, array('uid' => $uid));

		return $this->db->affected_rows();
	}

	/**
	 * 更新用户的postsNum和commentsNum
	 *
	 * @access	public
	 * @param	integer	$uid	用户的UID
	 * @param	string	$field	列名('postsNum'或'commentsNum')
	 * @param	string	$type	类型('+'或'-')
	 * @return	integer			返回更新的行数
	 */
	function update_user_field($uid, $field, $type)
	{
		$this->db->set($field, $field . $type . '1', FALSE)
			->where('uid', $uid)
			->update('users');

		return $this->db->affected_rows();
	}

	/**
	 * 刷新用户的评论数
	 *
	 * @access	public
	 * @param	integer	$owner_id	用户的UID
	 * @return	void
	 */
	function refresh_user_commentsNum($owner_id)
	{
		$uids = array();

		if ($owner_id)
		{
			$uids[] = $owner_id;
		}
		else
		{
			$query = $this->db->select('uid')->get('users');
			if ($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $user)
				{
					$uids[] = $user['uid'];
				}
			}
		}

		foreach ($uids as $uid)
		{
			$count = $this->db->where('ownerId', $uid)->count_all_results('comments');
			$this->db->update('users', array('commentsNum' => $count), array('uid' => $uid));
		}
		
		return;
	}
	
	// ---------------------------------- Delete ----------------------------------

	/**
	 * 删除一个用户
	 *
	 * @access	public
	 * @param	integer	$uid	用户的UID
	 * @return	integer			返回删除用户的行数
	 */
	function delete_user($uid)
	{
		//删除该用户
		$this->db->delete('users', array('uid' => $uid)); 

		$deleted = $this->db->affected_rows();

		if ($deleted)
		{
			// 删除用户的所有文章和页面
			$CI =& get_instance();
			$CI->load->model('post_model');
			$CI->post_model->delete_posts_by_user($uid);
		}

		return $deleted;
	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */