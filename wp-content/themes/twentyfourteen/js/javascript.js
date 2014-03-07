$(document).ready(function() {
	var height = $(".photo-overlay").height();
    var style = {'margin-top': (- height / 2)};

    $('.photo-overlay').css(style);
});