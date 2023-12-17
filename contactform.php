<?php
session_start();
$mode = 'input';
$errmessage = array();

try {
    // Define your database credentials
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'php';


    // Connect to the database using PDO
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['back']) && $_POST['back']) {
        // Do nothing
    } elseif (isset($_POST['confirm']) && $_POST['confirm']) {
        // Confirmation screen
        if (!$_POST['fullname']) {
            $errmessage[] = "名前を入力してください";
        } elseif (mb_strlen($_POST['fullname']) > 100) {
            $errmessage[] = "名前は100文字以内にしてください";
        }
        $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

        if (!$_POST['email']) {
            $errmessage[] = "Eメールを入力してください";
        } elseif (mb_strlen($_POST['email']) > 200) {
            $errmessage[] = "Eメールは200文字以内にしてください";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errmessage[] = "メールアドレスが不正です";
        }
        $_SESSION['email'] = htmlspecialchars($_POST['email'], ENT_QUOTES);

        if (!$_POST['message']) {
            $errmessage[] = "お問い合わせ内容を入力してください";
        } elseif (mb_strlen($_POST['message']) > 500) {
            $errmessage[] = "お問い合わせ内容は500文字以内にしてください";
        }
        $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

        if (!$_POST['kana']) {
            $errmessage[] = "フリガナを入力してください";
        } elseif (mb_strlen($_POST['kana']) > 100) {
            $errmessage[] = "フリガナは100文字以内にしてください";
        }
        $_SESSION['kana'] = htmlspecialchars($_POST['kana'], ENT_QUOTES);

        if (!$_POST['gender']) {
            $errmessage[] = "性別を入力してください";
        }
        $_SESSION['gender'] = htmlspecialchars($_POST['gender'], ENT_QUOTES);

        if (!$_POST['tel']) {
            $errmessage[] = "電話番号を入力してください";
        } elseif (mb_strlen($_POST['tel']) > 100) {
            $errmessage[] = "電話番号は100文字以内にしてください";
        }
        $_SESSION['tel'] = htmlspecialchars($_POST['tel'], ENT_QUOTES);


        $_SESSION['grade'] = htmlspecialchars($_POST['grade'], ENT_QUOTES);

        if (!$_POST['gakkou']) {
            $errmessage[] = "学校名を入力してください";
        } elseif (mb_strlen($_POST['gakkou']) > 100) {
            $errmessage[] = "学校名は100文字以内にしてください";
        }
        $_SESSION['gakkou'] = htmlspecialchars($_POST['gakkou'], ENT_QUOTES);

        if ($errmessage) {
            $mode = 'input';
        } else {
            $token = bin2hex(random_bytes(32)); // php7以降
            $_SESSION['token'] = $token;
            $mode = 'confirm';
        }
    } elseif (isset($_POST['send']) && $_POST['send']) {
        // Send button pressed
        if (!$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email']) {
            $errmessage[] = '不正な処理が行われました';
            $_SESSION = array();
            $mode = 'input';
        } elseif ($_POST['token'] != $_SESSION['token']) {
            $errmessage[] = '不正な処理が行われました';
            $_SESSION = array();
            $mode = 'input';
        } else {
            // Insert data into the database
            $stmt = $pdo->prepare("INSERT INTO boku (fullname, email, message,tel,grade,gakkou,kana,gender) VALUES (?, ?, ?,?,?,?,?,?)");
            $stmt->execute(array($_SESSION['fullname'], $_SESSION['email'], $_SESSION['message'], $_SESSION['tel'],
                $_SESSION['grade'], $_SESSION['gakkou'], $_SESSION['kana'], $_SESSION['gender']));


            $_SESSION = array();
            $mode = 'send';
        }
    } else {
        $_SESSION['fullname'] = "";
        $_SESSION['email'] = "";
        $_SESSION['message'] = "";
        $_SESSION['tel'] = "";
        $_SESSION["gender"] = "";
        $_SESSION["grade"] = "";
        $_SESSION["gakkou"] = "";
        $_SESSION["kana"] = "";
    }



} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} finally {
    $pdo = null; // Close the database connection
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        body {
            padding: 10px;
            max-width: 600px;
            margin: 0px auto;
        }

        div.button {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php if ($mode == 'input') { ?>
        <!-- 入力画面 -->
        <?php
        if ($errmessage) {
            echo '<div class="alert alert-danger" role="alert">';
            echo implode('<br>', $errmessage);
            echo '</div>';
        }
        ?>
        <form action="./contactform.php" method="post">
            名前 <input type="text" name="fullname" value="<?php echo $_SESSION['fullname'] ?>" class="form-control"><br>
            フリガナ <input type="text" name="kana" value="<?php echo $_SESSION['kana'] ?>" class="form-control"><br>
            <label>
                <input type="radio" name="gender" value="male" <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] == 'male')
                    echo 'checked'; ?>>
                男性
            </label>

            <label>
                <input type="radio" name="gender" value="female" <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] == 'female')
                    echo 'checked'; ?>>
                女性
            </label>
            <br>
            Eメール <input type="email" name="email" value="<?php echo $_SESSION['email'] ?>" class="form-control"><br>
            電話番号 <input type="tel" name="tel" value="<?php echo $_SESSION['tel'] ?>" class="form-control"><br>
            現在の学年など選択してください <select class="form-control" id="gradeSelect" name="grade">
                <option value="1" <?php if (isset($_SESSION['grade']) && $_SESSION['grade'] == '1')
                    echo 'selected'; ?>>1年生
                </option>
                <option value="2" <?php if (isset($_SESSION['grade']) && $_SESSION['grade'] == '2')
                    echo 'selected'; ?>>2年生
                </option>
                <option value="3" <?php if (isset($_SESSION['grade']) && $_SESSION['grade'] == '3')
                    echo 'selected'; ?>>3年生
                </option>
            </select><br>
            学校名<input type="text" name="gakkou" placeholder="abc高校" value="<?php echo $_SESSION['gakkou'] ?>"
                class="form-control"><br>
            お問い合わせ内容<br>
            <textarea cols="40" rows="8" name="message"
                class="form-control"><?php echo $_SESSION['message'] ?></textarea><br>
            <div class="button">
                <input type="submit" name="confirm" value="確認" class="btn btn-primary btn-lg" />
            </div>
        </form>
    <?php } else if ($mode == 'confirm') { ?>
            <!-- 確認画面 -->
            <form action="./contactform.php" method="post">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                名前
            <?php echo $_SESSION['fullname'] ?><br>
                Eメール
            <?php echo $_SESSION['email'] ?><br>
                お問い合わせ内容<br>
            <?php echo nl2br($_SESSION['message']) ?><br>
            <?php echo $_SESSION['kana'] ?><br>
                <input type="submit" name="back" value="戻る" class="btn btn-primary btn-lg" />
                <input type="submit" name="send" value="送信" class="btn btn-primary btn-lg" />
            </form>
    <?php } else { ?>
            <!-- 完了画面 -->
            送信しました。お問い合わせありがとうございました。<br>
    <?php } ?>
</body>

</html>