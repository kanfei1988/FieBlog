<?php
$config = array(
	'settings_general' => array(
		array(
			'field' => 'blog_title',
			'label' => '站点名称',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'blog_slogan',
			'label' => '站点口号',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'blog_description',
			'label' => '站点描述',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'blog_keywords',
			'label' => '关键词',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'homepage_type',
			'label' => '站点首页',
			'rules' => 'trim'
		),
		array(
			'field' => 'homepage_pid',
			'label' => '站点首页页面',
			'rules' => 'trim'
		),
		array(
			'field' => 'current_theme',
			'label' => '站点主题',
			'rules' => 'trim'
		),
		array(
			'field' => 'current_theme_format',
			'label' => '当前主题文件格式',
			'rules' => 'trim'
		),
		array(
			'field' => 'blog_status',
			'label' => '站点是否关闭',
			'rules' => 'trim'
		),	
		array(
			'field' => 'offline_reason',
			'label' => '站点关闭原因',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'upload_dir',
			'label' => '文件上传路径',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'upload_max_size',
			'label' => '上传文件最大尺寸',
			'rules' => 'trim|is_natural_no_zero|strip_tags'
		),
		array(
			'field' => 'upload_exts',
			'label' => '允许上传的文件类型',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'upload_img_exts',
			'label' => '允许上传的图片类型',
			'rules' => 'trim|strip_tags'
		)
	),
	'settings_discussion' => array(
		array(
			'field' => 'comment_date_format',
			'label' => '评论日期格式',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'comments_page_size',
			'label' => '每页评论数目',
			'rules' => 'trim|is_natural_no_zero|strip_tags'
		),
		array(
			'field' => 'comments_list_size',
			'label' => '评论列表数目',
			'rules' => 'trim|is_natural_no_zero|strip_tags'
		),
		array(
			'field' => 'comments_url_no_follow',
			'label' => '是否使用nofollow属性',
			'rules' => 'trim'
		),
		array(
			'field' => 'comments_require_moderation',
			'label' => '评论审核',
			'rules' => 'trim'
		),
		array(
			'field' => 'comments_auto_close',
			'label' => '自动关闭反馈功能',
			'rules' => 'trim'
		),
		array(
			'field' => 'comments_require_mail',
			'label' => '必须填写邮箱',
			'rules' => 'trim'
		),
		array(
			'field' => 'comments_require_url',
			'label' => '必须填写网址',
			'rules' => 'trim'
		),
		array(
			'field' => 'comments_allowed_html',
			'label' => '允许使用的HTML标签',
			'rules' => 'trim'
		)
	),
	'settings_reading' => array(
		array(
			'field' => 'post_date_format',
			'label' => '文章日期格式',
			'rules' => 'trim|strip_tags'
		),
		array(
			'field' => 'posts_page_size',
			'label' => '每页文章数目',
			'rules' => 'trim|is_natural_no_zero|strip_tags'
		),
		array(
			'field' => 'posts_list_size',
			'label' => '文章列表数目',
			'rules' => 'trim|is_natural_no_zero|strip_tags'
		),
		array(
			'field' => 'posts_full_text',
			'label' => '文章全文输出',
			'rules' => 'trim'
		),
		array(
			'field' => 'feed_full_text',
			'label' => '聚合全文输出',
			'rules' => 'trim'
		)
	),
	'settings_cache' => array(
		array(
			'field' => 'cache_enabled',
			'label' => '是否开启静态缓存',
			'rules' => 'trim'
		),
		array(
			'field' => 'cache_expire_time',
			'label' => '页面缓存自动刷新时间',
			'rules' => 'trim|is_natural_no_zero|strip_tags'
		),
		array(
			'field' => 'dbcache_expire_time',
			'label' => '数据缓存自动刷新时间',
			'rules' => 'trim|is_natural_no_zero|strip_tags'
		)
	),
	'profile' => array(
		array(
			'field' => 'password',
			'label' => '用户密码',
			'rules' => 'min_length[6]|trim'
		),
		array(
			'field' => 'confirm',
			'label' => '确认密码',
			'rules' => 'min_length[6]|trim|matches[password]'
		),
		array(
			'field' => 'screenName',
			'label' => '用户昵称',
			'rules' => 'required|trim|unique[users.screenName.uid]|strip_tags'
		),
		array(
			'field' => 'mail',
			'label' => '电子邮箱',
			'rules' => 'required|trim|valid_email|unique[users.mail.uid]'
		),
		array(
			'field' => 'url',
			'label' => '个人主页',
			'rules' => 'trim|prep_url'
		)
	),
	'meta' => array(
		array(
			'field' => 'name',
			'label' => '名称', 
			'rules' => 'required|trim|unique[metas.name.mid]|callback__name_to_slug|htmlspecialchars'
		),
		array(
			'field' => 'slug',
			'label' => '缩略名', 
			'rules' => 'trim|unique[metas.slug.mid]|alpha_dash|htmlspecialchars'
		)
	),
	'navigation' => array(
		array(
			'field' => 'title',
			'label' => '导航标题', 
			'rules' => 'required|trim|htmlspecialchars'
		),
		array(
			'field' => 'type',
			'label' => '链接类型', 
			'rules' => 'trim|htmlspecialchars'
		),
		array(
			'field' => 'url',
			'label' => '网址', 
			'rules' => 'trim'
		),
		array(
			'field' => 'uri',
			'label' => '网址URI', 
			'rules' => 'trim'
		),
		array(
			'field' => 'page',
			'label' => '页面', 
			'rules' => 'trim'
		),
		array(
			'field' => 'target',
			'label' => '视窗', 
			'rules' => 'trim'
		)
	),
	'post' => array(
		array(
			'field' => 'title',
			'label' => '标题', 
			'rules' => 'required|trim|htmlspecialchars'
		),
		array(
			'field' => 'text',
			'label' => '内容', 
			'rules' => 'required|trim'
		),
		array(
			'field' => 'trackback',
			'label' => 'Trackback', 
			'rules' => 'trim'
		),
		array(
			'field' => 'css',
			'label' => 'CSS', 
			'rules' => 'trim'
		),
		array(
			'field' => 'js',
			'label' => 'JavaScript', 
			'rules' => 'trim'
		),
		array(
			'field' => 'allowComment',
			'label' => '允许评论', 
			'rules' => 'trim'
		),
		array(
			'field' => 'allowPing',
			'label' => '允许被引用', 
			'rules' => 'trim'
		),
		array(
			'field' => 'allowFeed',
			'label' => '允许在聚合中出现', 
			'rules' => 'trim'
		),
		array(
			'field' => 'slug',
			'label' => '缩略名', 
			'rules' => 'trim|alpha_dash|htmlspecialchars'
		),
		array(
			'field' => 'created',
			'label' => '发布时间', 
			'rules' => 'required|trim'
		),		
		array(
			'field' => 'tags',
			'label' => '标签', 
			'rules' => 'trim|htmlspecialchars'
		),
		array(
			'field' => 'category[]',
			'label' => '分类', 
			'rules' => 'required|trim'
		)
	),
	'page' => array(
		array(
			'field' => 'title',
			'label' => '标题', 
			'rules' => 'required|trim|htmlspecialchars'
		),
		array(
			'field' => 'text',
			'label' => '内容', 
			'rules' => 'required|trim'
		),
		array(
			'field' => 'css',
			'label' => 'CSS', 
			'rules' => 'trim'
		),
		array(
			'field' => 'js',
			'label' => 'JavaScript', 
			'rules' => 'trim'
		),
		array(
			'field' => 'allowComment',
			'label' => '允许评论', 
			'rules' => 'trim'
		),
		array(
			'field' => 'allowPing',
			'label' => '允许被引用', 
			'rules' => 'trim'
		),
		array(
			'field' => 'allowFeed',
			'label' => '允许在聚合中出现', 
			'rules' => 'trim'
		),
		array(
			'field' => 'slug',
			'label' => '缩略名', 
			'rules' => 'trim|alpha_dash|htmlspecialchars'
		),
		array(
			'field' => 'created',
			'label' => '发布时间', 
			'rules' => 'required|trim'
		)
	)
);
?>