jQuery(document).snowfall({
	flakeCount : 30, //量、数
	flakeColor : '#FFF', //色
	flakeIndex : 1, //重なり
	minSize : 1, //最小サイズ
	maxSize : 3, //最大サイズ
	minSpeed : 1, //最低スピード
	maxSpeed : 1, //最大スピード
	round : true, //形を丸くする
	shadow : false //形に影をつける
});
$(function () {
    $("#includedHeader").load("header.html");
    $("#includedMovie").load("movie.html");
    $("#IncludedButton-6").load("button-6.html");
    $("#IncludedButton-shiryou").load("button-shiryou.html");
});
