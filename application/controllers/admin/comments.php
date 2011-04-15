<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends MY_Admin_Controller
{
	// 评论状态
	private $_status = array('approved' => '通过', 'waiting' => '待审核', 'spam' => '垃圾');

	function __construct()
	{
		parent::__construct();

		is_contributor();

		$this->load->model('comment_model');
		$this->load->model('post_model');

		$this->_data['parentPage'] = 'dashboard';
	}

	/**
	 * Comments Index
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		redirect('admin/comments/manage');
	}

	/**
	 * 评论管理
	 *
	 * @access	public
	 * @return	void
	 */
	function manage()
	{
		// 默认参数
		$comment_filter = array(
			'uid'		=> $this->auth->get('uid'),
			'pid'		=> 0,
			'status'	=> 'approved',
			'keywords'	=> ''
		);

		// 当前参数
		if ($this->session->userdata('comment_filter') && (!isset($_GET['reset'])))
		{
			$current_comment_filter = unserialize($this->session->userdata('comment_filter'));

			if ($current_comment_filter)
			{
				$comment_filter = array_merge($comment_filter, $current_comment_filter);
			}
		}

		// 合并查询参数
		foreach ($comment_filter as $key => $value)
		{
			if (isset($_GET[$key]))
			{
				$comment_filter[$key] = $this->input->get($key, TRUE);
			}
		}

		// 只有编辑才能查看全部评论
		if ((intval($comment_filter['uid']) === 0) && (! is_editor(1)))
		{
			$comment_filter['uid'] = $this->auth->get('uid');
		}

		// 只有编辑才能查看其它用户的评论
		if ((intval($comment_filter['uid']) !== intval($this->auth->get('uid'))) && (! is_editor(1)))
		{
			$comment_filter['uid'] = $this->auth->get('uid');
		}

		// 保存当前查询条件为Session
		$this->session->set_userdata('comment_filter', serialize($comment_filter));

		$comment_filter['limit'] = COMMENTS_PER_PAGE;
		$comment_filter['offset'] = isset($_GET['offset']) ? intval($this->input->get('offset')) : 0;

		$count = array();
		$this->_data['comments'] = $this->comment_model->get_comments($comment_filter, $count, array('postLink', 'authorLink'));
		$this->_data['count'] = $count;
		foreach ($comment_filter as $key => $value)
		{
			$this->_data[$key] = $value;
		}

		unset($comment_filter['limit'], $comment_filter['offset']);

		$this->load->library('pagination');
		$config['page_query_string'] = TRUE;
		$config['per_page'] = COMMENTS_PER_PAGE;
		$config['base_url'] = site_url('admin/comments/manage?' . query_str($comment_filter));
		$config['total_rows'] = $count['total'];
		$this->pagination->initialize($config);
		$this->_data['pagination'] = $this->pagination->create_links();

		$this->_data['title'] = '评论管理';
		
		$this->load->view('comments_manage', $this->_data);
	}

	/**
	 * 评论操作
	 *
	 * @access	public
	 * @return	void
	 */
	function operate()
	{
		foreach (array('op', 'cids', 'original_status') as $item)
		{
			$$item = $this->input->post($item, TRUE);
		}

		$comment_original_status = $this->_status[$original_status];

		if ($op == 'delete-spam')
		{
			$uid = ($uid === '0' && $this->auth->exceed('editor')) ? $uid : $this->auth->get('uid');

			$deleted = $this->comment_model->delete_spam_comments($uid);
			$this->user_model->refresh_user_commentsNum($uid);
			$this->session->set_msg($deleted, $deleted . '条垃圾评论被删除', '没有垃圾评论被删除');
		}
		else if ($cids && is_array($cids))
		{
			$deleted = 0;
			$updated = 0;
			
			foreach ($cids as $cid)
			{
				if (empty($cid)) continue;

				$comment = $this->comment_model->get_comment($cid);

				if (empty($comment)) continue;

				if (!(is_editor(1) || is_self($comment['ownerId'], 1))) continue;

				if ($op == 'delete')
				{
					$deleted += $this->comment_model->delete_comment($cid);
				}
				else if (array_key_exists($op, $this->_status))
				{
					$updated += $this->comment_model->update_comment_status($cid, $op);
				}
			}

			if ($op == 'delete')
			{
				$this->session->set_msg($deleted, $deleted . '条评论被删除', '没有评论被删除');
			}
			else if (array_key_exists($op, $this->_status))
			{
				$comment_status = $this->_status[$op];
				$this->session->set_msg($updated, $updated . '条' . $comment_original_status . '评论被转为' . $comment_status, '没有评论被转为' . $comment_status);
			}
		}
		else
		{
			$this->session->set_flashdata('error', '请选择需要管理的评论');
		}

		go_back();
	}
}

/* End of file comments.php */
/* Location: ./application/controllers/admin/comments.php */