<div id="sidebar">
	<ul id="sb1">
		<li id="search">
			<form id="searchform" method="get" action="<?php echo site_url('search');?>">
				<h2><label for="s">Search</label></h2>
				<div>
					<input type="text" id="s" name="s" value="" />
					<input type="submit" class="submit" value="search" />
				</div>
			</form>
		</li>
		<li id="categories-5" class="widget widget_categories">
			<h2>分类目录</h2>
			<ul>
				<?php foreach (get_metas('category') as $category):?>
					<li class="cat-item"><?php echo $category['titleLink'];?> (<?php echo $category['count'];?>)</li>
				<?php endforeach;?>
			</ul>
		</li>
		<li id="archives-6" class="widget widget_archive">
			<h2>文章归档</h2>
			<ul>
				<?php foreach (get_archives() as $archiveLink):?>
					<li><?php echo $archiveLink;?></li>
				<?php endforeach;?>
			</ul>
		</li>
		<li id="tag_cloud-5" class="widget widget_tag_cloud">
			<h2>标签</h2>
			<div>
				<?php foreach (get_metas('tag') as $tag):?>
					<?php echo $tag['titleLink'];?>&nbsp;&nbsp;
				<?php endforeach;?>
			</div>
		</li>
		<li id="recent-posts-6" class="widget widget_recent_entries">
			<h2>最近文章</h2>
			<ul>
				<?php foreach (get_recent_posts() as $recent_post):?>
					<li><?php echo $recent_post['titleLink'];?></li>
				<?php endforeach;?>
			</ul>
		</li>
		<li id="recent-comments-5" class="widget widget_recent_comments">
			<h2>近期评论</h2>
			<ul id="recentcomments">
				<?php foreach (get_recent_comments() as $comment):?>
					<li class="recentcomments"><?php echo $comment['authorLink'];?> 在 <?php echo $comment['titleLink'];?> 上的评论</li>
				<?php endforeach;?>
			</ul>
		</li>
		<li id="linkcat-2" class="widget widget_links">
			<h2>链接表</h2>
			<ul class='xoxo blogroll'>
				<li><a href="http://www.google.com.hk/" title="Google" target="_blank">Google</a></li>
				<li><a href="http://codeigniter.org.cn/" target="_blank">CodeIgniter中国</a></li>
				<li><a href="http://www.fieblog.co.cc/" title="FieBlog" target="_blank">FieBlog</a></li>
			</ul>
		</li>
		<li id="meta-6" class="widget widget_meta">
			<h2 class="widgettitle">功能</h2>
			<ul>
				<li><a href="<?php echo site_url('admin/dashboard');?>">站点管理</a></li>
				<li><a href="<?php echo site_url('admin/login');?>">登入</a></li>
				<li><a href="<?php echo site_url('feed');?>" title="使用 RSS 2.0 同步站点内容">文章 RSS</a></li>
				<li><a href="<?php echo site_url('feed/comments');?>" title="RSS 上的最近评论">评论 RSS</a></li>
			</ul>
		</li>
	</ul>
</div><!-- #sidebar -->