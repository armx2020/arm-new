$(document).ready(function ($) {
	$.mask.definitions['h'] = "[0|1|2|3|4|5|6|8|9]"

	$(".mask-phone").click(function () {
		$(this).setCursorPosition(3);
	}).mask("+7 (h99) 999-99-99", {autoclear: false});

	$.fn.setCursorPosition = function (pos) {
		if ($(this).get(0).setSelectionRange) {
			$(this).get(0).setSelectionRange(pos, pos);
		} else if ($(this).get(0).createTextRange) {
			var range = $(this).get(0).createTextRange();
			range.collapse(true);
			range.moveEnd('character', pos);
			range.moveStart('character', pos);
			range.select();
		}
	};
});

