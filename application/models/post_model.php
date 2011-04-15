<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_model extends CI_Model
{
	private $_post_type = array('post', 'attachment', 'page');

	private $_post_status = array('publish', 'draft', 'waiting');

	private $_meta_type = array('category', 'tag');

	function __construct()
	{
		parent::__construct();
	}

	// --------------------------------- Retrieve ---------------------------------

	/**
	 * 获取一篇文章/页面 
	 *
	 * @access	public
	 * @param	string	$key	索引列
	 * @param	string	$value	索引值
	 * @return	array			以关联数组的形式返回一个记录
	 */
	function get_post($key, $value, $extra = array())
	{
		$post = array();

		if (in_array($key, array('pid', 'slug'), TRUE) && $value)
		{
			$query = $this->db->select('posts.*, users.screenName as screenName, users.name as username')
				->join('users', 'posts.uid = users.uid')
				->where($key, $value)
				->limit(1)
				->get('posts');

			if ($query->num_rows() > 0)
			{
				$post = $query->row_array();

				if ($extra)
				{
					$this->get_extra($post, $extra);
				}
			}

			$query->free_result();
		}

		return $post;
	}

	/**
	 * 获取一篇文章的其他属性
	 *
	 * @access	public
	 * @param	array	$post	文章
	 * @param	array	$extra	属性列表
	 * @return	void
	 */
	function get_extra(&$post, $extra)
	{
		// 发布时间
		if (in_array('published', $extra))
		{
			$post_date_format = settings('post_date_format') ? 
				settings('post_date_format') : 'Y-m-d H:i';
			$post['published'] = date($post_date_format, $post['created']);
		}

		// 文章链接
		if (in_array('permalink', $extra))
		{
			$post['permalink'] = site_url('post/'.$post['slug']);
		}

		// 浏览链接
		if (in_array('viewLink', $extra))
		{
			$post['viewLink'] = ($post['status'] == 'publish') ? anchor(site_url('post/'.$post['slug']), '<img src="'.base_url().'/assets/images/view.gif"', 'class="hidden"') : '&nbsp;';
		}

		// 文章标题
		if (in_array('titleLink', $extra))
		{
			$post['titleLink'] = anchor(site_url('post/'.$post['slug']), $post['title']);
		}

		// 作者链接
		if (in_array('authorLink', $extra))
		{
			$post['authorLink'] = anchor(site_url('author/'.$post['username']), $post['screenName']);
		}

		// 作者的所有文章(后)
		if (in_array('auhtorManageLink', $extra))
		{
			$post['authorManageLink'] = anchor(site_url('admin/posts/manage/post/publish/'.$post['uid']), mb_word_limiter($post['screenName'], 6));
		}

		// 评论链接
		if (in_array('commentLink', $extra))
		{
			$post['commentLink'] = anchor(site_url('post/'.$post['slug'].'#comments'), $post['commentsNum'].' 条评论', 'title="'.$post['title'].' 上的评论"');
		}

		// 编辑链接
		if (in_array('writeLink', $extra))
		{
			$post['writeLink'] = site_url('admin/posts/write/'.$post['type'].'/'.$post['pid']);
		}

		// 评论管理链接
		if (in_array('commentsManageLink', $extra))
		{
			$post['commentsManageLink'] = site_url('admin/comments/manage?reset=1&uid='.$post['uid'].'&pid='.$post['pid']);
		}

		// 文章
		if ($post['type'] == 'post')
		{
			// 正文
			if (in_array('content', $extra))
			{
				$post['content'] = get_post_content($post['text']);
			}

			// 摘要
			if (in_array('excerpt', $extra))
			{
				$post['excerpt'] = get_post_excerpt($post['text']);

				if (has_break($post['text']))
				{
					$post['excerpt'] .= '<p>' . anchor(site_url('post/'.$post['slug']), '&#187 阅读全文') . '</p>';
				}
			}

			// 分类及标签
			if (in_array('metas', $extra))
			{
				$post_categories_str = array();
				$post_category_ids = array();
				$post_categories = array();
				$post_tags_str = array();
				$post_tags = array();

				$metas_query = $this->db->select('post_metas.*, metas.*')
					->join('metas', 'post_metas.mid = metas.mid')
					->where('pid', $post['pid'])
					->get('post_metas');

				if ($metas_query->num_rows() > 0)
				{
					foreach ($metas_query->result_array() as $meta)
					{
						if ($meta['type'] == 'category')
						{
							$post_categories_str[] = $meta['name'];
							$post_category_ids[] = $meta['mid'];
							$post_categories[] = anchor(site_url('category/'.$meta['slug']), $meta['name']);
						}
						else
						{
							$post_tags_str[] = $meta['name'];
							$post_tags[] = anchor(site_url('tag/'.$meta['slug']), $meta['name']);
						}
					}
				}

				$metas_query->free_result();

				$post['categories'] = implode(', ', $post_categories);
				$post['tags'] = implode(', ', $post_tags);
				$post['categories_str'] = implode(', ', $post_categories_str);
				$post['category_ids']= $post_category_ids;
				$post['tags_str'] = implode(', ', $post_tags_str);
			}

			// 左右邻居文章
			if (in_array('prevLink', $extra) || in_array('nextLink', $extra))
			{
				$adjacent_posts = $this->get_adjacent_posts($post['pid']);

				$post['prevLink'] = ($adjacent_posts['prev'])?anchor(site_url('post/'.$adjacent_posts['prev']['slug']), $adjacent_posts['prev']['title']):'';

				$post['nextLink'] = ($adjacent_posts['next'])?anchor(site_url('post/'.$adjacent_posts['next']['slug']), $adjacent_posts['next']['title']):'';
			}
		}
		else if ($post['type'] == 'page')
		{
			// 正文
			if (in_array('content', $extra))
			{
				$post['content'] = $post['text'];
			}

			// 导航
			if (in_array('navigationLink', $extra))
			{
				$post['navigationLink'] = '&nbsp;';

				$navigations_query = $this->db->select('nid, title')
					->where('page', $post['pid'])
					->limit(1)
					->get('navigations');

				if ($navigations_query->num_rows() > 0)
				{
					$navigation = $navigations_query->row_array();

					$post['navigationLink'] = anchor(site_url('admin/navigations/manage/'.$navigation['nid']), mb_word_limiter($navigation['title']));
				}

				$navigations_query->free_result();
			}
		}
	}

	/**
	 * 根据条件数组 获取多篇文章/页面
	 *
	 * @access	public
	 * @param	array	$where	条件数组
	 * @param	integer	&$count	结果行数
	 * @return	array			以关联数组的形式返回多个记录
	 */
	function get_posts($where, &$count = '', $extra = array())
	{
		$posts = array();

		if (!isset($where['type'])) $where['type'] = 'post';
		if (!isset($where['status'])) $where['status'] = 'publish';

		$this->db->start_cache();

		if (isset($where['meta_type']) && $where['meta_type'] && in_array($where['meta_type'], $this->_meta_type, TRUE) && isset($where['meta_slug']) && $where['meta_slug'])
		{
			$this->db->join('post_metas', 'posts.pid = post_metas.pid');
			$this->db->join('metas', 'post_metas.mid = metas.mid');
			$this->db->where('metas.type', $where['meta_type']);
			$this->db->where('metas.slug', $where['meta_slug']);
		}
		
		if (isset($where['type']) && $where['type'] && in_array($where['type'], $this->_post_type, TRUE))
		{
			$this->db->where('posts.type', $where['type']);
		}
		
		if (isset($where['uid']) && $where['uid'] && is_numeric($where['uid']))
		{
			$this->db->where('posts.uid', intval($where['uid']));
		}

		if (isset($where['from']) && $where['from'] && is_numeric($where['from']))
		{
			$this->db->where('posts.created >= ', intval($where['from']));
		}

		if (isset($where['to']) && $where['to'] && is_numeric($where['to']))
		{
			$this->db->where('posts.created <=', intval($where['to']));
		}
		
		if(isset($where['keywords']) && $where['keywords'])
		{
			$this->db->like('posts.title', $where['keywords']);
		}
		
		if(isset($where['feed_filter']) && $where['feed_filter'])
		{
			$this->db->where('allowFeed', 1);
		}

		$this->db->stop_cache();

		if (is_array($count))
		{
			$count['publish'] = $this->db->where('status', 'publish')->count_all_results('posts');
			$count['draft'] = $this->db->where('status', 'draft')->count_all_results('posts');
			$count['waiting'] = $this->db->where('status', 'waiting')->count_all_results('posts');
		}

		if (isset($where['status']) && $where['status'] && in_array($where['status'], $this->_post_status, TRUE))
		{
			$this->db->where('posts.status', $where['status']);
		}
		if (is_array($count))
		{
			$count['total'] = $this->db->count_all_results('posts');
		}
		else
		{
			$count = $this->db->count_all_results('posts');
		}

		$this->db->select('posts.*, users.screenName as screenName, users.name as username');
		$this->db->join('users', 'users.uid = posts.uid');
		
		$this->db->order_by('posts.created', 'DESC');

		if (isset($where['status']) && $where['status'] && in_array($where['status'], $this->_post_status, TRUE))
		{
			$this->db->where('posts.status', $where['status']);
		}
		
		if(isset($where['limit']) && $where['limit'] && is_numeric($where['limit']))
		{
			$this->db->limit(intval($where['limit']));
		}
		
		if(isset($where['offset']) && is_numeric($where['offset']))
		{
			$this->db->offset(intval($where['offset']));
		}

		$query = $this->db->get('posts');

		if ($query->num_rows() > 0)
		{
			$posts = $query->result_array();

		}
		$query->free_result();

		$this->db->flush_cache();

		if ($extra)
		{
			foreach ($posts as &$post)
			{
				$this->get_extra($post, $extra);
			}
		}

		return $posts;
	}

	/**
	 * 根据时间条件数组 获取多篇文章/页面
	 *
	 * @access	public
	 * @param	array	$where	条件数组
	 * @param	integer	&count	结果行数
	 * @return	array			以关联数组的形式返回多个记录
	 */
	function get_archives($where, &$count = '', $extra = array())
	{
		extract($where);

		if (empty($year) && empty($month) && empty($day)) exit();
		
		if (!empty($year) && !empty($month) && !empty($day)) 
		{
			$from = mktime(0, 0, 0, $month, $day, $year);
			$to = mktime(23, 59, 59, $month, $day, $year);
		}
		else if (!empty($year) && !empty($month)) 
		{
			$from = mktime(0, 0, 0, $month, 1, $year);
			$to = mktime(23, 59, 59, $month, date('t', $from), $year);
		}
		else if (!empty($year)) 
		{
			$from = mktime(0, 0, 0, 1, 1, $year);
			$to = mktime(23, 59, 59, 12, 31, $year);
		}

		$_where = array('from' => $from, 'to' => $to, 'limit' => $limit, 'offset'=>$offset);
		return $this->get_posts($_where, $count, $extra);
	}

	/**
	 * 获取各个月的文章的数量
	 *
	 * @access	public
	 * @return	array			以关联数组的形式返回
	 */
	function get_archive_count()
	{
		$archives = array();

		$query = $this->db->select('created')
			->where('type', 'post')
			->where('status', 'publish')
			->get('posts');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $post)
			{
				$month = date('Ym', $post['created']);

				if (isset($archives[$month]))
				{
					$archives[$month] ++;
				}
				else
				{
					$archives[$month] = 1;
				}
			}
		}

		$query->free_result();

		$archiveLinks = array();

		foreach ($archives as $month => $count)
		{
			$archivelinks[] =  anchor(site_url('archive/'.substr($month, 0, 4).'/'.substr($month, 4, 2)), substr($month, 0, 4) . ' 年 '.substr($month, 4, 2).' 月') . ' ('.$count.')';
		}

		return $archivelinks;
	}

	/**
	 * 获取最近发布的几篇文章
	 *
	 * @access	public
	 * @param	integer	$limit	限制的行数
	 * @return	array			以关联数组的形式返回多个记录
	 */
	function get_recent_posts($limit)
	{
		$posts = array();

		$query = $this->db->select('slug, title, type')
			->where('type', 'post')
			->where('status', 'publish')
			->order_by('created', 'DESC')
			->limit($limit)
			->get('posts');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$posts[] = $row;
			}
		}

		$query->free_result();

		foreach ($posts as &$post)
		{
			$this->get_extra($post, array('titleLink'));
		}

		return $posts;
	}

	/**
	 * 获取某篇文章的前后文章
	 *
	 * @access	public
	 * @param	integer	$pid	文章PID
	 * @return	array			以关联数组的形式返回两个记录
	 */
	function get_adjacent_posts($pid)
	{
		$posts = array(
			'prev'	=> array(),
			'next'	=> array()
		);

		$query = $this->db->select('slug, title')
			->where('type', 'post')
			->where('status', 'publish')
			->where('created > (select created from posts where pid = '.intval($pid).')')
			->order_by('created')
			->limit(1)
			->get('posts');

		if ($query->num_rows() > 0) $posts['prev'] = $query->row_array();

		$query->free_result();
			
		$query = $this->db->select('slug, title')
			->where('type', 'post')
			->where('status', 'publish')
			->where('created < (select created from posts where pid = '.intval($pid).')')
			->order_by('created', 'DESC')
			->limit(1)
			->get('posts');

		if ($query->num_rows() > 0) $posts['next'] = $query->row_array();

		$query->free_result();

		return $posts;
	}

	/**
	 * 生成文章的缩略名
	 *
	 * @access	public
	 * @param	string	$slug	输入的缩略名
	 * @param	integer	$pid	文章的PID
	 * @return	integer			返回合法的缩略名
	 */
	function get_slug_name($slug, $pid)
	{
		$result = $slug;
		$index = 1;

		while ($this->db->where('slug', $result)->where('pid <>', $pid)->count_all_results('posts'))
		{
			$result = $slug . '_' . $index;
			$index ++;
		}

		return $result;
	}

	// ---------------------------------- Create ----------------------------------

	/**
	 * 发送trackback
	 *
	 * @access	public
	 * @param	integer	$pid		文章PID
	 * @param	array	$trackbacks	Trackback数组
	 * @return	void
	 */
	function send_trackbacks($pid, $trackbacks)
	{
		$post = $this->get_post('pid', $pid, array('permalink'));

		if ($post)
		{
			$CI =& get_instance();
			$CI->load->library('trackback');

			foreach ($trackbacks as $trackback)
			{
				if (empty($trackback)) continue;
				
				$tb_data = array(
					'ping_url'	=> $trackback,
					'url'		=> $post['permalink'],
					'title'		=> $post['title'],
					'excerpt'	=> $post['text'],
					'blog_name'	=> settings('blog_title'),
					'charset'	=> 'utf-8'
				);

				if (!$CI->trackback->send($tb_data))
				{
					log_message('error', $this->trackback->display_errors());
				}
			}
		}

		return;
	}

	/**
	 * 添加一篇文章
	 *
	 * @access	public
	 * @param	array	$post_data	文章数据
	 * @return	integer				返回新添加文章的PID
	 */
	function insert_post($post_data)
	{
		$data = array();

		foreach (array('type', 'uid', 'status', 'title', 'text', 'css', 'js', 'allowComment', 'allowPing', 'allowFeed') as $item)
		{
			$data[$item] = $post_data[$item];
		}

		$data['created'] = strtotime($post_data['created']);
		$data['modified'] = $data['created'];

		$this->db->insert('posts', $data);

		$pid = $this->db->affected_rows() ? $this->db->insert_id() : 0;

		if ($pid)
		{
			// 更新文章和页面的缩略名
			$this->update_post_slug($pid, $post_data['slug']);

			if ($post_data['type'] == 'post')
			{
				// 用户的文章数+1
				$CI =& get_instance();
				$CI->load->model('user_model');
				$CI->user_model->update_user_field($post_data['uid'], 'postsNum', '+');

				// 添加文章的关系
				$CI->load->model('meta_model');
				$CI->meta_model->insert_post_categories($pid, $post_data['category']);
				$CI->meta_model->insert_post_tags($pid, $post_data['tags']);
	
				// 发送trackbacks
				$trackbacks = array_unique(preg_split("/(\r|\n|\r\n)/", trim($post_data['trackback'])));
				if ($trackbacks) $this->send_trackbacks($pid, $trackbacks);
			}
		}

		return $pid;
	}

	// ---------------------------------- Update ----------------------------------

	/**
	 * 更新一篇文章
	 *
	 * @access	public
	 * @param	integer	$pid		文章的PID
	 * @param	array	$post_data	文章数据
	 * @return	integer				返回更新的行数
	 */
	function update_post($pid, $post_data)
	{
		$data = array();

		foreach (array('type', 'uid', 'status', 'title', 'text', 'css', 'js', 'allowComment', 'allowPing', 'allowFeed') as $item)
		{
			$data[$item] = $post_data[$item];
		}

		$data['created'] = strtotime($post_data['created']);
		$data['modified'] = time();

		$this->db->update('posts', $data, array('pid' => intval($pid)));

		$affected_rows = $this->db->affected_rows();

		if ($affected_rows)
		{
			// 更新文章和页面的缩略名
			$this->update_post_slug($pid, $post_data['slug']);

			if ($post_data['type'] == 'post')
			{
				// 添加文章的关系
				$CI =& get_instance();
				$CI->load->model('meta_model');
				$CI->meta_model->delete_post_meta_by_post($pid);
				$CI->meta_model->insert_post_categories($pid, $post_data['category']);
				$CI->meta_model->insert_post_tags($pid, $post_data['tags']);
				$CI->meta_model->delete_orphaned_tags();
	
				// 发送trackbacks

				$trackbacks = array_unique(preg_split("/(\r|\n|\r\n)/", trim($post_data['trackback'])));

				if ($trackbacks) $this->send_trackbacks($pid, $trackbacks);
			}
		}

		return $affected_rows;
	}
	
	/**
	 * 更新文章和页面的状态
	 *
	 * @access	public
	 * @param	integer	$pid		文章的PID
	 * @param	string	$status		目标状态('publish/draft/waiting')
	 * @return	integer				返回更新的行数
	 */
	function update_post_status($pid, $status)
	{
		$post = array();

		$query = $this->db->select('status')
			->where('pid', $pid)
			->get('posts');

		if ($query->num_rows() > 0) $post = $query->row_array();

		$query->free_result();

		if ($post)
		{
			$this->db->update('posts', array('status' => $status), array('pid' => $pid));

			$updated = $this->db->affected_rows();

			if ($updated)
			{
				if ($post['type'] == 'post')
				{
					$CI =& get_instance();
					$CI->load->model('meta_model');

					if ($status == 'publish' && $post['status'] != 'publish')
					{
						$CI->meta_model->update_count_by_post($post['pid'], '+');
					}
					else if($status != 'publish' && $post['status'] == 'publish')
					{
						$CI->meta_model->update_count_by_post($post['pid'], '-');
					}
				}
			}

			return $updated;
		}

		return FALSE;
	}

	/**
	 * 更新文章和页面的评论数
	 *
	 * @access	public
	 * @param	integer	$pid	文章的PID
	 * @param	string	$type	类型('+'或'-')
	 * @return	integer			返回更新的行数
	 */
	function update_commentsNum($pid, $type)
	{
		$this->db->set('commentsNum', 'commentsNum' . $type . '1', FALSE)
			->where('pid', $pid)
			->update('posts');

		return $this->db->affected_rows();
	}
	
	/**
	 * 更新文章的缩略名
	 *
	 * @access	public
	 * @param	integer	$pid	文章的PID
	 * @param	string	$slug	缩略名
	 * @return	integer			返回更新的行数
	 */
	function update_post_slug($pid, $slug)
	{
		$slug = repair_slugName($slug, $pid);

		$slug = $this->get_slug_name($slug, $pid);

		$this->db->update('posts', array('slug' => $slug), array('pid' => $pid));

		return $this->db->affected_rows();
	}

	// ---------------------------------- Delete ----------------------------------

	/**
	 * 删除一篇文章/页面
	 *
	 * @access	public
	 * @param	integer	$pid	文章的PID
	 * @return	integer			返回删除文章/页面的行数
	 */
	function delete_post($pid)
	{
		$post = array();

		$query = $this->db->select('uid')
			->where('pid', $pid)
			->get('posts');

		if ($query->num_rows() > 0) $post = $query->row_array();

		$query->free_result();

		if ($post)
		{
			// 删除文章
			$this->db->delete('posts', array('pid' => intval($pid)));
			$deleted = $this->db->affected_rows();

			if ($deleted)
			{
				// 删除文章的所有评论
				$CI =& get_instance();
				$CI->load->model('comment_model');
				$CI->comment_model->delete_comments_by_post($pid);

				// 删除文章的所有关系和多余的META
				$CI->load->model('meta_model');
				$CI->meta_model->delete_post_meta_by_post($pid);
				$CI->meta_model->delete_orphaned_tags();
				

				// 用户的文章数-1
				$CI->load->model('user_model');
				$CI->user_model->update_user_field($comment['ownerId'], 'postsNum', '-');
			}

			return $deleted;
		}

		return FALSE;
	}

	/**
	 * 删除多篇文章/页面
	 *
	 * @access	public
	 * @param	array	$pids	文章PID的数组
	 * @return	integer			返回删除文章/页面的行数
	 */
	function delete_posts($pids)
	{
		$deleted = 0;

		if ($pids && is_array($pids))
		{
			foreach ($pids as $pid)
			{
				$deleted += $this->delete_post($pid);
			}
		}

		return $deleted;
	}

	/**
	 * 根据用户ID 删除一个用户的所有文章/页面
	 *
	 * @access	public
	 * @param	integer	$uid	用户的UID
	 * @return	integer			返回删除文章/页面的行数
	 */
	function delete_posts_by_user($uid)
	{
		// 删除文章和页面
		$this->db->delete('posts', array('uid' => $uid));

		$deleted = $this->db->affected_rows();

		if ($deleted)
		{
			// 删除用户的所有评论
			$CI =& get_instance();
			$CI->load->model('comment_model');
			$CI->comment_model->delete_comments_by_user($uid);

			// 删除文章的所有关系
			$CI->load->model('meta_model');
			$this->meta_model->delete_post_meta_by_user($uid);
			$CI->meta_model->delete_orphaned_tags();

			// 用户的文章数清零
			$this->db->update('users', array('postsNum' => 0), array('uid' => uid));
		}

		return $deleted;
	}
}

/* End of file post_model.php */
/* Location: ./application/models/post_model.php */