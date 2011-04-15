<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>网站概要</h3>
		<div id="infowrap">
			<div class="infobox left summary">
				<h3>网站统计</h3>
				<div class="left">
					<h4>内容</h4>
					<ul>
						<li><span class="num"><?php echo $count['posts'];?></span>&nbsp;<a href="<?php echo site_url('admin/posts/manage');?>">文章</a></li>
						<li><span class="num"><?php echo $count['pages'];?></span>&nbsp;<a href="<?php echo site_url('admin/posts/manage/page');?>">页面</a></li>
						<li><span class="num"><?php echo $count['categories'];?></span>&nbsp;<a href="<?php echo site_url('admin/metas/manage');?>">分类</a></li>
						<li><span class="num"><?php echo $count['tags'];?></span>&nbsp;<a href="<?php echo site_url('admin/posts/manage/tag');?>">标签</a></li>
					</ul>

				</div>
				<div class="right">
					<h4>评论</h4>
					<ul>
						<li><span class="num"><?php echo $count['comments']?></span>&nbsp;<a href="<?php echo site_url('admin/comments/manage');?>">评论</a></li>
						<li><span class="num"><?php echo $count['approved_comments']?></span>&nbsp;<a href="<?php echo site_url('admin/comments/manage/approved');?>" class="approved_comment">已获准</a></li>
						<li><span class="num"><?php echo $count['waiting_comments'];?></span>&nbsp;<a href="<?php echo site_url('admin/comments/manage/waiting');?>" class="waiting_comment">待审</a></li>
						<li><span class="num"><?php echo $count['spam_comments'];?></span>&nbsp;<a href="<?php echo site_url('admin/comments/manage/spam');?>" class="spam_comment">垃圾评论</a></li>
					</ul>
				</div>
				<div class="intro clear">您正在使用 FieBlog <?php echo APP_VERSION;?></div>
			</div>
			<div class="infobox right">
				<h3>
					<span>最近发表的文章</span>
					<a href="<?php echo site_url('admin/posts/manage?reset=1');?>" class="option">更多</a>
					<br style="clear:both;" />
				</h3>
				<table class="manage" cellpadding="0" cellspacing="0">
					<?php foreach ($posts as $post):?>
						<tr>
							<td width="30px" class="ball"><a href="<?php echo $post['commentsManageLink'];?>" class="balloon-button"><?php echo $post['commentsNum'];?></a></td>
							<td><a href="<?php echo $post['writeLink'];?>"><?php echo mb_word_limiter($post['title'], 19);?></a></td>
							<td width="20px"><?php echo $post['viewLink'];?></td>
							<td width="110px"><?php echo date('Y-m-d H:i', $post['created']);?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<br style="clear:both" />
			<div class="infobox left">
				<h3>
					<span>快速发布</span>
					<a href="<?php echo site_url('admin/posts/write');?>" class="option">写日志</a>
					<br class="clear" />
				</h3>
				<form id="fast_write" method="post" action="<?php echo site_url('admin/posts/write');?>">
				<input type="hidden" name="type" value="post" />
				<input type="hidden" name="status" value="publish" />
				<input type="hidden" name="uid" value="<?php echo $uid;?>" />
				<input type="hidden" name="allowComment" value="1" />
				<input type="hidden" name="allowPing" value="1" />
				<input type="hidden" name="allowFeed" value="1" />
				<input type="hidden" name="created" value="<?php echo date('Y-m-d H:i');?>" />
				<input type="hidden" name="dashboard" value="1" />
					<p>
						<label for="title" class="label">标题</label>
						<select name="category[]">
							<?php foreach ($categories as $category):?>
								<option value="<?php echo $category['mid']?>"><?php echo mb_word_limiter($category['name'], 4);?></option>
							<?php endforeach;?>
						</select>
						<input type="text" id="title"class="text" name="title" value="" />
					</p>
					<p>
						<label for="text" class="label">内容</label>
						<textarea id="text" name="text"></textarea>
					</p>
					<p class="submit">
						<button type="button" id="button1">保存</button>&nbsp;&nbsp;
						<button type="submit" id="button2">发布</button>
					</p>
				</form>
			</div>
			<div class="infobox right">
				<h3>
					<span>最近得到的回复</span>
					<a href="<?php echo site_url('admin/comments/manage?reste=1');?>" class="option">更多</a>
					<br style="clear:both;" />
				</h3>
				<table class="manage" cellpadding="0" cellspacing="0">
					<?php foreach ($comments as $comment):?>
						<tr>
							<td width="16px"><span class="<?php echo $comment['type'];?>"></span></td>
							<td width="80px"><?php echo $comment['authorLink'];?></td>
							<td><a href="<?php echo $comment['manageLink'];?>"class="<?php echo $comment['status'];?>_comment"><?php echo mb_word_limiter(strip_tags($comment['text']), 15);?></td>
							<td width="20px"><?php echo $comment['viewLink'];?></td>
							<td width="110px"><?php echo date('Y-m-d H:i', $comment['created']);?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="clear:both"></div>
		</div><!-- #infowrap -->
	</div>
</div><!-- #content -->
<?php $this->load->view('footer');?>