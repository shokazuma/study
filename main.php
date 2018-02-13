<?php
session_start();

$dsn = "mysql:host=153.126.185.110;dbname=bss;charset=utf8";
$dbUser = "root";
$dbPass= "";

$errorMessage = "";

$pdo = "";
try{
    $pdo = new pdo( $dsn,$dbUser,$dbPass );
    $pdo -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $pdo -> setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
} catch ( PDOException $e ) {
    $errorMessage = "データベース接続エラー";
}

date_default_timezone_set('Asia/Tokyo');
if ( isset( $_POST["content"] ) ) {
    echo( $_POST["content"] );
    $now = date('Y-m-d H:i:s');
    echo( $now );
    try{
        $stmt = $pdo->prepare( "INSERT INTO content (name, adddate, content) values ( ?, ?, ? )" );
        $stmt -> execute( array( $_SESSION["name"], $now, $_POST["content"]  ) );
        header("Location: " . $_SERVER['PHP_SELF']);
    } catch  ( PDOException $e ) {
        echo($e);
        $errorMessage = "データ追加エラー";
    }    
}

try{
    $stmt = $pdo->prepare( "SELECT * FROM content" );
    $stmt -> execute();
} catch  ( PDOException $e ) {
    $errorMessage = "データ取得エラー";
}

?>
<script type="text/javascript">
(window.onload = function() {
    window.scrollTo(0,document.body.scrollHeight);
})();
</script>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="./css/main.css">
        <title>0.02ちゃんねる</title>
    </head>
    <body>
        <div id="header">
            <h1>BBS Light</h1>
        </div>
        <div id="contents">
            <table>
                <thead>
                    <tr>
                        <th id="no">No</th>
                        <th id="name">ユーザー</th>
                        <th id="date">書き込み日時</th>
                        <th id="content">内容</th>
                    </tr>
                </thead>
                <div id="vline1"></div>
                <div id="vline2"></div>
                <div id="vline3"></div>
            </table>
            <table>
                <tbody>
                    <?php
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td id="no"><?php echo $row["id"] ?></td>
                        <td id="name"><?php echo $row["name"] ?></td>
                        <td id="date"><?php echo $row["adddate"] ?></td>
                        <td id="content"><?php echo $row["content"] ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="footer">
            <form name="form1" method="POST" action="">
                <div id="send_contents">
                    <input type="text" id="content" name="content" value="<?php if (!empty($_POST["content"])) {echo htmlspecialchars($_POST["content"], ENT_QUOTES, 'UTF-8');} ?>">
                    <button class="button" id="send" name="send" onclick="document.form1.submit();">
                        送信
                    </button>
                </div>
            </form> 
        </div>
    </body>
</html>