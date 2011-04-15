<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends CI_Controller
{
	private $_meta_type = array('category' => '分类', 'tag' => '标签');

	private $_feed_full_text;

	private $_count = '';

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('feedwriter', RSS2);
		$this->load->model('post_model');
		$this->load->model('comment_model');
		
		$this->_feed_full_text = intval(settings('feed_full_text'));
	}

	/**
	 * 请求分发
	 *
	 * @access	public
	 * @param	string	$key	类别
	 * @param	string	$value	缩略名
	 * @return void
	 */
	public function index($key = '', $value = '')
	{
		switch ($key)
		{
			case 'category':	$this->generate_meta_feed('category', $value); break;
			case 'tag':			$this->generate_meta_feed('tag', $value); break;
			case 'post':		$this->generate_comments_feed($value); break;
			case 'comments':	$this->generate_comments_feed(); break;
			default:			$this->generate_feed(); break;
		}
	}

	/**
	 * 处理item节点，并生成xml文档
	 *
	 * @access	private
	 * @param  string $slug
	 * @return void
	 */
	private function _generate($posts)
	{
		if($posts)
		{
			foreach($posts as $post)
			{
				$description = ($this->_feed_full_text == 1) ? 
					get_post_excerpt($post['text']) : 
					get_post_content($post['text']);

				$newItem = $this->feedwriter->createNewItem();

				$newItem->setTitle($post['title']);
				$newItem->setLink($post['permalink']);
				$newItem->setDate($post['created']);
				$newItem->setDescription($description);
				$newItem->addElement('author', $post['screenName']);
				$newItem->addElement('guid', $post['permalink'], array('isPermaLink' => 'true'));

				$this->feedwriter->addItem($newItem);
			}
		}

		$this->feedwriter->genarateFeed();
	}

	/**
	 * 生成所有日志feed
	 *
	 * @access	public
	 * @return void
	 */
	public function generate_feed()
	{
		$where = array('limit' => 10, 'feed_filter' => TRUE);
		$posts = $this->post_model->get_posts($where, $this->_count, array('permalink'));

		$this->feedwriter->setTitle(settings('blog_title'));
		$this->feedwriter->setLink(site_url());
		$this->feedwriter->setDescription(settings('blog_description'));
		$this->feedwriter->setChannelElement('language', 'zh-CN');
		$this->feedwriter->setChannelElement('pubDate', date(DATE_RSS, time()));
		
		$this->_generate($posts);
	}

	/**
	 * 生成指定分类或标签日志feed
	 *
	 * @access	public
	 * @return void
	 */
	function generate_meta_feed($type, $slug)
	{
		$this->load->model('meta_model');
		$meta = $this->meta_model->get_meta($type, 'slug', $slug);

		$meta_type = $this->_meta_type[$type];

		if(empty($meta)) show_error('发生错误：'.$meta_type.'不存在或已被删除');

		$this->feedwriter->setTitle(settings('blog_title') . ' - '.$meta_type.'：' .$meta['name']);
		$this->feedwriter->setLink(site_url($type.'/'. $meta['slug']));
		$this->feedwriter->setDescription($meta['description']);
		$this->feedwriter->setChannelElement('language', 'zh-CN');
		$this->feedwriter->setChannelElement('pubDate', date(DATE_RSS, time()));

		$where = array('meta_type' => $type, 'meta_slug' => $slug, 'limit' => 10, 'feed_filter' => TRUE);
		$posts = $this->post_model->get_posts($where, $this->_count, array('permalink'));

		$this->_generate($posts);
	}

	/**
	 * 生成评论feed
	 *
	 * @access	public
	 * @return void
	 */
	public function generate_comments_feed($slug = '')
	{
		if ($slug)
		{
			$post = $this->post_model->get_post('slug', $slug, array('permalink', 'content'));
			if(empty($post)) show_error('发生错误：内容不存在或已被删除');
			$this->feedwriter->setTitle(settings('blog_title') . ' － ' . $post['title'] . ' 的评论');
			$this->feedwriter->setLink($post['permalink']);
			$this->feedwriter->setDescription(mb_word_limiter(strip_tags(get_post_content($post['text'])), 200, '...'));
		}
		else
		{
			$this->feedwriter->setTitle(settings('blog_title') . '所有评论');
			$this->feedwriter->setLink(site_url());
			$this->feedwriter->setDescription(settings('blog_description'));
		}

		$this->feedwriter->setChannelElement('language', 'zh-CN');
		$this->feedwriter->setChannelElement('pubDate', date(DATE_RSS, time()));

		$where = array('type' => 'comment', 'status' => 'approved', 'limit' => 100);
		if ($slug) $where['pid'] = $post['pid'];

		$comments = $this->comment_model->get_comments($where, $this->_count, array('permalink', 'content'));

		if($comments)
		{
			foreach($comments as $comment)
			{
				$newItem = $this->feedwriter->createNewItem();

				$title = ($slug) ? $comment['author'] . ' 评' : $comment['author'] . '评论：' . $comment['post_title'];

				$newItem->setTitle($title);
				$newItem->setLink($comment['permalink']);
				$newItem->setDate($comment['created']);
				$newItem->setDescription($comment['content']);
				$newItem->addElement('author', $comment['author']);
				$newItem->addElement('guid', $comment['permalink'], array('isPermaLink'=>'true'));

				$this->feedwriter->addItem($newItem);
			}
		}

		$this->feedwriter->genarateFeed();
	}
}

/* End of file feed.php */
/* Location: ./application/controllers/feed.php */