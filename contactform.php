<?php
session_start();
$mode = 'input';
$errmessage = array();

try {
    // Define your database credentials
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'ynF8cwmT-';
    $db_name = 'php';


    // Connect to the database using PDO
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['back']) && $_POST['back']) {
        // Do nothing
    } elseif (isset($_POST['confirm']) && $_POST['confirm']) {
        // Confirmation screen
        if (!$_POST['fullname']) {
            $errmessage['fullname'] = "名前を入力してください";
        } elseif (mb_strlen($_POST['fullname']) > 20) {
            $errmessage['fullname'] = "名前は1文字以上、20文字以内にしてください";
        }
        $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

        if (!$_POST['email']) {
            $errmessage['email'] = "Eメールを入力してください";
        } elseif (mb_strlen($_POST['email']) > 320) {
            $errmessage['email'] = "Eメールは320文字以内にしてください";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errmessage['email'] = "メールアドレスが不正です";
        }
        $_SESSION['email'] = htmlspecialchars($_POST['email'], ENT_QUOTES);

        if (!$_POST['message']) {
            $errmessage['message'] = "お問い合わせ内容を入力してください";
        } elseif (mb_strlen($_POST['message']) > 500) {
            $errmessage['message'] = "お問い合わせ内容は500文字以内にしてください";
        }
        $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

        if (!$_POST['kana']) {
            $errmessage['kana'] = "フリガナを入力してください";
        } elseif (mb_strlen($_POST['kana']) > 20) {
            $errmessage['kana'] = "フリガナは20文字以内にしてください";
        }
        $_SESSION['kana'] = htmlspecialchars($_POST['kana'], ENT_QUOTES);

        if (!$_POST['gender']) {
            $errmessage['gender'] = "性別を入力してください";
        }
        $_SESSION['gender'] = htmlspecialchars($_POST['gender'], ENT_QUOTES);


        if (!$_POST['tel']) {
            $errmessage['tel'] = "電話番号を入力してください";
        } elseif (mb_strlen($_POST['tel']) > 11) {
            $errmessage['tel'] = "電話番号は11文字以内にしてください";
        }
        $_SESSION['tel'] = htmlspecialchars($_POST['tel'], ENT_QUOTES);


        $_SESSION['grade'] = htmlspecialchars($_POST['grade'], ENT_QUOTES);

        if (!$_POST['gakkou']) {
            $errmessage['gakkou'] = "学校名を入力してください";
        } elseif (mb_strlen($_POST['gakkou']) > 100) {
            $errmessage['gakkou'] = "学校名は100文字以内にしてください";
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
            $errmessage['token'] = '不正な処理が行われました';
            $_SESSION = array();
            $mode = 'input';
        } elseif ($_POST['token'] != $_SESSION['token']) {
            $errmessage['token'] = '不正な処理が行われました';
            $_SESSION = array();
            $mode = 'input';
        } else {
            // Insert data into the database
            $stmt = $pdo->prepare("INSERT INTO contact (fullname, email, message,tel,grade,gakkou,kana,gender) VALUES (?, ?, ?,?,?,?,?,?)");
            $stmt->execute(
                array(
                    $_SESSION['fullname'],
                    $_SESSION['email'],
                    $_SESSION['message'],
                    $_SESSION['tel'],
                    $_SESSION['grade'],
                    $_SESSION['gakkou'],
                    $_SESSION['kana'],
                    $_SESSION['gender']
                )
            );


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
    <meta charset="UTF-8" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>総合制作</title>
    <link href="css/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/ham.css" />
    <meta name="google-site-verification" content="jZK94A-9ZnrZV0jVGYO72HrycaLX80ZacUyzQbxPLEI" />
    <script type="text/javascript" src="js/snowfall.min.jquery.js"></script>
</head>

<body>
    <div id="includedHeader"></div>
    <div id="includedMovie"></div>
    <div class="wrapper">
        <main>


            <?php if ($mode == 'input') { ?>
                <!-- 入力画面 -->

                <form action="./contactform.php" method="post">
                    <?php
                    if ($errmessage && isset($errmessage['token'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $errmessage['token'];
                        echo '</div>';
                    } ?>

                    <div class="form-group row justify-content-center">
                        <label for="fullname" class="col-md-3 col-form-label text-md-right">お名前<span class="text-danger"
                                style="margin-left: 5px;">必須</span></label>
                        <div class="col-md-6">
                            <input type="text" name="fullname" value="<?php echo $_SESSION['fullname'] ?>"
                                class="form-control">
                        </div>
                    </div>

                    <br>
                    <?php
                    if ($errmessage && isset($errmessage['fullname'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $errmessage['fullname'];
                        echo '</div>';
                    } ?>
                    <div class="form-group row justify-content-center">
                        <label for="kana" class="col-md-3 col-form-label text-md-right">フリガナ<span class="text-danger"
                                style="margin-left: 5px;">必須</span></label>
                        <div class="col-md-6">
                            <input type="text" name="kana" value="<?php echo $_SESSION['kana'] ?>" class="form-control">
                        </div>
                    </div>
                    <br>
                    <?php
                    if ($errmessage && isset($errmessage['kana'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $errmessage['kana'];
                        echo '</div>';
                    } ?>

                    <div class="form-group row justify-content-center">
                        <label for="gender" class="col-md-3 col-form-label text-md-right">性別<span class="text-danger"
                                style="margin-left: 5px;">必須</span></label>
                        <div class="col-md-6">
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
                        </div>
                    </div>
                    <br>
                    <?php
                    if ($errmessage && isset($errmessage['gender'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $errmessage['gender'];
                        echo '</div>';
                    } ?>
                    <div class="form-group row justify-content-center">
                        <label for="email" class="col-md-3 col-form-label text-md-right">Eメール<span class="text-danger"
                                style="margin-left: 5px;">必須</span></label>
                        <div class="col-md-6">
                            <input type="text" name="email" value="<?php echo $_SESSION['email'] ?>" class="form-control">
                        </div>
                    </div>
                    <br>
                    <?php
                    if ($errmessage && isset($errmessage['email'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $errmessage['email'];
                        echo '</div>';
                    } ?>
                    <div class="form-group row justify-content-center">
                        <label for="tel" class="col-md-3 col-form-label text-md-right">電話番号<span class="text-danger"
                                style="margin-left: 5px;">必須</span></label>
                        <div class="col-md-6">
                            <input type="text" name="tel" value="<?php echo $_SESSION['tel'] ?>" class="form-control">
                        </div>
                    </div>
                    <br>
                    <?php
                    if ($errmessage && isset($errmessage['tel'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $errmessage['tel'];
                        echo '</div>';
                    } ?>
                    <div class="form-group row justify-content-center">
                        <label for="grade" class="col-md-3 col-form-label text-md-right">現在の学年など選択してください<span
                                class="text-danger" style="margin-left: 5px;">必須</span></label>
                        <div class="col-md-6">
                            <select class="form-control" id="gradeSelect" name="grade">
                                <option value="1" <?php if (isset($_SESSION['grade']) && $_SESSION['grade'] == '1')
                                    echo 'selected'; ?>>1年生
                                </option>
                                <option value="2" <?php if (isset($_SESSION['grade']) && $_SESSION['grade'] == '2')
                                    echo 'selected'; ?>>2年生
                                </option>
                                <option value="3" <?php if (isset($_SESSION['grade']) && $_SESSION['grade'] == '3')
                                    echo 'selected'; ?>>3年生
                                </option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row justify-content-center">
                        <label for="gakkou" class="col-md-3 col-form-label text-md-right">学校名<span class="text-danger"
                                style="margin-left: 5px;">必須</span></label>
                        <div class="col-md-6">
                            <input type="text" name="gakkou" placeholder="abc高校" value="<?php echo $_SESSION['gakkou'] ?>"
                                class="form-control">
                        </div>
                    </div>
                    <br>
                    <?php
                    if ($errmessage && isset($errmessage['gakkou'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $errmessage['gakkou'];
                        echo '</div>';
                    } ?>

                    <div class="form-group row justify-content-center">
                        <label for="message" class="col-md-3 col-form-label text-md-right">お問い合わせ内容<span class="text-danger"
                                style="margin-left: 5px;">必須</span></label>
                        <div class="col-md-6">
                            <textarea cols="40" rows="8" name="message"
                                class="form-control"><?php echo $_SESSION['message'] ?></textarea>
                        </div>
                    </div>
                    <br>
                    <?php
                    if ($errmessage && isset($errmessage['message'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $errmessage['message'];
                        echo '</div>';
                    } ?>
                    <div class="button">
                        <input type="submit" name="confirm" value="確認" class="btn btn-primary btn-lg" />
                    </div>
                </form>
            <?php } else if ($mode == 'confirm') { ?>
                    <!-- 確認画面 -->
                    <form action="./contactform.php" method="post">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                        <div class="form-group row justify-content-center">
                            <label for="fullname" class="col-md-3 col-form-label text-md-right">お名前<span class="text-danger"
                                    style="margin-left: 5px;">必須</span></label>
                            <div class="col-md-6">
                            <?php echo $_SESSION['fullname'] ?>
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <label for="kana" class="col-md-3 col-form-label text-md-right">フリガナ<span class="text-danger"
                                    style="margin-left: 5px;">必須</span></label>
                            <div class="col-md-6">
                            <?php echo $_SESSION['kana'] ?>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label for="gender" class="col-md-3 col-form-label text-md-right">性別<span class="text-danger"
                                    style="margin-left: 5px;">必須</span></label>
                            <div class="col-md-6">
                            <?php if ($_SESSION['gender'] == "male") {
                                echo "男性";
                            } else {
                                echo "女性";
                            } ?>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Eメール<span class="text-danger"
                                    style="margin-left: 5px;">必須</span></label>
                            <div class="col-md-6">
                            <?php echo $_SESSION['email'] ?>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label for="tel" class="col-md-3 col-form-label text-md-right">電話番号<span class="text-danger"
                                    style="margin-left: 5px;">必須</span></label>
                            <div class="col-md-6">
                            <?php echo $_SESSION['tel'] ?>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label for="grade" class="col-md-3 col-form-label text-md-right">学年<span class="text-danger"
                                    style="margin-left: 5px;">必須</span></label>
                            <div class="col-md-6">
                            <?php if ($_SESSION['grade'] == "1") {
                                echo "1年生";
                            } elseif ($_SESSION['grade'] == "2") {
                                echo "2年生";
                            } elseif ($_SESSION['grade'] == "3") {
                                echo "3年生";
                            } ?>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label for="gakkou" class="col-md-3 col-form-label text-md-right">学校名<span class="text-danger"
                                    style="margin-left: 5px;">必須</span></label>
                            <div class="col-md-6">
                            <?php echo $_SESSION['gakkou'] ?>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label for="message" class="col-md-3 col-form-label text-md-right">お問い合わせ内容<span class="text-danger"
                                    style="margin-left: 5px;">必須</span></label>
                            <div class="col-md-6">
                            <?php echo $_SESSION['message'] ?>
                            </div>
                        </div>

                        <input type="submit" name="back" value="戻る" class="btn btn-primary btn-lg" />
                        <input type="submit" name="send" value="送信" class="btn btn-primary btn-lg" />
                    </form>
            <?php } else { ?>
                    <!-- 完了画面 -->
                    <div class="mt-5 mb-5">
                        <h2>お問い合わせが完了しました。</h2>
                        <br><br>
                        <p style="text-align: center;">お問い合わせいただきありがとうございました。</p>
                    </div>
            <?php } ?>




        </main>
    </div>
    <div class="hr"></div>

    <footer>
        <p>Copyright©Sapporo Joho Mirai</p>
    </footer>
    <script src="js/script.js"></script>

</body>

</html>