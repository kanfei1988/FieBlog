<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// --------------------------------- Retrieve ---------------------------------

	/**
	 * 获取所有的设置
	 *
	 * @access	public
	 * @return	array			以关联数组的形式返回所有设置
	 */
	function get_settings()
	{
		$settings = array();

		$query = $this->db->get('settings');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$settings[$row->name] = $row->value;
			}
		}

		return $settings;
	}

	/**
	 * 获取所有的主题
	 *
	 * @access	public
	 * @return	array			返回主题列表
	 */
	function get_themes()
	{
		$themes = array();

		$this->load->helper('directory');

		$themes = directory_map(THEMEPATH, 1);

		$themes = array_diff($themes, array('index.html'));

		return $themes;
	}

	// ---------------------------------- Delete ----------------------------------

	/**
	 * 更新一个设置
	 *
	 * @access	public
	 * @param	string	$name	设置名
	 * @param	string	$value	设置值
	 * @return	integer			返回更新的行数
	 */
	function update_setting($name, $value)
	{
		$this->db->update('settings', array('value' => $value), array('name' => $name));

		return $this->db->affected_rows();
	}

	/**
	 * 更新多个设置
	 *
	 * @access	public
	 * @param	array	$settings	设置数组
	 * @return	integer				返回更新的行数
	 */
	function update_settings($settings)
	{
		$affected_rows = 0;

		foreach ($settings as $name => $value)
		{
			$affected_rows += $this->update_setting($name, $value);
		}

		return $affected_rows;
	}
}

/* End of file setting_model.php */
/* Location: ./application/models/setting_model.php */