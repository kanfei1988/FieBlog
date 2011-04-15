<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metas extends MY_Admin_Controller
{
	// Meta类型
	private $_type = array('category' => '分类', 'tag' => '标签');

	function __construct()
	{
		parent::__construct();

		is_editor();

		$this->load->model('meta_model');

		$this->_data['parentPage'] = 'metas';
	}

	/**
	 * Metas Index
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		redirect('admin/metas/manage');
	}

	/**
	 * 分类/标签 管理
	 *
	 * @access	public
	 * @param	string	$type	Meta类型
	 * @param	string	$mid	Meta的MID
	 * @return	void
	 */
	function manage($type = 'category', $mid = NULL)
	{
		if ($this->form_validation->run('meta') == FALSE)
		{
			if ($mid && is_numeric($mid))
			{
				$this->_data['meta'] = $this->meta_model->get_meta($type, 'mid', $mid);
			}

			$this->_data['type'] = $type;
			$this->_data['count'] = $this->meta_model->get_metas_count();
			$this->_data['metas'] = $this->meta_model->get_metas($type, array('postsLink', 'viewLink', 'manageLink'));

			// 分类排序的jQuery插件
			$this->assets->add_a_js('jquery-ui/jquery-ui-1.8.11.custom.min.js');

			$this->_data['title'] = $this->_type[$type] . '管理';

			$this->load->view('metas_manage', $this->_data);
		}
		else
		{
			foreach (array('type', 'name', 'slug', 'description') as $item)
			{
				$meta_data[$item] = $this->input->post($item, TRUE);
			}

			$meta_type = $this->_type[$type];

			if ($this->input->post('mid', TRUE))
			{
				$update = $this->meta_model->update_meta($mid, $meta_data);
				$this->session->set_msg(($update >= FALSE),  $meta_type. '更新成功', $meta_type . '更新失败');
			}
			else
			{
				$insert = $this->meta_model->insert_meta($meta_data);
				$this->session->set_msg($insert, $meta_type . '添加成功', $meta_type . '添加失败');
			}

			clean_dbcache();

			redirect('admin/metas/manage/' . $type);
		}
	}

	/**
	 * 分类/标签 操作
	 *
	 * @access	public
	 * @return	void
	 */
	function operate()
	{
		foreach (array('op', 'type', 'mids', 'merge') as $item)
		{
			$$item = $this->input->post($item, TRUE);
		}

		$meta_type = $this->_type[$type];

		if ($mids && is_array($mids))
		{
			$deleted = 0;
			$merged = 0;

			foreach ($mids as $mid)
			{
				if (empty($mid)) continue;

				if ($op == 'delete')
				{
					$deleted += $this->meta_model->delete_meta($mid);
		
				}
				else if ($op == 'merge')
				{
					if ($type == 'tag')
					{
						$merge = $this->meta_model->scan_tags($merge);

						if (empty($merge))
						{
							$this->session->set_flashdata('error', '合并到的标签名不合法');
							redirect('admin/metas/manage/tag');
						}
					}
					$merged += $this->meta_model->merge_metas($merge, $mids);
					$this->session->set_msg($merged, $meta_type . '已被合并', '没有' . $meta_type . '被合并');
				}
			}

			clean_dbcache();

			if ($op == 'delete')
			{
				$this->session->set_msg($deleted, $deleted . '个' . $meta_type . '被删除', '没有' . $meta_type . '被删除');
			}
			else if ($op == 'merge')
			{
				$this->session->set_msg($delete, $merged . '个' . $meta_type . '被合并', '没有' . $meta_type . '被合并');
			}
		}
		else
		{
			$this->session->set_flashdata('error', '请选择需要管理的' . $meta_type);
		}
		
		go_back();
	}
}

/* End of file metas.php */
/* Location: ./application/controllers/admin/metas.php */