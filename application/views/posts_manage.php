<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>
			<span>
				<a href="<?php echo site_url('admin/posts/manage?uid='.$this->auth->get('uid'));?>" class="<?php echo ($uid == $this->auth->get('uid'))?'selected':'';?>"><?php echo ($type == 'post')?'我的文章':'我的页面';?></a>
				<?php if (is_editor(1)):?>
					 | <a href="<?php echo site_url('admin/posts/manage?uid=0');?>" class="<?php echo $uid == '0'?'selected':'';?>"><?php echo ($type == 'post')?'全部文章':'全部页面';?></a>
				<?php endif;?>
			</span>
			<a href="<?php echo site_url('admin/posts/manage?status=waiting');?>" class="option waiting <?php echo $status=='waiting'?'selected':'';?>">待审核(<?php echo $count['waiting'];?>)</a>
			<a href="<?php echo site_url('admin/posts/manage?status=draft');?>" class="option draft <?php echo $status=='draft'?'selected':'';?>">草稿(<?php echo $count['draft'];?>)</a>
			<a href="<?php echo site_url('admin/posts/manage?status=publish');?>" class="option publish <?php echo $status=='publish'?'selected':'';?>">已发布(<?php echo $count['publish'];?>)</a>
			<br style="clear:both" />
		</h3>
		<form method="get" name="select_posts" action="<?php echo site_url('admin/posts/manage');?>">
			<p class="operate">
				操作：
				<a href="javascript:void(0)" class="operate select-all">全选</a>，
				<a href="javascript:void(0)" class="operate select-none">不选</a>，
				<a href="<?php echo site_url('admin/posts/manage?reset=1');?>" class="operate">刷新</a>&nbsp;&nbsp;&nbsp;
				选中项：
				<?php if ($status != 'publish'):?>
					<a href="javascript:void(0)" class="operate select-submit" rel="publish">发布</a>，
				<?php endif;?>
				<?php if ($status != 'draft'):?>
					<a href="javascript:void(0)" class="operate select-submit" rel="draft">草稿</a>，
				<?php endif;?>
				<?php if ($status != 'waiting'):?>
					<a href="javascript:void(0)" class="operate select-submit" rel="waiting">待审核</a>，
				<?php endif;?>
				<a href="javascript:void(0)" class="operate select-submit" rel="delete" lang="确认要删除这篇<?php echo ($type=='post')?'文章':'页面';?>吗？">删除</a>
			</p>
			<p class="searchbox">
				<?php echo form_input('keywords', isset($keywords)?$keywords:'');?>
				<?php if ($type=='post'):?>
					<?php echo form_hidden('meta_type', 'category');?>
					<select name="meta_slug">
						<option value="" <?php echo (isset($meta_slug) && $meta_slug)?'':'selected="selected"';?>>所有分类</option>
						<?php foreach ($category_list as $item):?>
							<option value="<?php echo $item['slug'];?>" <?php echo (isset($meta_slug) && $meta_slug==$item['slug'])?'selected="selected"':'';?>><?php echo mb_word_limiter($item['name'], 8);?></option>
						<?php endforeach;?>
					</select>
				<?php endif;?>
				<button type="submit">筛选</button>
			</p>
		</form>
		<?php echo form_open('admin/posts/operate', 'name="manage_posts" class="operate-form"');?>
			<?php echo form_hidden('op', '');?>
			<?php echo form_hidden('type', $type);?>
			<?php echo form_hidden('original_status', $status);?>
			<table id="<?php echo ($type=='post')?'posts_table':'pages_table';?>" class="manage" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th width="40px">&nbsp;</th>
						<th width="40px">评论数</th>
						<th width="220px">标题</th>
						<th>缩略名</th>
						<th width="20px">&nbsp;</th>
						<th width="160px"><?php echo ($type=='post')?'分类':'导航';?></td>
						<th width="100px">作者</th>
						<th width="110px">发布日期</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($posts as $num => $post):?>
					<tr id="post-<?php echo $post['pid'];?>" class="<?php echo ($num%2)?'odd':'even';?>">
						<td class="center"><?php echo form_checkbox('pids[]', $post['pid']);?></td>
						<td><?php echo anchor($post['commentsManageLink'], $post['commentsNum'], 'class="balloon-button"');?></td>
						<td><?php echo anchor($post['writeLink'], mb_word_limiter($post['title'], 15));?></td>
						<td><?php echo mb_word_limiter($post['slug'], 25);?></td>
						<td><?php echo $post['viewLink'];?></td>
						<td><?php echo ($type=='post') ? mb_word_limiter($post['categories_str'], 15) : $post['navigationLink'];?></td>
						<td><?php echo $post['authorManageLink'];?></td>
						<td><?php echo date('Y-m-d H:i', $post['created']);?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
			<?php echo $pagination;?>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>