<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Navigations extends MY_Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		is_editor();

		$this->load->model('navigation_model');
		$this->load->model('post_model');

		$this->_data['parentPage'] = 'dashboard';
	}

	/**
	 * Navigations Index
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		redirect('admin/navigations/manage');
	}

	/**
	 * 导航管理
	 *
	 * @access	public
	 * @param	string	$nid	导航的NID
	 * @return	void
	 */
	function manage($nid = NULL)
	{
		if ($this->form_validation->run('navigation') == FALSE)
		{
			if ($nid && is_numeric($nid))
			{
				$this->_data['navigation'] = $this->navigation_model->get_navigation($nid);
			}

			$this->_data['navigations'] = $this->navigation_model->get_navigations(array('viewLink', 'manageLink'));
			$this->_data['posts'] = $this->post_model->get_posts(array('type' => 'page', 'status' => 'publish'));

			$this->assets->add_a_js('jquery-ui/jquery-ui-1.8.11.custom.min.js');

			$this->_data['title'] = '导航管理';

			$this->load->view('navigations_manage', $this->_data);
		}
		else
		{
			foreach (array('title', 'type', 'target') as $item)
			{
				$$item = $this->input->post($item, TRUE);
				$navigation_data[$item] = $$item;
			}

			$navigation_data['url'] = '';
			$navigation_data['uri'] = '';
			$navigation_data['page'] = 0;
			$navigation_data[$type] = $this->input->post($type, TRUE);

			clean_dbcache();

			if ($this->input->post('nid', TRUE))
			{
				$update = $this->navigation_model->update_navigation($nid, $navigation_data);
				$this->session->set_msg(($update >= 0),  '导航更新成功', '导航更新失败');
			}
			else
			{
				$insert = $this->navigation_model->insert_navigation($navigation_data);
				$this->session->set_msg($insert, '导航添加成功', '导航添加失败');
			}

			redirect('admin/navigations/manage/');
		}
	}

	/**
	 * 导航管理
	 *
	 * @access	public
	 * @return	void
	 */
	function operate()
	{
		foreach (array('op', 'nids') as $item)
		{
			$$item = $this->input->post($item, TRUE);
		}

		if ($nids && is_array($nids))
		{
			$deleted = 0;

			foreach ($nids as $nid)
			{
				if (empty($nid)) continue;

				if ($op == 'delete')
				{
					$deleted += $this->navigation_model->delete_navigation($nid);
				}
			}

			clean_dbcache();

			if ($op == 'delete')
			{
				$this->session->set_msg($deleted, $deleted . '个导航被删除', '没有导航被删除');
			}
		}
		else
		{
			$this->session->set_flashdata('error', '请选择需要管理的导航');
		}

		go_back();
	}
}

/* End of file navigations.php */
/* Location: ./application/controllers/admin/navigations.php */