<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<div id="content">
	<div id="box" class="metas-manage">
		<h3>
			<span><?php echo ($type=='category')?'分类管理':'标签管理';?></span>
			<a href="<?php echo site_url('admin/metas/manage/tag');?>" class="option tag">标签(<?php echo $count['tag'];?>)</a>
			<a href="<?php echo site_url('admin/metas/manage/category');?>" class="option category">分类(<?php echo $count['category'];?>)</a>
			<br style="clear:both;" />
		</h3>
		<div class="left">
			<form method="post" name="manage_categories" action="<?php echo site_url('admin/metas/operate');?>" class="meta_table operate-form">
				<input type="hidden" name="op" value="" />
				<input type="hidden" name="type" value="<?php echo $type;?>">
				<p class="operate">
					操作：
					<a href="javascript:void(0)" class="operate select-all">全选</a>，
					<a href="javascript:void(0)" class="operate select-none">不选</a>&nbsp;&nbsp;&nbsp;
					选中项：
					<a href="javascript:void(0)" class="operate select-submit" rel="delete" lang="确认要删除这个<?php echo ($type=='category')?'分类':'标签';?>吗？">删除</a>，
					<a href="javascript:void(0)" class="operate select-submit" rel="merge">合并到</a>
					<?php if ($type == 'category'):?>
						<select name="merge">
							<?php foreach ($metas as $item):?>
								<option value="<?php echo $item['mid'];?>"><?php echo mb_word_limiter($item['name'], 8);?></option>
							<?php endforeach;?>
						</select>
					<?php else:?>
						<input type="text" name="merge" value="" />
					<?php endif;?>
				</p>
				<table id="<?php echo ($type=='category')?'categories_table':'tags_table';?>" class="manage" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th width="40px"> </th>
							<th width="40px">文章数</th>
							<th width="250px">名称</th>
							<th width="20px"> </th>
							<th>缩略名</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($metas as $num => $item):?>
							<tr id="meta-<?php echo $item['mid'];?>" class="<?php echo ($num%2)?'odd':'even';?>">
								<td class="center"><?php echo form_checkbox('mids[]', $item['mid']);?></td>
								<td><?php echo $item['postsLink'];?></td>
								<td><?php echo $item['manageLink'];?></td>
								<td><?php echo $item['viewLink'];?></td>
								<td><?php echo mb_word_limiter($item['slug'], 30);?></td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</form>
		</div>
		<div class="right">
			<?php if ($type == 'category'):?>
				<?php echo form_open('admin/metas/manage/category'.(isset($meta['mid'])?'/'.$meta['mid']:''), 'class="meta_form"');?>
				<?php echo form_hidden('mid', (isset($meta['mid'])?$meta['mid']:'0'));?>
				<?php echo form_hidden('type', 'category');?>
					<ul>
						<li>
							<label for="name" class="label">分类名称</label>
							<?php echo form_error('name' ,'<p class="error">', '</p>');?>
							<input type="text" class="text" id="name" name="name" value="<?php echo set_value('name', isset($meta['name'])?$meta['name']:'');?>" />
						</li>
						<li>
							<label for="slug" class="label">分类缩略名</label>
							<?php echo form_error('slug' ,'<p class="error">', '</p>');?>
							<input type="text" class="text" id="slug" name="slug" value="<?php echo set_value('slug', isset($meta['slug'])?$meta['slug']:'');?>" />
							<p class="description">分类缩略名用于创建友好的链接形式,建议使用字母,数字,下划线和横杠.</p>
						</li>
						<li>
							<label for="description" class="label">分类描述</label>
							<?php echo form_error('description' ,'<p class="error">', '</p>');?>
							<textarea id="description" name="description"><?php echo set_value('description', isset($meta['description'])?$meta['description']:'');?></textarea>
							<p class="description">此文字用于描述分类,在有的主题中它会被显示</p>
						</li>
						<li>
							<button type="submit" id="button1"><?php echo isset($meta)?'更新分类':'添加分类';?></button>
						</li>
					</ul>
				<?php echo form_close();?>
			<?php else:?>
				<?php echo form_open('admin/metas/manage/tag'.(isset($meta['mid'])?'/'.$meta['mid']:''), 'class="meta_form"');?>
				<input type="hidden" name="mid" value="<?php echo (isset($meta['mid'])?$meta['mid']:'0');?>" />
				<input type="hidden" name="type" value="tag" />
					<ul>
						<li>
							<label for="name" class="label">标签名称</label>
							<?php echo form_error('name' ,'<p class="error">', '</p>');?>
							<input type="text" class="text" id="name" name="name" value="<?php echo set_value('name', isset($meta['name'])?$meta['name']:'');?>" />
							<p class="description">这是标签在站点中显示的名称.可以使用中文,如"地球".</p>
						</li>
						<li>
							<label for="slug" class="label">标签缩略名</label>
							<?php echo form_error('slug' ,'<p class="error">', '</p>');?>
							<input type="text" class="text" id="slug" name="slug" value="<?php echo set_value('slug', isset($meta['slug'])?$meta['slug']:'');?>" />
							<p class="description">标签缩略名用于创建友好的链接形式,如果留空则默认使用标签名称.</p>
						</li>
						<li>
							<button type="submit" id="button1"><?php echo isset($meta)?'更新标签':'添加标签';?></button>
						</li>
					</ul>
				<?php echo form_close();?>
			<?php endif;?>
			<div style="clear:both;"></div>
		</div>
		<br class="clear" />
	</div>
</div>
<?php $this->load->view('footer');?>