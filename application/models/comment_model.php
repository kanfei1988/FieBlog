<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends CI_Model
{
	private $_type = array('comment', 'trackback');

	private $_status = array('approved', 'waiting', 'spam');

	function __construct()
	{
		parent::__construct();
	}

	// --------------------------------- Retrieve ---------------------------------

	/**
	 * 获取一条评论
	 *
	 * @access	public
	 * @param	integer	$cid	评论的CID
	 * @return	array			以关联数组的形式返回一个记录
	 */
	function get_comment($cid)
	{
		$comment = array();

		$query = $this->db->where('cid', $cid)->get('comments');

		if ($query->num_rows() > 0) $comment = $query->row_array();

		$query->free_result();

		return $comment;
	}

	/**
	 * 获取一条评论的其他属性
	 *
	 * @access	public
	 * @param	array	$comment	评论
	 * @param	array	$extra		属性列表
	 * @return	void
	 */
	function get_extra(&$comment, $extra)
	{
		// 发布时间
		if (in_array('published', $extra))
		{
			$comments_date_format = settings('comments_date_format') ? 
				settings('comments_date_format') : 'Y-m-d H:i';
			$comment['published'] = date($comments_date_format, $comment['created']);
		}

		// 评论链接
		if (in_array('permalink', $extra))
		{
			$comment['permalink'] = site_url('post/'.$comment['post_slug'].'#comment-'.$comment['cid']);
		}

		// 浏览链接
		if (in_array('viewLink', $extra))
		{
			$comment['viewLink'] = ($comment['status'] == 'approved') ? anchor(site_url('post/'.$comment['post_slug']).'#comment-'.$comment['cid'], '<img src="'.base_url().'/assets/images/view.gif" />', 'class="hidden"') : '&nbsp;';
		}

		// 管理链接
		if (in_array('manageLink', $extra))
		{
			$comment['manageLink'] = site_url('admin/comments/manage?reset=1&status='.$comment['status'].'&uid='.$comment['ownerId'].'&pid='.$comment['pid']);
		}

		// 文章链接
		if (in_array('postLink', $extra))
		{
			$comment['postLink'] = anchor(site_url('post/'.$comment['post_slug']), mb_word_limiter($comment['post_title'],10));
		}

		// 查看链接(前)
		if (in_array('titleLink', $extra))
		{
			$comment['titleLink'] = anchor(site_url('post/'.$comment['post_slug'].'#comment-'.$comment['cid']), mb_word_limiter($comment['post_title'],10));
		}

		// 作者链接
		if (in_array('authorLink', $extra))
		{
			$comment['authorLink'] = $comment['author'];

			if ($comment['url'])
			{
				$nofollow = array();

				if (settings('comments_url_no_follow'))
					$nofollow = array('rel' => 'external nofollow');

				$comment['authorLink'] = anchor($comment['url'], $comment['author'], $nofollow);
			}
		}

		// 父级留言
		if (in_array('thread', $extra))
		{
			$comment['thread'] = $this->get_threaded_comments($comment['parent'], TRUE);
		}

		// 正文
		if (in_array('content', $extra))
		{
			$comment['content'] = get_comment_content($comment['text']);
		}
	}
	
	/**
	 * 根据条件数组 获取多条评论
	 *
	 * @access	public
	 * @param	array	$where	条件数组
	 * @param	integer	&$count	结果行数
	 * @return	array			以关联数组的形式返回多个记录
	 */
	function get_comments($where, &$count = '', $extra = array())
	{
		$comments = array();

		$this->db->start_cache();

		if (isset($where['uid']) && $where['uid'] && is_numeric($where['uid']))
		{
			$this->db->where('comments.ownerId', intval($where['uid']));
		}

		if (isset($where['pid']) && $where['pid'] && is_numeric($where['pid']))
		{
			$this->db->where('comments.pid', intval($where['pid']));
		}

		if (isset($where['type']) && in_array($where['type'], $this->_type, TRUE))
		{
			$this->db->where('comments.type', $where['type']);
		}

		if (isset($where['keywords']) && $where['keywords'])
		{
			$this->db->like('comments.text', $where['keywords']);
		}

		$this->db->stop_cache();

		if (is_array($count))
		{
			$count['approved'] = $this->db->where('status', 'approved')->count_all_results('comments');
			$count['waiting'] = $this->db->where('status', 'waiting')->count_all_results('comments');
			$count['spam'] = $this->db->where('status', 'spam')->count_all_results('comments');
		}

		if (isset($where['status']) && in_array($where['status'], $this->_status, TRUE))
		{
			$this->db->where('comments.status', $where['status']);
		}

		if (is_array($count))
		{
			$count['total'] = $this->db->count_all_results('comments');
		}
		else
		{
			$count = $this->db->count_all_results('comments');
		}

		$this->db->select('comments.*, posts.title as post_title, posts.slug as post_slug');
		$this->db->join('posts', 'comments.pid = posts.pid');

		$this->db->order_by('comments.created', 'desc');

		if (isset($where['status']) && in_array($where['status'], $this->_status, TRUE))
		{
			$this->db->where('comments.status', $where['status']);
		}

		if (isset($where['limit']) && $where['limit'] && is_numeric($where['limit']))
		{
			$this->db->limit(intval($where['limit']));
		}

		if (isset($where['offset']) && $where['offset'] && is_numeric($where['offset']))
		{
			$this->db->offset(intval($where['offset']));
		}

		$query = $this->db->get('comments');

		if ($query->num_rows() > 0) $comments = $query->result_array();

		$query->free_result();

		$this->db->flush_cache();

		if ($extra)
		{
			foreach ($comments as &$comment)
			{
				$this->get_extra($comment, $extra);
			}
		}

		return $comments;
	}

	/**
	 * 获取嵌套评论
	 *
	 * @access	public
	 * @param	integer	$cid	评论的CID
	 * @param	bool	$reset	是否重置静态变量
	 * @return	str				嵌套评论的HTML代码
	 */
	function get_threaded_comments($cid, $reset = FALSE)
	{
		static $str = '';
		static $floor = 1;

		if ($reset)
		{
			$str = '';
			$floor = 1;
		}

		if ($cid)
		{
			$query = $this->db->select('author, text, parent')
				->where('cid', $cid)
				->get('comments');

			if ($query->num_rows() > 0)
			{
				$comment = $query->row_array();

				$str .= '<div class="box">';

				$this->get_threaded_comments($comment['parent']);

				$str .= '<p class="name">'.$comment['author'].'</p><p class="time">'.$floor.'</p><p class="text">'.get_comment_content($comment['text']).'</p></div>';
				$floor ++;
			}
			$query->free_result();
		}

		return $str;		
	}

	/**
	 * 获取一个用户各种状态的评论的数量
	 *
	 * @access	public
	 * @param	integer	$owner_id	用户UID
	 * @return	array			以关联数组的形式返回
	 */
	function get_comments_count($owner_id)
	{
		$count = array();

		foreach (array('approved', 'waiting', 'spam') as $status)
		{
			if ($owner_id)
			{
				$this->db->where('ownerId', $owner_id);
			}

			$count[$status] = $this->db->where('status', $status)->count_all_results('comments');
		}

		return $count;
	}

	/**
	 * 获取最近通过的几条评论
	 *
	 * @access	public
	 * @param	integer	$limit	限制的行数
	 * @return	array			以关联数组的形式返回多个记录
	 */
	function get_recent_comments($limit)
	{
		$comments = array();

		$query = $this->db->select('comments.pid as pid, comments.text as text, cid, author, url, posts.slug as post_slug, posts.title as post_title')
			->join('posts', 'comments.pid = posts.pid')
			->where('comments.status', 'approved')
			->order_by('comments.created', 'desc')
			->limit($limit)
			->get('comments');

		if ($query->num_rows() > 0) $comments = $query->result_array();

		$query->free_result();

		foreach($comments as &$comment)
		{
			$this->get_extra($comment, array('authorLink', 'titleLink'));
		}

		return $comments;
	}

	// ---------------------------------- Create ----------------------------------

	/**
	 * 添加一条评论
	 *
	 * @access	public
	 * @param	array	$comment_data	评论数据
	 * @return	integer					返回新添加评论的CID
	 */
	function insert_comment($comment_data)
	{
		$cid = $this->db->insert('comments', $comment_data);

		if ($cid)
		{
			// 用户的评论数+1
			$CI =& get_instance();
			$CI->load->model('user_model');
			$CI->user_model->update_user_field($comment_data['ownerId'], 'commentsNum', '+');

			// 文章的评论数+1
			if ($comment_data['status'] == 'approved')
			{
				$CI->load->model('post_model');
				$CI->post_model->update_commentsNum($comment_data['pid'], '+');
			}
		}

		return $cid;
	}

	// ---------------------------------- Update ----------------------------------

	/**
	 * 更新一条评论的状态
	 *
	 * @access	public
	 * @param	integer	$cid	评论的CID
	 * @param	array	$status	目标状态('approved/waiting/spam')
	 * @return	integer			返回更新的行数
	 */
	function update_comment_status($cid, $status)
	{
		$comment = array();

		$query = $this->db->select('status, pid')
			->where('cid', $cid)
			->get('comments');

		if ($query->num_rows() > 0) $comment = $query->row_array();

		$query->free_result();

		if ($comment)
		{
			$this->db->update('comments', array('status' => $status), array('cid' => $cid));

			$updated = $this->db->affected_rows();

			if ($updated)
			{
				$CI =& get_instance();
				$CI->load->model('post_model');

				if ($status == 'approved' && $comment['status'] != 'approved')
				{
					$CI->post_model->update_commentsNum($comment['pid'], '+');
				}
				else if ($status != 'approved' && $comment['status'] == 'approved')
				{
					$CI->post_model->update_commentsNum($comment['pid'], '-');

					// 所有子评论的parent重置为0
					$this->reset_parent($cid);
				}
			}

			return $updated;
		}

		return FALSE;
	}

	/**
	 * 更改一个原父级评论的所有子评论的parent
	 *
	 * @access	public
	 * @param	integer	$parent_cid	评论的CID
	 * @return	integer				返回更新的行数
	 */	
	function reset_parent($parent_cid)
	{
		$this->db->update('comments', array('parent' => 0), array('parent' => $parent_cid));

		return $this->db->affected_rows();
	}

	// ---------------------------------- Delete ----------------------------------

	/**
	 * 删除一条评论
	 *
	 * @access	public
	 * @param	integer	$pid	评论的CID
	 * @return	integer			返回删除评论的行数
	 */
	function delete_comment($cid)
	{
		$comment = array();

		$query = $this->db->select('ownerId, pid, status')
			->where('cid', $cid)
			->get('comments');

		if ($query->num_rows() > 0) $comment = $query->row_array();

		if ($comment)
		{
			$this->db->delete('comments', array('cid' => $cid));

			$deleted = $this->db->affected_rows();

			if ($deleted)
			{
				// 用户的评论数-1
				$CI =& get_instance();
				$CI->load->model('user_model');
				$CI->user_model->update_user_field($comment['ownerId'], 'commentsNum', '-');

				// 如果为approved评论
				if ($comment['status'] == 'approved')
				{
					//文章的评论数-1
					$CI->load->model('post_model');
					$CI->post_model->update_commentsNum($comment['pid'], '-');

					// 所有子评论的parent重置为0
					$this->reset_parent($cid);
				}
			}

			return $deleted;
		}

		return FALSE;
	}

	/**
	 * 删除所有的垃圾评论
	 *
	 * @access	public
	 * @param	integer	$owner_id	用户的UID
	 * @return	integer				返回删除评论的行数
	 */
	function delete_spam_comments($owner_id)
	{
		if ($owner_id)
		{
			$this->db->where('ownerId', $owner_id);
		}

		$this->db->delete('comments', array('status' => 'spam'));

		$deleted = $this->db->affected_rows();

		if ($deleted)
		{
			// 更新用户的评论数
			$CI =& get_instance();
			$CI->load->model('user_model');
			$CI->user_model->refresh_user_commentsNum($owner_id);
		}

		return $deleted;
	}

	/**
	 * 删除一篇文章的所有评论
	 *
	 * @access	public
	 * @param	integer	$pid	文章的PID
	 * @return	integer			返回删除评论的行数
	 */
	function delete_comments_by_post($pid)
	{
		$post = array();

		$query = $this->db->select('uid')
			->where('pid', $pid)
			->get('posts');

		if ($query->num_rows() > 0) $post = $query->row_array();

		$query->free_result();

		if ($post)
		{
			$this->db->delete('comments', array('pid' => $pid));

			$deleted = $this->db->affected_rows();

			if ($deleted)
			{
				// 更新用户的评论数
				$CI =& get_instance();
				$CI->load->model('user_model');
				$CI->user_model->refresh_user_commentsNum($post['uid']);

				// 文章的评论数清零
				$this->db->update('posts', array('commentsNum' => 0), array('pid' => $pid));
			}

			return $deleted;
		}

		return FALSE;
	}

	/**
	 * 删除一个用户的所有评论
	 *
	 * @access	public
	 * @param	integer	$owner_id	用户的UID
	 * @return	integer				返回删除评论的行数
	 */
	function delete_comments_by_user($owner_id)
	{
		// 删除评论
		$this->db->delete('comments', array('ownerId' => $owner_id));

		$deleted = $this->db->affected_rows();

		if ($deleted)
		{
			// 用户的评论数清零
			$this->db->update('users', array('commentsNum' => 0), array('uid' => $owner_id));

			// 用户所有文章的评论数清零
			$posts = $this->db->select('pid')
				->where('uid', $owner_id)
				->get('posts')
				->result_array();

			foreach ($posts as $post)
			{
				$this->db->update('posts', array('commentsNum' => 0), array('pid' => $post['pid']));
			}
		}

		return $deleted;
	}
}

/* End of file comment_model.php */
/* Location: ./application/models/comment_model.php */