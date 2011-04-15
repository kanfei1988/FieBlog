function reply(elem) {
	var li = $(elem).parents('li').first();
	var id = li.attr('id').substr(8);
	var respond = $('#respond');
	var wrapper = $('#commentbox');
	if ($(elem).text() == '取消回复') {
		$(elem).text('回复');
		wrapper.append(respond);
		$('input[name="parent"]').val(0);
	} else {
		if (respond.parent().is('li')) {
			respond.prev('p').children('a').text('回复');
		}
		$(elem).text('取消回复');
		li.append(respond);
		$('input[name="parent"]').val(id);
	}
}