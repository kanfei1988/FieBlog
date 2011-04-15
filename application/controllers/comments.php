<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends MY_Controller
{
	// 评论规则
	private $_comment_rules = array(
		array(
			'field'	=> 'author',
			'label'	=> '姓名',
			'rules'	=> 'required|trim|min_length[2]|max_length[200]|strip_tags'
		),
		array(
			'field'	=> 'mail',
			'label'	=> '电子邮件',
			'rules'	=> 'trim|valid_email|max_length[200]|strip_tags'
		),
		array(
			'field'	=> 'url',
			'label'	=> '个人网站',
			'rules'	=> 'trim|prep_url|max_length[200]|strip_tags'
		)
	);
	
	// Trackback规则
	private $_trackback_rules = array(
		array(
			'field'	=> 'blog_name',
			'label'	=> '博客名称',
			'rules'	=> 'required|trim|min_length[2]|max_length[200]|strip_tags'
		),
		array(
			'field'	=> 'title',
			'label'	=> '日志标题',
			'rules'	=> 'trim|strip_tags'
		),
		array(
			'field'	=> 'url',
			'label'	=> '日志URL',
			'rules'	=> 'trim|prep_url|strip_tags'
		),
		array(
			'field'	=> 'excerpt',
			'label'	=> '日志摘要',
			'rules'	=> 'trim'
		)
	);
	
	// 默认评论状态
	private $_status;

	function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('comment_model');
		$this->load->model('post_model');

		// 设置评论初始状态
		$this->_status = (intval(settings('comments_require_moderation')) == 1) ? 'waiting' : 'approved';

		// 评论是否需要电子邮件
		if (intval(settings('comments_require_mail')) == 1)
			$this->_comment_rules[1]['rules'] = 'required|' . $this->_comment_rules[1]['rules'];

		// 评论是否需要个人网站
		if (intval(settings('comments_require_url')) == 1)
			$this->_comment_rules[2]['rules'] = 'required|' . $this->_comment_rules[2]['rules'];

	}

	/**
	 * 发表评论
	 *
	 * @access	public
	 * @param	string	$pid	文章PID
	 * @return	void
	 */
	function index($pid = '')
	{
		if (empty($pid) || !is_numeric($pid)) go_back();

		$post = $this->post_model->get_post('pid', intval($pid));

		if (empty($post)) show_error('评论失败：该文章或页面不存在');

		if ((intval($post['allowComment']) === 0))
			show_error('评论失败：该文章或页面关闭了评论功能');

		$auto_close = intval(settings('comments_auto_close'));
		if ($auto_close > 0 && time() > ($post['created'] + $auto_close))
			show_error('评论失败：该文章或页面关闭了评论功能');

		$this->form_validation->set_rules($this->_comment_rules);

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
		}
		else
		{
			$this->load->library('user_agent');

			$comment_data = array(
				'created'	=> time(),
				'agent'		=> $this->agent->agent_string(),
				'ip'		=> $this->input->ip_address(),
				'pid'		=> $pid,
				'ownerId'	=> $post['uid'],
				'type'		=> 'comment',
				'status'	=> $this->_status
			);

			foreach (array('author', 'mail', 'url', 'text', 'parent') as $item)
			{
				$comment_data[$item] = $this->input->post($item, TRUE);
			}

			$insert_id = $this->comment_model->insert_comment($comment_data);

			if (empty($insert_id)) show_error('留言失败');

			go_back();
		}
	}

	/**
	 * 发送Trackback
	 *
	 * @access	public
	 * @param	string	$pid	文章PID
	 * @return	void
	 */
	function trackback($pid = '')
	{
		$this->load->library('trackback');

		if (empty($pid) || !is_numeric($pid)) $this->trackback->send_error('Unable to determine the entry ID');

		if ($this->input->server('REQUEST_METHOD') != 'POST') show_error('Trackback accepts POST request ONLY');

		$post = $this->post_model->get_post('pid', intval($pid));

		if (empty($post)) $this->trackback->send_error('Unable to retrieve the article');

		if (intval($post['allowPing']) === 0) $this->trackback->send_error('Ping denied');

		$this->form_validation->set_rules($this->_trackback_rules);

		if ($this->form_validation->run() == FALSE)
		{
			$this->trackback->send_error(validation_errors());
		}
		else
		{
			if (!$this->trackback->receive())
			{
				$this->trackback->send_error('The Trackback contains invalid data');
			}

			/*
			$content = serialize(array(
				'title'		=> $this->trackback->data('title'),
				'excerpt'	=> $this->_filter_text($this->trackback->data('excerpt'))
			));*/

			$content = '[' . html_entity_decode($this->trackback->data('title'), ENT_COMPAT, 'UTF-8') . ']'.
				$this->_filter_text(html_entity_decode($this->trackback->data('excerpt'), ENT_COMPAT, 'UTF-8'));

			$trackback = array(
				'created'	=> time(),
				'agent'		=> '',
				'ip'		=> $this->input->ip_address(),
				'pid'		=> $pid,
				'ownerId'	=> $post['uid'],
				'type'		=> 'trackback',
				'status'	=> $this->_status,
				'author'	=> html_entity_decode($this->trackback->data('blog_name'), ENT_COMPAT, 'UTF-8'),
				'url'		=> $this->trackback->data('url'),
				'text'		=> $content,
				'parent'	=> 0
			);

			$this->comment_model->insert_comment($trackback);

			$this->trackback->send_success();
		}
	}

	/**
	 * 文本过滤
	 *
	 * @access	public
	 * @param	string	$text	文本
	 * @return	string
	 */
	private function _filter_text($text)
	{
		$text = str_replace("\r", "", $text);
		$text = preg_replace("/\n{2,}/", "\n\n", $text);

		return $text;
	}
}

/* End of file comments.php */
/* Location: ./application/controllers/comments.php */