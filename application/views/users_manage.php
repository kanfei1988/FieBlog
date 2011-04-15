<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box">
		<h3>用户管理</h3>
		<form method="get">
			<p class="operate">
				操作：<a href="javascript:void(0)" class="operate select-all">全选</a>，<a href="javascript:void(0)" class="operate select-none">不选</a>&nbsp;&nbsp;&nbsp;
				选中项：<a href="javascript:void(0)" class="operate select-submit" rel="delete" lang="确认要删除这个用户以及他的所有文章吗？">删除</a>
			</p>			
		</form>
		<form method="post" name="manage_users" class="operate-form" action="<?php echo site_url('admin/users/operate');?>">
			<input type="hidden" name="op" value="" />
			<table id="users_table" class="manage" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th width="40px"></th>
						<th width="40px">文章数</th>
						<th width="40px">评论数</th>
						<th>用户名</th>
						<th width="180px">昵称</th>
						<th width="20px"> </th>
						<th width="200px">电子邮件</th>
						<th width="80px">用户组</th>
						<th width="110px">最后登录</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($users as $num => $user):?>
					<tr id="user-<?php echo $user['uid'];?>" class="<?php echo ($num%2)?'odd':'even';?>">
						<td class="center"><input type="checkbox" name="uids[]" value="<?php echo $user['uid'];?>"></td>
						<td><a href="<?php echo site_url('admin/posts/manage?reset=1&uid='.$user['uid']);?>" class="balloon-button"><?php echo $user['postsNum'];?></a></td>
						<td><a href="<?php echo site_url('admin/comments/manage?reset=1&uid='.$user['uid']);?>" class="balloon-button"><?php echo $user['commentsNum'];?></a></td>
						<td><a href="<?php echo site_url('admin/users/user/'.$user['uid']);?>"><?php echo mb_word_limiter($user['name'], 28);?></a></td>
						<td><?php echo mb_word_limiter($user['screenName'], 12);?></td>
						<td><a href="<?php echo site_url('author/'.$user['name']);?>" class="hidden" target="_blank"><img src="<?php echo base_url().'/assets/images/view.gif';?>"></a></td>
						<td><?php echo mailto($user['mail'], mb_word_limiter($user['mail'], 28));?></td>
						<td><?php echo $user['groupName'];?></td>
						<td><?php echo date('Y-m-d H:i',$user['logged']);?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</form>
	</div><!-- #box -->
</div>
<?php $this->load->view('footer');?>