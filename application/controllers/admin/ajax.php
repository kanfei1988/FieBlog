<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		is_administrator();

		if ( ! $this->input->is_ajax_request()) show_404();
	}

	/**
	 * 导航排序
	 *
	 * @access	public
	 * @return	void
	 */
	function sort_navigations()
	{
		$navigations = $this->input->get('navigation');

		$updated = 0;

		if ($navigations)
		{
			$order = 1;
			foreach ($navigations as $nid)
			{
				$this->db->update('navigations', array('order' => $order), array('nid' => $nid));
				$updated += $this->db->affected_rows();
				$order ++;
			}

			$this->load->driver('cache');
			if ($this->cache->file->get('navigations'))
			{
				$this->cache->file->delete('navigations');
			}
		}

		echo $updated;
	}

	/**
	 * 分类排序
	 *
	 * @access	public
	 * @return	void
	 */
	function sort_categories()
	{
		$metas = $this->input->get('meta');

		$updated = 0;
	
		if ($metas)
		{
			$order = 1;
			foreach ($metas as $mid)
			{
				$this->db->update('metas', array('order' => $order), array('mid' => $mid));
				$updated += $this->db->affected_rows();
				$order ++;
			}
		}

		echo $updated;
	}
}

/* End of file ajax.php */
/* Location: ./application/controllers/admin/ajax.php */