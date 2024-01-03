jQuery(document).snowfall({
	flakeCount: 30, //量、数
	flakeColor: '#FFF', //色
	flakeIndex: 1, //重なり
	minSize: 1, //最小サイズ
	maxSize: 3, //最大サイズ
	minSpeed: 1, //最低スピード
	maxSpeed: 1, //最大スピード
	round: true, //形を丸くする
	shadow: false //形に影をつける
});
$(function () {
	$("#includedHeader").load("header.html");
	$("#includedMovie").load("movie.html");
	$("#IncludedButton-6").load("button-6.html");
	$("#IncludedButton-shiryou").load("button-shiryou.html");
});
$(function () {
	$("#datepicker").datepicker({
		dateFormat: "yy-mm-dd", // 期待される日付のフォーマット
		changeMonth: true, // 月の選択を有効にする
		changeYear: true, // 年の選択を有効にする
		yearRange: "1900:2024" // 選択可能な年の範囲
	});
});


$(document).ready(function () {
	// 郵便番号入力時に住所を取得して自動入力
	$("#postalCode").on("input", function () {
		var postalCode = $(this).val();
		if (postalCode.length === 7) {
			$.ajax({
				url: "https://zipcloud.ibsnet.co.jp/api/search",
				type: "GET",
				data: { zipcode: postalCode },
				dataType: "jsonp",
				success: function (data) {
					if (data.status === 200) {
						$("#prefecture").val(data.results[0].address1);
						$("#city").val(data.results[0].address2);
						$("#address1").val(data.results[0].address3);
					}
				}
			});
		}
	});

	// 入力補助と半角⇔全角揃え
	$("input[type='text']").on("input", function () {
		// 入力補助（英数字・片仮名の半角⇔全角揃え）
		var inputValue = $(this).val();
		inputValue = inputValue.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function (s) {
			return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
		});
		$(this).val(inputValue);
	});
});