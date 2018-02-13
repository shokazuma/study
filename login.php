<?php
session_start();

$dsn = "mysql:host=153.126.185.110;dbname=bss;charset=utf8";
$dbUser = "root";
$dbPass= "";

$errorMessage = "";

if ( isset( $_POST["form1"] ) ){
    if ( !isset( $_POST["userid"] ) ){
        $errorMessage = "ユーザー名が未入力です。";
    } else if ( !isset( $_POST["password"] ) ){
        $errorMessage = "パスワードが未入力です。";
    }
}
$rowCount = 0;
if ( isset( $_POST["userid"] ) && isset( $_POST["password"] ) ) {
    try{
        $pdo = new pdo( $dsn,$dbUser,$dbPass );
        $pdo -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $pdo -> setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
        $stmt = $pdo->prepare( "SELECT * FROM users where name = ? and password = ?" );
        $stmt -> execute( array($_POST["userid"], $_POST["password"] ) );
        $count = $stmt -> rowCount();
    } catch ( PDOException $e ) {
        $errorMessage = "データベースエラー";
    }
    if ( $count == 1 ){
        $_SESSION["name"] = $_POST["userid"];
        header( "Location: main.php" );
        exit();
    } else {
        $errorMessage = "ユーザー名、または、パスワードに誤りがあります。";
    }
}
?>
<?php
function displayError( $message ) {
?>
    <script type="text/javascript">
        confirm("<?php echo $message; ?>");
    </script>
<?php
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>0.02ちゃんねる</title>
        <link rel="stylesheet" type="text/css" href="./css/login.css">
        <?php
        if( !empty( $errorMessage ) ){
            displayError( $errorMessage );
        }
        ?>
    </head>
    <body>
        <div id="login_pane">
            <div id="login_title">
                <h1>BBS Light</h1>
            </div>
            <form name="form1" method="POST" action="">
                <div id="login_main">
                    <p>
                        <label for="userid">ユーザー名：</label><input type="text" id="userid" name="userid" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES, 'UTF-8');} ?>">
                    </p>
                    <p>
                        <label for="password">パスワード：</label><input type="password" id="password" name="password" value="">
                    </p>
                    <button class="login_button" id="login" name="login" onclick="document.form1.submit();">
                        ログイン
                    </button>
                </div>
            </form> 
        </div>
    </body>
</html>