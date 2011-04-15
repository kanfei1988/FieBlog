<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Navigation_model extends CI_Model
{
	private $_navigation_type = array('url', 'uri', 'page');

	function __construct()
	{
		parent::__construct();
	}

	// --------------------------------- Retrieve ---------------------------------

	/**
	 * 获取一个导航
	 *
	 * @access	public
	 * @param	string	$nid	导航的NID
	 * @return	array			以关联数组的形式返回一个记录
	 */
	function get_navigation($nid, $extra = array())
	{
		$navigation = array();

		$query = $this->db->where('nid', $nid)->get('navigations');

		if ($query->num_rows() > 0) $navigation = $query->row_array();

		$query->free_result();

		if ($extra) $this->get_extra($navigation, $extra);

		return $navigation;
	}

	/**
	 * 获取一个导航的其他属性
	 *
	 * @access	public
	 * @param	array	$navigation	导航
	 * @param	array	$extra		属性列表
	 * @return	void
	 */
	function get_extra(&$navigation, $extra)
	{
		if (in_array('manageLink', $extra))
		{
			$navigation['manageLink'] = anchor(site_url('admin/navigations/manage/'.$navigation['nid']), mb_word_limiter($navigation['title'], 8));
		}

		if (in_array('viewLink', $extra))
		{
			$navigation['viewLink'] = '<a class="hidden" href="'.$navigation['url'].'"><img src="'.base_url().'/assets/images/view.gif" width="16" height="16"></a>';
		}
	}

	/**
	 * 获取所有导航
	 *
	 * @access	public
	 * @return	array			以关联数组的形式返回多个记录
	 */
	function get_navigations($extra = array())
	{
		$navigations = array();

		$query = $this->db->order_by('order', 'asc')->get('navigations');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if ($row['type'] == 'uri')
				{
					$row['url'] = site_url().'/'.$row['uri'];
				}
				else if ($row['type'] == 'page')
				{
					$page_query = $this->db->select('slug')
						->where('pid', $row['page'])
						->get('posts');

					if ($page_query->num_rows() > 0)
					{
						$page = $page_query->row_array();
						$row['url'] = site_url('post/'.$page['slug']);
					}
					else
					{
						$row['url'] = site_url();
					}

					$page_query->free_result();
				}

				$navigations[] = $row;
			}
		}

		$query->free_result();

		if ($extra)
		{
			foreach ($navigations as &$navigation)
			{
				$this->get_extra($navigation, $extra);
			}
		}

		return $navigations;
	}

	// ---------------------------------- Create ----------------------------------

	/**
	 * 添加一个导航
	 *
	 * @access	public
	 * @param	array	$navigation_data	导航数据
	 * @return	integer						返回新添加导航的NID
	 */
	function insert_navigation($navigation_data)
	{
		$this->db->insert('navigations', $navigation_data);

		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
	}

	// ---------------------------------- Update ----------------------------------

	/**
	 * 更新一个导航
	 *
	 * @access	public
	 * @param	integer	$nid				导航的PID
	 * @param	array	$nabigation_data	导航数据
	 * @return	integer						返回更新的行数
	 */
	function update_navigation($nid, $navigation_data)
	{
		$this->db->update('navigations', $navigation_data, array('nid' => $nid));

		return $this->db->affected_rows();
	}

	// ---------------------------------- Delete ----------------------------------

	/**
	 * 删除一个导航
	 *
	 * @access	public
	 * @param	integer	$nid	导航的NID
	 * @return	integer			返回删除导航的行数
	 */
	function delete_navigation($nid)
	{
		$this->db->delete('navigations', array('nid' => $nid));

		return $this->db->affected_rows();
	}

	/**
	 * 删除多个导航
	 *
	 * @access	public
	 * @param	array	$nids	导航的NID的数组
	 * @return	integer			返回删除导航的行数
	 */
	function delete_navigations($nids)
	{
		$deleted = 0;

		if ($nids && is_array($nids))
		{
			foreach ($nids as $nid)
			{
				$deleted += $this->delete_navigation($nid);
			}
		}

		return $deleted;
	}
}

/* End of file navigation_model.php */
/* Location: ./application/models/navigation_model.php */