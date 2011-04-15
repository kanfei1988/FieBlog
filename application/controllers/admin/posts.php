<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends MY_Admin_Controller
{
	// 文章类型
	private $_type = array('post' => '文章', 'page' => '页面');

	// 文章状态
	private $_status = array('publish' => '发布', 'draft' => '草稿', 'waiting' => '待审核');

	// 附加参数
	private $_extra = array('metas', 'navigation', 'permalink', 'viewLink', 'writeLink', 'commentsManageLink', 'navigationLink', 'auhtorManageLink');

	function __construct()
	{
		parent::__construct();

		is_contributor();

		$this->load->model('post_model');
		$this->load->model('meta_model');
		$this->load->model('comment_model');
	}

	/**
	 * Posts Index
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		redirect('admin/posts/manage');
	}

	/**
	 * 文章/页面 管理
	 *
	 * @access	public
	 * @return	void
	 */
	function manage()
	{
		// 默认参数
		$post_filter = array(
			'uid'		=> $this->auth->get('uid'),
			'type'		=> 'post',
			'status'	=> 'publish',
			'meta_type'	=> 'category',
			'meta_slug'	=> '',
			'keywords'	=> '',
		);

		// 当前参数
		if ($this->session->userdata('post_filter') && (!isset($_GET['reset'])))
		{
			$current_post_filter = unserialize($this->session->userdata('post_filter'));

			// 合并参数
			if ($current_post_filter)
			{
				$post_filter = array_merge($post_filter, $current_post_filter);
			}
		}
	
		// 合并查询参数
		foreach ($post_filter as $key => $value)
		{
			if (isset($_GET[$key]))
			{
				$post_filter[$key] = $this->input->get($key, TRUE);
			}
		}

		// 只有编辑才能查看全部文章
		if ((intval($post_filter['uid']) === 0) && (! is_editor(1)))
		{
			$post_filter['uid'] = $this->auth->get('uid');
		}

		// 只有编辑才能查看其它用户的文章
		if ((intval($post_filter['uid']) !== intval($this->auth->get('uid'))) && (! is_editor(1)))
		{
			$post_filter['uid'] = $this->auth->get('uid');
		}

		// 保存当前查询条件为Session
		$this->session->set_userdata('post_filter', serialize($post_filter));

		$post_filter['limit'] = POSTS_PER_PAGE;
		$post_filter['offset'] = isset($_GET['offset']) ? intval($this->input->get('offset')) : 0;

		$count = array();
		$this->_data['posts'] = $this->post_model->get_posts($post_filter, $count, $this->_extra);
		$this->_data['parentPage'] = $post_filter['type'];
		$this->_data['category_list'] = $this->meta_model->get_metas('category');
		$this->_data['count'] = $count;
		foreach ($post_filter as $key => $value)
		{
			$this->_data[$key] = $value;
		}

		unset($post_filter['limit'], $post_filter['offset']);

		$this->load->library('pagination');
		$config['page_query_string'] = TRUE;
		$config['uri_segment'] = NULL;
		$config['per_page'] = POSTS_PER_PAGE;
		$config['base_url'] = site_url('admin/posts/manage?' . query_str($post_filter));
		$config['total_rows'] = $count['total'];
		$this->pagination->initialize($config);
		$this->_data['pagination'] = $this->pagination->create_links();

		$this->_data['title'] = $this->_type[$post_filter['type']] . '管理';
		
		$this->load->view('posts_manage', $this->_data);
	}

	/**
	 * 文章/页面 操作
	 *
	 * @access	public
	 * @return	void
	 */
	function operate()
	{
		foreach (array('op', 'type', 'pids', 'original_status') as $item)
		{
			$$item = $this->input->post($item, TRUE);
		}

		$post_type = $this->_type[$type];
		$post_original_status = $this->_status[$original_status];

		if ($pids && is_array($pids))
		{
			$deleted = 0;
			$updated = 0;
			
			foreach ($pids as $pid)
			{
				if (empty($pid)) continue;

				$post = $this->post_model->get_post('pid', $pid);

				if (empty($post)) continue;

				if (!(is_editor(1) || is_self($post['uid'], 1))) continue;

				if ($op == 'delete')
				{
					$deleted += $this->post_model->delete_post($pid);
				}
				else if (array_key_exists($op, $this->_status))
				{
					$updated += $this->post_model->update_post_status($pid, $op);
				}
			}

			clean_dbcache();

			if ($op == 'delete')
			{
				$this->session->set_msg($deleted, $deleted . '篇' . $post_original_status . $post_type . '被删除', '没有' . $post_type . '被删除');
			}
			else if (array_key_exists($op, $this->_status))
			{
				$post_status = $this->_status[$op];
				$this->session->set_msg($updated, $updated . '篇' . $post_original_status . $post_type. '被转为' . $post_status, '没有' . $post_type . '被转为' . $post_status);
			}
		}
		else
		{
			$this->session->set_flashdata('error', '请选择需要管理的' . $post_type);
		}

		go_back();
	}

	/**
	 * 文章/页面 编辑
	 *
	 * @access	public
	 * @param	string	$type	类型
	 * @param	string	$pid	文章/页面的PID
	 * @return	void
	 */
	function write($type = 'post', $pid = NULL)
	{
		if ($this->form_validation->run($type) == FALSE)
		{
			$timestamp = time();

			if ($pid && is_numeric($pid))
			{
				$this->_data['post'] = $this->post_model->get_post('pid', $pid, $this->_extra);

				if (!is_editor(1)) is_self($this->_data['post']['uid']);

				$timestamp = $this->_data['post']['created'];
			}

			$this->_data['type'] = $type;
			$this->_data['parentPage'] = $type;
			$this->_data['time'] = getdate($timestamp);

			if ($type == 'post')
			{
				$this->_data['categories'] = $this->meta_model->get_metas('category');
			}

			$this->_data['title'] = ($pid ? '编辑' : ($type == 'post' ? '撰写' : '创建')) . $this->_type[$type];

			$this->assets
				->add_a_css('jquery-ui-1.8.11.custom.css', 'assets/js/jquery-ui/')
				->add_a_js('jquery-ui/jquery-ui-1.8.11.custom.min.js')
				->add_a_js('jquery-ui/jquery-ui-timepicker-addon.js')
				->add_a_js('kindeditor/kindeditor.js', '', 'charset="utf-8"')
				->add_a_js('edit_area/edit_area_full.js');

			$this->load->view('posts_write', $this->_data);
		}
		else
		{
			foreach (array('type', 'uid', 'status', 'title', 'text', 'created', 'allowComment', 'allowPing', 'allowFeed', 'trackback','css', 'js', 'slug', 'category', 'tags') as $item)
			{
				$post_data[$item] = $this->input->post($item);
			}

			if ($pid) //更新文章或页面
			{
				$update = $this->post_model->update_post($pid, $post_data);

				$success_msg = $this->_type[$type] . (($post_data['status'] == 'publish') ? '已更新' : '已保存');
				$this->session->set_msg(($update >= 0) , $success_msg, $this->_type[$type] . '更新失败');
			}
			else //插入文章或页面
			{
				$pid = $this->post_model->insert_post($post_data);

				$success_msg = $this->_type[$type] . (($post_data['status']=='publish') ? '创建成功' : '已保存');
				$this->session->set_msg($pid , $success_msg, $this->_type[$type] . '创建失败');
			}

			clean_dbcache();

			if ($this->input->post('dashboard')) redirect('admin/dashboard');

			if ($post_data['status'] == 'draft')
			{
				redirect('admin/posts/write/'.$type.'/'.$pid);
			}
			else
			{
				redirect('admin/posts/manage/'.$type);
			}
		}
	}
}

/* End of file posts.php */
/* Location: ./application/controllers/admin/posts.php */