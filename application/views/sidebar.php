<div id="sidebar">
	<ul>
		<li>
			<h3>控制台</h3>
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
		</li>
		<?php if (is_editor(1)):?>
			<li>
				<h3>分类&标签</h3>
				<ul>
					<li><a href="<?php echo site_url('admin/metas/manage');?>" class="category">分类管理</a></li>
					<li><a href="<?php echo site_url('admin/metas/manage/tag');?>" class="tag">标签管理</a></li>
				</ul>
			</li>
		<?php endif;?>
		<li>
			<h3>文章</h3>
			<ul>
				<li><a href="<?php echo site_url('admin/posts/manage?reset=1&type=post');?>" class="post">文章管理</a></li>
				<li><a href="<?php echo site_url('admin/posts/write');?>" class="post_add">撰写文章</a></li>
			</ul>
		</li>
		<?php if (is_editor(1)):?>
			<li>
				<h3>页面</h3>
				<ul>
					<li><a href="<?php echo site_url('admin/posts/manage?reset=1&type=page');?>" class="page">页面管理</a></li>
					<li><a href="<?php echo site_url('admin/posts/write/page');?>" class="page_add">创建页面</a></li>
				</ul>
			</li>
		<?php endif;?>
		<?php if (is_administrator(1)):?>
			<li>
				<h3>用户</h3>
				<ul>
					<li><a href="<?php echo site_url('admin/users/manage');?>" class="user">用户管理</a></li>
					<li><a href="<?php echo site_url('admin/users/user');?>" class="user_add">添加用户</a></li>
				</ul>
			</li>
			<li>
				<h3>设置</h3>
				<ul>
					<li><a href="<?php echo site_url('admin/settings/general');?>" class="general_setting">基本设置</a></li>
					<li><a href="<?php echo site_url('admin/settings/discussion');?>" class="comment_setting">评论设置</a></li>
					<li><a href="<?php echo site_url('admin/settings/reading');?>" class="reading_setting">文章设置</a></li>
					<li><a href="<?php echo site_url('admin/settings/cache');?>" class="cache_setting">缓存设置</a></li>
				</ul>
			</li>
		<?php endif;?>
	</ul>
</div>
