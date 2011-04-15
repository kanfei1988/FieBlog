<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meta_model extends CI_Model
{
	private $_meta_type = array('category', 'tag');

	private $_meta_keys = array('mid', 'name', 'slug');

	function __construct()
	{
		parent::__construct();
	}

	// --------------------------------- Retrieve ---------------------------------

	/**
	 * 获取一个META
	 *
	 * @access	public
	 * @param	string	$type	类型('category/tag')
	 * @param	string	$key	索引列
	 * @param	string	$value	索引值
	 * @return	array			以关联数组的形式返回一个记录
	 */
	function get_meta($type = 'category', $key, $value, $extra = array())
	{
		$meta = array();

		if ($type && in_array($type, $this->_meta_type, TRUE))
		{
			$this->db->where('type', $type);
		}

		if ($key && in_array($key, $this->_meta_keys, TRUE))
		{
			$this->db->where($key, $value);
		}

		$query = $this->db->get('metas');

		if ($query->num_rows() > 0) $meta = $query->row_array();

		$query->free_result();

		if ($extra) $this->get_extra($meta, $extra);

		return $meta;
	}

	/**
	 * 获取一个META的其他属性
	 *
	 * @access	public
	 * @param	array	$meta	META
	 * @param	array	$extra	属性列表
	 * @return	void
	 */
	function get_extra(&$meta, $extra)
	{
		if (in_array('permalink', $extra))
		{
			$meta['permalink'] = site_url('category/'.$meta['slug']);
		}

		if (in_array('titleLink', $extra))
		{
			if ($meta['type'] == 'category')
			{
				$meta['titleLink'] = anchor(site_url('category/'.$meta['slug']), $meta['name'], 'title="查看'.$meta['name'].' 下的所有文章"');
			}
			else if ($meta['type'] == 'tag')
			{
				$meta['titleLink'] = anchor(site_url('tag/'.$meta['slug']), $meta['name'], 'title="'.$meta['count'].'个话题"');
			}
		}

		// 文章链接
		if (in_array('postsLink', $extra))
		{
			$meta['postsLink'] = anchor(site_url('admin/posts/manage?reset=1&meta_type='.$meta['type'].'&meta_slug='.$meta['slug']), $meta['count'], 'class="balloon-button"');
		}

		// 浏览链接
		if (in_array('viewLink', $extra))
		{
			$meta['viewLink'] = anchor(site_url('category/'.$meta['slug']), '<img src= "'.base_url().'/assets/images/view.gif"', 'class="hidden"');
		}

		// 管理链接
		if (in_array('manageLink', $extra))
		{
			$meta['manageLink'] = anchor(site_url('admin/metas/manage/'.$meta['type'].'/'.$meta['mid']), mb_word_limiter($meta['name'], 15));
		}
	}

	/**
	 * 获取所有的META(按count列的降序排列)
	 *
	 * @access	public
	 * @param	string	$type	类型('category/tag')
	 * @return	array			以关联数组的形式返回多个记录
	 */
	function get_metas($type = 'category', $extra =array())
	{
		$metas = array();

		if ($type && in_array($type, $this->_meta_type, TRUE))
		{
			$this->db->where('type', $type);
		}

		$query = $this->db->order_by('order', 'asc')
			->order_by('count', 'desc')
			->get('metas');

		if ($query->num_rows() > 0) $metas = $query->result_array();

		$query->free_result();

		if ($extra)
		{
			foreach ($metas as &$meta)
			{
				$this->get_extra($meta, $extra);
			}
		}

		return $metas;
	}

	/**
	 * 获取某个META的PID数组
	 *
	 * @access	public
	 * @param	integer	$mid	META的MID
	 * @return	array			以索引数组的形式返回
	 */
	function get_meta_pids($mid)
	{
		$pids = array();

		$query = $this->db->select('pid')
			->where('mid', $mid)
			->get('post_metas');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$pids[] = $row->pid;
			}
		}

		$query->free_result();

		return $pids;
	}

	/**
	 * 获取一篇文章的Category数组和Tag数组
	 *
	 * @access	public
	 * @param	integer	$pid	文章的PID
	 * @return	array			以关联数组的形式返回
	 */
	function get_post_metas($pid)
	{
		$metas = array();

		$query = $this->db->select('metas.*')
			->join('metas', 'post_metas.mid = metas.mid')
			->where('post_metas.pid', intval($pid))
			->get('post_metas');
		
		if ($query->num_rows() > 0)
		{
			foreach ($this->_meta_type as $type)
			{
				$metas[$type] = array();
			}

			foreach ($query->result_array() as $row)
			{
				foreach ($this->_meta_type as $type)
				{
					$metas[$type][] = $row;
				}
			}
		}

		$query->free_result();

		return $metas;
	}

	/**
	 * 获取所有META的文章数量
	 *
	 * @access	public
	 * @return	array	以关联数组的形式返回
	 */
	function get_metas_count()
	{
		return array(
			'category'	=> $this->db->where('type', 'category')->count_all_results('metas'),
			'tag'		=> $this->db->where('type', 'tag')->count_all_results('metas')
		);
	}

	/**
	 * 检查一个META是否存在
	 *
	 * @access	public
	 * @param	string	$type			类型('category/tag')
	 * @param	string	$key			索引列
	 * @param	string	$value			索引值
	 * @param	string	$exclude_mid	需要忽略的MID
	 * @return	bool					META是否存在
	 */
	function check_meta_exist($type, $key, $value, $exclude_mid = NULL)
	{
		if (in_array($type, $this->_meta_type, TRUE))
		{
			$this->db->where('type', $type);
		}
		if (in_array($key, $this->_meta_keys, TRUE))
		{
			$this->db->where($key, $value);
		}
		if ($exclude_mid && is_numeric($exclude_mid))
		{
			$this->db->where('mid <>', $exclude_mid);
		}

		return ($this->db->count_all_results('metas') > 0);
	}

	/**
	 * 根据Tags的名称数组获取MID数组 不存在则添加
	 *
	 * @access	public
	 * @param	array	$input_tags	Tags的名称数组
	 * @return	array				Tags的MID数组
	 */
	function scan_tags($input_tags)
	{
		$tags = is_array($input_tags) ? $input_tags : array($input_tags);
		$mids = array();

		foreach ($tags as $tag)
		{
			if (empty($tag))
			{
				continue;
			}

			$query = $this->db->where('type', 'tag')
				->where('name', $tag)
				->get('metas');

			if ($query->num_rows() > 0)
			{
				$mids[] = $query->row()->mid;
			}
			else
			{
				$mids[] = $this->insert_meta(array('type' => 'tag', 'name' => $tag));
			}

			$query->free_result();
		}

		return is_array($input_tags) ? $mids : current($mids);
	}

	// ---------------------------------- Create ----------------------------------

	/**
	 * 添加一个META
	 *
	 * @access	public
	 * @param	array	$meta_data	META数据
	 * @return	integer				返回新添加META的MID
	 */
	function insert_meta($meta_data)
	{
		if (empty($meta_data['slug'])) $meta_data['slug'] = $meta_data['name'];

		$meta_data['slug'] = repair_slugName($meta_data['slug']);

		if (empty($meta_data['description'])) $meta_data['description'] = '';

		$this->db->insert('metas', $meta_data);

		return ($this->db->affected_rows()) ? $this->db->insert_id() : FALSE;
	}

	/**
	 * 添加一个PID和MID的关系
	 *
	 * @access	public
	 * @param	integer	$pid	文章的PID
	 * @param	integer	$mid	文章的MID
	 * @return	integer			返回影响的行数
	 */
	function insert_post_meta($pid, $mid)
	{
		$query = $this->db->select('status')
			->where('pid', $pid)
			->get('posts');

		if ($query->num_rows() > 0) $post = $query->row_array();

		$query->free_result();

		if ($post)
		{
			$this->db->insert('post_metas', array('pid' => $pid, 'mid' => $mid));

			$inserted = $this->db->affected_rows();

			if ($inserted && $post['status'] == 'publish')
			{
				$this->update_count($mid, '+');
			}

			return $inserted;
		}

		return FALSE;
	}

	/**
	 * 添加一篇文章的分类关系
	 *
	 * @access	public
	 * @param	integer	$pid		文章的PID
	 * @param	array	$categories	分类的MID数组
	 * @return	integer				返回影响的行数
	 */
	function insert_post_categories($pid, $categories)
	{
		$inserted = 0;

		if ($categories)
		{
			foreach ($categories as $mid)
			{
				$inserted += $this->insert_post_meta($pid, $mid);
			}
		}

		return $inserted;
	}

	/**
	 * 添加一篇文章的标签关系
	 *
	 * @access	public
	 * @param	integer	$pid		文章的PID
	 * @param	string	$tags		标签字符串
	 * @return	integer				返回影响的行数
	 */
	function insert_post_tags($pid, $tags)
	{
		// 把全角逗号转为半角逗号
		$tags = str_replace('，', ',', $tags);

		// 把tags字符串转为tags的name数组
		$tags = array_unique(array_map('trim', explode(',', $tags)));

		// 把name数组转为ID数组 不存在的tag则要添加
		$tag_ids = $this->scan_tags($tags);

		$inserted = 0;

		if ($tag_ids)
		{
			foreach ($tag_ids as $mid)
			{
				$inserted += $this->insert_post_meta($pid, $mid);
			}
		}

		return $inserted;
	}

	// ---------------------------------- Update ----------------------------------

	/**
	 * 更新一个META
	 *
	 * @access	public
	 * @param	integer	$mid		META的MID
	 * @param	array	$meta_data	META数据
	 * @return	integer				返回更新的行数
	 */
	function update_meta($mid, $meta_data)
	{
		if (empty($meta_data['slug'])) $meta_data['slug'] = $meta_data['name'];

		$meta_data['slug'] = repair_slugName($meta_data['slug']);

		if (empty($meta_data['description'])) $meta_data['description'] = '';

		$this->db->update('metas', $meta_data, array('mid' => intval($mid)));

		return $this->db->affected_rows();
	}

	/**
	 * 更新一个META的Publish文章数
	 *
	 * @access	public
	 * @param	integer	$mid	META的MID
	 * @param	string	$type	类型('+'或'-')
	 * @return	integer			返回更新的行数
	 */
	function update_count($mid, $type)
	{
		$this->db->set('count', 'count' . $type . ' 1', FALSE)
			->where('mid', $mid)
			->update('metas');

		return $this->db->affected_rows();
	}

	/**
	 * 更新一篇文章的所有META的Publish文章数
	 *
	 * @access	public
	 * @param	integer	$mid	META的MID
	 * @param	string	$type	类型('+'或'-')
	 * @return	integer			返回更新的行数
	 */
	function update_count_by_post($pid, $type)
	{
		$query = $this->db->select('mid')
			->where('pid', $pid)
			->get('post_metas');

		$updated = 0;

		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $meta)
			{
				$updated += $this->update_count($meta['mid'], $type);
			}
		}

		$query->free_result();

		return $updated;
	}

	/**
	 * 刷新所有META的Publish文章数
	 *
	 * @access	public
	 * @return	void
	 */
	function refresh_metas_count()
	{
		$query = $this->db->select('mid')->get('metas');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $meta)
			{
				$count = 0;

				$post_metas_query = $this->db->select('pid')
					->where('mid', $meta['mid'])
					->get('post_metas');

				if ($post_metas_query->num_rows() > 0)
				{
					foreach ($post_metas_query->result_array() as $post_meta)
					{
						$posts_query = $this->db->select('status')
							->where('pid', $post_meta['pid'])
							->get('posts');

						if ($posts_query->num_rows() > 0)
						{
							$post = $posts_query->row_array();

							if ($post['status'] == 'publish') $count++;
						}

						$posts_query->free_result();
					}
				}

				$post_metas_query->free_result();

				$this->db->where('mid', $meta['mid'])->update('metas', array('count' => $count));
			}
		}

		$query->free_result();

		return;
	}

	/**
	 * 将多个META合并到一个META
	 *
	 * @access	public
	 * @param	integer	$to_mid	目标MID
	 * @param	string	$mids	要合并的META的MID数组
	 * @return	integer			返回新添加关系的行数
	 */
	function merge_metas($to_mid, $mids = array())
	{
		// 目标meta的pids
		$old_pids = $this->get_meta_pids($to_mid);

		$merged = 0;

		if ($mids && is_array($mids))
		{
			foreach ($mids as $mid)
			{
				if ($mid !== $to_mid)
				{
					// 要删除meta的pids
					$new_pids = $this->get_meta_pids($mid);

					// 删除关系和META
					$this->delete_meta($mid); 

					// 没有加上关系的pids
					$diff_ids = array_diff($new_pids, $old_pids);

					if (count($diff_ids) > 0)
					{
						foreach ($diff_ids as $diff_id)
						{
							$merged += $this->insert_post_meta($diff_id, $to_mid);
						}
					}
				}
			}
		}

		return $merged;
	}

	// ---------------------------------- Delete ----------------------------------

	/**
	 * 删除一个META
	 *
	 * @access	public
	 * @param	integer	$mid	META的MID
	 * @return	integer			返回删除META的行数
	 */
	function delete_meta($mid)
	{
		// 删除关系
		$this->db->delete('post_metas', array('mid' => intval($mid)));

		// 删除该meta
		$this->db->delete('metas', array('mid' => intval($mid))); 

		return $this->db->affected_rows();
	}

	/**
	 * 删除多个META
	 *
	 * @access	public
	 * @param	array	$mids	META的MID数组
	 * @return	integer			返回META的行数
	 */
	function delete_metas($mids)
	{
		$deleted = 0;

		if ($mids && is_array($mids))
		{
			foreach ($mids as $mid)
			{
				$deleted += $this->delete_meta($mid);
			}
		}

		return $deleted;
	}

	/**
	 * 删除多余的TAG
	 *
	 * @access	public
	 * @return	integer		返回删除TAG的行数
	 */
	function delete_orphaned_tags()
	{
		$query = $this->db->select('metas.mid')
			->join('post_metas', 'metas.mid = post_metas.mid', 'left')
			->where('metas.type', 'tag')
			->where('post_metas.mid is NULL')
			->get('metas');

		$deleted = 0;

		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$deleted += $this->delete_meta($row['mid']);
			}
		}

		$query->free_result();

		return $deleted;
	}

	/**
	 * 删除一个PID和MID的关系
	 *
	 * @access	public
	 * @param	integer	$pid	文章的PID
	 * @param	integer	$mid	文章的MID
	 * @return	integer			返回影响的行数
	 */
	function delete_post_meta($pid, $mid)
	{
		$post = array();

		$query = $this->db->select('status')->where('pid', $pid)->get('posts');

		if ($query->num_rows() > 0) $post = $query->row_array();

		$query->free_result();

		if ($post)
		{
			$this->db->where('pid', $pid)->where('mid', $mid)->delete('post_metas');

			$deleted = $this->db->affected_rows();

			if ($deleted && ($post['status'] == 'publish')) $this->update_count($mid, '-');

			return $deleted;
		}

		return FALSE;
	}

	/**
	 * 删除一篇文章的所有关系
	 *
	 * @access	public
	 * @param	integer	$pid	文章的PID
	 * @return	integer			返回删除关系的行数
	 */
	function delete_post_meta_by_post($pid)
	{
		$this->db->delete('post_metas', array('pid' => $pid));

		$deleted = $this->db->affected_rows();

		if ($deleted) $this->refresh_metas_count();

		return $deleted;
		
	}

	/**
	 * 删除一个用户的所有文章的所有关系
	 *
	 * @access	public
	 * @param	integer	$uid	用户的UID
	 * @return	integer			返回删除关系的行数
	 */
	function delete_post_meta_by_user($uid)
	{
		$posts = array();

		$query = $this->db->select('pid')->where('uid', $uid)->get('posts');

		if ($query->num_rows() > 0) $posts = $query->result_array();

		$query->free_result();

		if ($posts)
		{
			$deleted = 0;

			foreach($posts as $post)
			{
				$deleted += $this->db->where('pid', $post['pid'])->delete('post_metas');
			}

			if ($deleted) $this->refresh_metas_count();

			return $deleted;
		}

		return FALSE;
	}
}

/* End of file meta_model.php */
/* Location: ./application/models/meta_model.php */