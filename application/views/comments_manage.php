<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>
			<span>
				<a href="<?php echo site_url('admin/comments/manage?uid='.$this->auth->get('uid'));?>" class="<?php echo ($uid == $this->auth->get('uid'))?'selected':'';?>">我的留言</a>
				<?php if (is_editor(1)):?>
				 | <a href="<?php echo site_url('admin/comments/manage?uid=0');?>" class="<?php echo ($uid == '0')?'selected':'';?>">全部留言</a>
				<?php endif;?>
			</span>
			<a href="<?php echo site_url('admin/comments/manage?status=spam');?>" class="option spam <?php echo $status=='spam'?'selected':'';?>">垃圾(<?php echo $count['spam'];?>)</a>
			<a href="<?php echo site_url('admin/comments/manage?status=waiting');?>" class="option waiting <?php echo $status=='waiting'?'selected':'';?>">待审核(<?php echo $count['waiting'];?>)</a>
			<a href="<?php echo site_url('admin/comments/manage?status=approved');?>" class="option approved <?php echo $status=='approved'?'selected':'';?>">通过(<?php echo $count['approved'];?>)</a>
			<br style="clear:both" />
		</h3>
		<form method="get" name="select_comments" action="<?php echo site_url('admin/comments/manage');?>">
			<p class="operate">
				操作：
				<a href="javascript:void(0)" class="operate select-all">全选</a>，
				<a href="javascript:void(0)" class="operate select-none">不选</a>，
				<a href="<?php echo site_url('admin/comments/manage?reset=1');?>" class="operate">刷新</a>&nbsp;&nbsp;&nbsp;
				选中项：
				<?php if ($status != 'approved'):?>
					<a href="javascript:void(0)" class="operate select-submit" rel="approved">通过</a>，
				<?php endif;?>
				<?php if ($status != 'waiting'):?>
					<a href="javascript:void(0)" class="operate select-submit" rel="waiting">待审核</a>，
				<?php endif;?>
				<?php if ($status != 'spam'):?>
					<a href="javascript:void(0)" class="operate select-submit" rel="spam">标记垃圾</a>，
				<?php endif;?>
				<a href="javascript:void(0)" class="operate select-submit" rel="delete" lang="确认要删除这些评论吗？">删除</a>
				<?php if ($status == 'spam'):?>
					，<a href="javascript:void(0)" class="operate select-submit" rel="delete-spam" lang="确认删除所有垃圾评论吗？">删除所有垃圾评论</a>
				<?php endif;?>
				
			</p>
			<p class="searchbox">
				<input type="text" name="keywords" value="<?php echo $keywords;?>" onclick="" />
				<button type="submit">筛选</button>
			</p>
		</form>
		<?php echo form_open('admin/comments/operate', 'name="manage_comments" class="operate-form"');?>
			<?php echo form_hidden('op', '');?>
			<?php echo form_hidden('uid', $uid);?>
			<?php echo form_hidden('original_status', $status);?>
			<table id="comments_table" class="manage" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th width="40px">&nbsp;</th>
						<th width="20px">&nbsp;</th>
						<th width="100px">评论者</th>
						<th width="160px">邮件</th>
						<th width="150px">所属文章</th>
						<th>内容</th>
						<th width="110px">时间</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($comments as $num => $comment):?>
					<tr id="comment-<?php echo $comment['cid'];?>" class="<?php echo ($num%2)?'odd':'even';?>">
						<td class="center"><?php echo form_checkbox('cids[]', $comment['cid']);?></td>
						<td><span class="<?php echo $comment['type'];?>"></span></td>
						<td><?php echo $comment['authorLink'];?></td>
						<td><?php echo mb_word_limiter($comment['mail'], 23);?></td>
						<td><?php echo $comment['postLink'];?></a></td>
						<td><?php echo mb_word_limiter(strip_tags($comment['text']), 27);?></td>
						<td><?php echo date('Y-m-d H:i', $comment['created']);?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
			<?php echo $pagination;?>
		<?php echo form_close();?>
	</div>
</div>
<?php $this->load->view('footer');?>