document.addEventListener('DOMContentLoaded', function () {
    // Get the current date
    var currentDate = new Date();

    // 4月～5月かどうかをチェックする
    if (currentDate.getMonth() >= 0 && currentDate.getMonth() < 5) {
        //4月以降だったならcssを変更する
        document.getElementById('stylesheet').href = 'css/spring.css';
    }
});