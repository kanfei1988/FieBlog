<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo settings('blog_title');;?> &#187; <?php echo $title;?></title>
<?php foreach ($this->assets->get_css() as $css => $extra):?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().$css;?>"<?php echo $extra?' '.$extra:''?> /> 
<?php endforeach;?>
<script type="text/javascript">var site_url = '<?php echo site_url();?>';</script>
<?php foreach ($this->assets->get_js() as $js => $extra):?>
<script type="text/javascript" src="<?php echo base_url().$js;?>"<?php echo $extra?' '.$extra:'';?>></script>
<?php endforeach;?>
</head>
<body>
	<div id="notify"><?php $this->load->view('notify');?></div>
	<div id="container">
		<div id="header">
			<h2><a href="<?php echo site_url();?>"><?php echo settings('blog_title');?></a></h2>
			<div id="topmenu">
				<ul>
					<li class="<?php echo ($parentPage=='dashboard'?'current':'');?>"><a href="<?php echo site_url('admin/dashboard');?>">控制台</a></li>
					<li class="<?php echo ($parentPage=='metas'?'current':'');?>"><a href="<?php echo site_url('admin/metas/manage');?>">分类&标签</a></li>
					<li class="<?php echo ($parentPage=='post'?'current':'');?>"><a href="<?php echo site_url('admin/posts/manage?reset=1&type=post');?>">文章</a></li>
					<li class="<?php echo ($parentPage=='page'?'current':'');?>"><a href="<?php echo site_url('admin/posts/manage?reset=1&type=page');?>">页面</a></li>
					<li class="<?php echo ($parentPage=='users'?'current':'');?>"><a href="<?php echo site_url('admin/users/manage');?>">用户</a></li>
					<li class="<?php echo ($parentPage=='settings'?'current':'');?>"><a href="<?php echo site_url('admin/settings/general');?>">设置</a></li>
				</ul>
			</div>
		</div>
		<div id="top-panel">
			<div id="panel">
				<?php if ($parentPage == 'dashboard'):?>
					<ul>
						<li><a href="<?php echo site_url('admin/dashboard');?>" class="report">网站概要</a></li>
						<?php if (is_administrator(1)):?>
							<li><a href="<?php echo site_url('admin/themes/manage')?>" class="theme">主题管理</a></li>
							<li><a href="<?php echo site_url('admin/navigations/manage');?>" class="menu">导航管理</a></li>
							<li><a href="<?php echo site_url('admin/settings/clean_cache');?>" class="refresh">缓存刷新</a></li>
							<li><a href="<?php echo site_url('admin/settings/backup');?>" class="backup">数据备份</a></li>
						<?php endif;?>
						<li><a href="<?php echo site_url('admin/comments/manage?reset=1');?>" class="comment">评论管理</a></li>
						<li><a href="<?php echo site_url('admin/profile');?>" class="profile">个人设置</a></li>
						<li><a href="<?php echo site_url('admin/login/logout');?>" class="logout">安全退出</a></li>
					</ul>
				<?php elseif ($parentPage == 'metas'):?>
					<?php if(is_editor(1)):?>
						<ul>
							<li><a href="<?php echo site_url('admin/metas/manage');?>" class="category">分类管理</a></li>
							<li><a href="<?php echo site_url('admin/metas/manage/tag');?>" class="tag">标签管理</a></li>
						</ul>
					<?php endif;?>
				<?php elseif ($parentPage == 'post'):?>
					<ul>
						<li><a href="<?php echo site_url('admin/posts/manage?reset=1&type=post');?>" class="post">文章管理</a></li>
						<li><a href="<?php echo site_url('admin/posts/write');?>" class="post_add">撰写文章</a></li>
					</ul>
				<?php elseif ($parentPage == 'page'):?>
					<?php if (is_editor(1)):?>
						<ul>
							<li><a href="<?php echo site_url('admin/posts/manage?reset=1&type=page');?>" class="page">页面管理</a></li>
							<li><a href="<?php echo site_url('admin/posts/write/page');?>" class="page_add">创建页面</a></li>
						</ul>
					<?php endif;?>
				<?php elseif ($parentPage == 'users'):?>
					<?php if (is_administrator(1)):?>
						<ul>
							<li><a href="<?php echo site_url('admin/users/manage');?>" class="user">用户管理</a></li>
							<li><a href="<?php echo site_url('admin/users/user');?>" class="user_add">添加用户</a></li>
						</ul>
					<?php endif;?>
				<?php elseif ($parentPage == 'settings'):?>
					<?php if (is_administrator(1)):?>
						<ul>
							<li><a href="<?php echo site_url('admin/settings/general');?>" class="general_setting">基本设置</a></li>
							<li><a href="<?php echo site_url('admin/settings/discussion');?>" class="comment_setting">评论设置</a></li>
							<li><a href="<?php echo site_url('admin/settings/reading');?>" class="reading_setting">文章设置</a></li>
							<li><a href="<?php echo site_url('admin/settings/cache');?>" class="cache_setting">缓存设置</a></li>
						</ul>
					<?php endif;?>
				<?php endif;?>
			</div>
		</div>
		<div id="wrapper">