jQuery(function($){
	// 消息框淡出
	$('#notify').delay(2000).fadeOut(3000);

	// 日期时间插件
	if ($('#created').length) {
		$('#created').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeText: '时间',
			hourText: '时钟',
			minuteText: '分钟',
			currentText: '当前',
			closeText: '完成',
			ampm: false,
			prevText: '上个月',
			nextText: '下个月',
			monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
			dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
			dayNames: ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
			firstDay: 1
		});
	}

	// 排序插件助手
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};

	// 导航的排序
	if ($('#navigations_table tbody').length) {
		$('#navigations_table tbody').sortable({
			helper: fixHelper,
			axis: 'y',
			cursor: 'move',
			update: function(){
				$('#navigations_table tbody tr').removeClass('odd even');
				$('#navigations_table tbody tr:odd').addClass('odd');
				$('#navigations_table tbody tr:even').addClass('even');

				$.ajax({
					type: 'get',
					url: site_url+'/admin/ajax/sort_navigations',
					data: $('#navigations_table tbody').sortable('serialize'),
					success: function(msg) {
						if (msg > 0) {
							$('#notify').html('<p class="success">导航排序已更新</p>').show().delay(2000).fadeOut(3000);
						}
					}
				});
			}
		}).disableSelection();
	}

	// 分类的排序
	if ($('#categories_table tbody').length) {
		$('#categories_table tbody').sortable({
			helper: fixHelper,
			axis: 'y',
			cursor: 'move',
			update: function(){
				$('#categories_table tbody tr').removeClass('odd even');
				$('#categories_table tbody tr:odd').addClass('odd');
				$('#categories_table tbody tr:even').addClass('even');

				$.ajax({
					type: 'get',
					url: site_url+'/admin/ajax/sort_categories',
					data: $('#categories_table tbody').sortable('serialize'),
					success: function(msg) {
						if (msg > 0) {
							$('#notify').html('<p class="success">分类排序已更新</p>').show().delay(2000).fadeOut(3000);
						}
					}
				});
			}
		}).disableSelection();
	}

	// 主题修改页面的目录树
	if ($('#browser').length) {
		$("#browser").treeview({
			animated:"fast",
			collapsed:true,
			unique:true
		});
	}

	/*
	// 主题修改页面的代码编辑框tab插件
	if ($('#file-content').length) {
		$('#file-content').tabby();
	}*/

	// 表格的悬浮变色
	$('table.manage tr').hover(function(){
		$(this).find('a.hidden').show();
		$(this).addClass('hover');
	}, function(){
		$(this).find('a.hidden').hide();
		$(this).removeClass('hover');
	});

	// 全选
	$('a.select-all').click(function(){
		$('form :checkbox').attr('checked', 'checked');
	});

	// 不选
	$('a.select-none').click(function(){
		$('form :checkbox').attr('checked', '');
	});

	// 提交按钮
	$('a.select-submit').click(function(){
		var b = $(this).attr('lang');
		var a = b ? confirm(b) : true;
		if (a) {
			var c = $('form.operate-form input[name="op"]').val($(this).attr('rel'));
			$('form.operate-form').submit();
			//alert($('form.operate-form').serialize());
		}
	});

	// 文章保存
	$('form#write button#button1').click(function(){
		$('input[name="status"]').val('draft');
		$('form#write').submit();
	});

	// 快速发布文章保存
	$('form#fast_write button#button1').click(function(){
		$('input[name="status"]').val('draft');
		$('form#fast_write').submit();
	});

	// Navigation类型选择
	$('form.meta_form select#type').change(function(){
		$('form.meta_form li.type_url').hide();
		$('form.meta_form li.type_uri').hide();
		$('form.meta_form li.type_page').hide();

		if ($(this).val() == 'url') {
			$('form.meta_form li.type_url').show();
		} else if ($(this).val() == 'uri') {
			$('form.meta_form li.type_uri').show();
		} else if ($(this).val() == 'page') {
			$('form.meta_form li.type_page').show();
		}
	});

	$('form#settings select#homepage_type').change(function(){
		if ($(this).val() == 'posts') {
			$('form#settings select#homepage_pid').hide();
		} else if ($(this).val() == 'homepage') {
			$('form#settings select#homepage_pid').show();
		}
	});

	$('#trackback-menu').click(function() {
		if ($(this).hasClass('display')) {
			$('#trackback').hide();
			$(this).removeClass('display');
		} else {
			editAreaLoader.toggle('css', 'off');
			$('#css').hide();
			$('#css-menu').removeClass('display');
			editAreaLoader.toggle('js', 'off');
			$('#js').hide();
			$('#js-menu').removeClass('display');
			$('#trackback').show();
			$(this).addClass('display')
		}
	});

	$('#js-menu').click(function() {
		if ($(this).hasClass('display')) {
			editAreaLoader.toggle('js', 'off');
			$('#js').hide();
			$(this).removeClass('display');
		} else {
			$('#trackback').hide();
			$('#trackback-menu').removeClass('display');
			editAreaLoader.toggle('css', 'off');
			$('#css').hide();
			$('#css-menu').removeClass('display');
			$('#js').show();
			editAreaLoader.toggle('js', 'on');
			$(this).addClass('display')
		}
	});

	$('#css-menu').click(function() {
		if ($(this).hasClass('display')) {
			editAreaLoader.toggle('css', 'off');
			$('#css').hide();
			$(this).removeClass('display');
		} else {
			$('#trackback').hide();
			$('#trackback-menu').removeClass('display');
			editAreaLoader.toggle('js', 'off');
			$('#js').hide();
			$('#js-menu').removeClass('display');
			$('#css').show();
			editAreaLoader.toggle('css', 'on');
			$(this).addClass('display')
		}
	});
});


