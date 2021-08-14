<?php

//職種 - ラジオボタン選択肢
$Occupation = array();
$Occupation[1] = '歯科衛生士（正社員）';
$Occupation[2] = '歯科衛生士（パートタイマー）';
$Occupation[3] = '歯科助手、受付（正社員）';


session_start();
$mode = 'input';
$errmessage = array();


if( isset($_POST['back']) && $_POST['back'] ){
	// 戻るボタンを押した時
	// 何もしない
} else if( isset($_POST['confirm']) && $_POST['confirm'] ){
	// 確認ボタンを押した時

	//希望職種を選択なかった場合
	if(!isset($_POST['occupation']) || !$_POST['occupation']) {
		$errormessage[] = "希望職種を選んでください";
	}else if($_POST['occupation'] <= 0 || $_POST['occupation'] >= 3) {
		$errormessage[] = "希望職種が不正です";
	}
    if(isset($_POST['occupation'])) {
		// XSS攻撃対策 - session変数を使ってサーバーに保存　
		$_SESSION['occupation'] = htmlspecialchars($_POST['occupation'], ENT_QUOTES);
	}
	
	

	// 名前が空欄の場合
	if( !$_POST['fullname'] ) {
		$errmessage[] = "名前を入力してください";
	// 名前が100文字以上の場合
	} else if( mb_strlen($_POST['fullname']) > 100 ){
		$errmessage[] = "名前は100文字以内にしてください";
	}
	// XSS攻撃対策 - session変数を使ってサーバーに保存
	$_SESSION['fullname']	= htmlspecialchars($_POST['fullname'], ENT_QUOTES);


	// ふりがなが空欄の場合
	if( !$_POST['katakana'] ) {
		$errmessage[] = "フリガナを入力してください";
	// ふりがなが100文字以上の場合
	} else if( mb_strlen($_POST['katakana']) > 100 ){
		$errmessage[] = "フリガナは100文字以内にしてください";
	}
	// XSS攻撃対策 - session変数を使ってサーバーに保存
	$_SESSION['katakana']	= htmlspecialchars($_POST['katakana'], ENT_QUOTES);



	// 最終学歴が空欄の場合
	if( !$_POST['gakureki'] ) {
		$errmessage[] = "最終学歴を入力してください";
	// 最終学歴が100文字以上の場合
	} else if( mb_strlen($_POST['gakureki']) > 100 ){
		$errmessage[] = "最終学歴は100文字以内にしてください";
	}
	// XSS攻撃対策 - session変数を使ってサーバーに保存
	$_SESSION['gakureki']	= htmlspecialchars($_POST['gakureki'], ENT_QUOTES);

	
	// 生年月日が空欄の場合
	if( !$_POST['date'] ) {
		$errmessage[] = "生年月日を入力してください";
	//日が2文字以上の場合
	} else if( mb_strlen($_POST['date']) > 100 ){
		$errmessage[] = "正しい日を入力してください";
	}
	//XSS攻撃対策 - session変数を使ってサーバーに保存
	$_SESSION['date']	= htmlspecialchars($_POST['date'], ENT_QUOTES);



	// 電話番号が空欄の場合
	if( !$_POST['telephone'] ) {
		$errmessage[] = "電話番号を入力してください";
	//電話番号が200文字以上の場合
	} else if( mb_strlen($_POST['telephone']) > 11 ){
		$errmessage[] = "正しい電話番号を入力してください";
	}
	//XSS攻撃対策 - session変数を使ってサーバーに保存
	$_SESSION['telephone']	= htmlspecialchars($_POST['telephone'], ENT_QUOTES);

    

	// emialが空欄の場合
	if( !$_POST['email'] ) {
		$errmessage[] = "Eメールを入力してください";
	//emailが200文字以上の場合
	} else if( mb_strlen($_POST['email']) > 200 ){
		$errmessage[] = "Eメールは200文字以内にしてください";
	//emial型式かどうかーもしだったらtrue
	} else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
		$errmessage[] = "メールアドレスが不正です";
	}
	//XSS攻撃対策 - session変数を使ってサーバーに保存
	$_SESSION['email']	= htmlspecialchars($_POST['email'], ENT_QUOTES);



    
	// //お問い合わせ内容が空欄の場合
	// if( !$_POST['message'] ){
	// 	$errmessage[] = "お問い合わせ内容を入力してください";
	//お問い合わせ内容が500文字以上の場合
	if( mb_strlen($_POST['message']) > 500 ){
		$errmessage[] = "お問い合わせ内容は500文字以内にしてください";
	}
	//XSS攻撃対策 - session変数を使ってサーバーに保存
	$_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

	// mode変数
	if( $errmessage ){
		$mode = 'input';
	} else {
	//   CSRF対策 - トークンを生成
	  $token = bin2hex(random_bytes(32));

	  $_SESSION['token']  = $token;
		$mode = 'confirm';
	}

} else if( isset($_POST['send']) && $_POST['send'] ){
	// 送信ボタンを押したとき
	// トークンが一致しなかった場合
  if( !$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email'] ){
	  $errmessage[] = '不正な処理が行われました';
	// 初期化
	  $_SESSION     = array();
	  $mode         = 'input';
  } else if( $_POST['token'] != $_SESSION['token'] ){
    $errmessage[] = '不正な処理が行われました';
	// 初期化
    $_SESSION     = array();
    $mode         = 'input';
  } else {
	  $message = "お問い合わせを受け付けました \r\n"
	             . "職種:  " . $Occupation[$_SESSION['occupation']] . "\r\n"
	             . "名前: " . $_SESSION['fullname'] . "\r\n"
				 . "フリガナ:" . $_SESSION['katakana'] . "\r\n"
				 . "最終学歴:" . $_SESSION['gakureki'] . "\r\n"
				 . "生年月日:" . $_SESSION['date'] . "\r\n"
				 . "電話番号:" . $_SESSION['telephone'] . "\r\n"
	             . "email: " . $_SESSION['email'] . "\r\n"
	             . "お問い合わせ内容:\r\n"
	             . preg_replace( "/\r\n|\r|\n/", "\r\n", $_SESSION['message'] );
	  mail( $_SESSION['email'], 'お問い合わせありがとうございます', $message );
	  mail( 'taro4no1@gmail.com', 'お問い合わせありがとうございます', $message );
	  // 初期化
	  $_SESSION = array();
	  // mode変数
	  $mode     = 'send';
  }
} else {
	$_SESSION['occupation'] = "";
	$_SESSION['fullname'] = "";
	$_SESSION['katakana'] = "";
	$_SESSION['gakureki'] = "";
	$_SESSION['date'] = "";
	$_SESSION['telephone'] = "";
	$_SESSION['email']    = "";
	$_SESSION['message']  = "";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>お問い合わせフォーム</title>
  <link rel="stylesheet" href="cmn/css/phpstyle.css">
</head>
<body>
<?php if( $mode == 'input' ){ ?>
  <!-- POSTの時 - 入力画面 -->
	<?php
	if( $errmessage ){
		echo '<div style = "color:red;">';
		echo implode('<br>', $errmessage );
		echo '</div>';
	}
	?>
　
  <form action="./contactform.php" method="post">
    職種テストです
    <?php foreach( $Occupation as $i => $v) { ?>
	    <?php if($_SESSION['occupation'] == $i){ ?>
    		<label><input type = "radio" name = "occupation" value="<?php echo $i ?>" checked ><?php echo $v ?></label><br>
		<?php } else { ?>
			<label><input type = "radio" name = "occupation" value="<?php echo $i ?>"><?php echo $v ?></label><br>
		<?php } ?>
	<?php } ?>
    名前    <input type="text"    name="fullname" value="<?php echo $_SESSION['fullname'] ?>" ><br>
	フリガナ <input type="text"   name ="katakana" value="<?php echo $_SESSION['katakana'] ?>"><br>
	最終学歴 <input type="text"   name="gakureki" value="<?php echo $_SESSION['gakureki'] ?>"><br>
	生年月日<input type="date"   name="date"   value="<?php echo $_SESSION['date'] ?>"><br>
	電話番号 <input type="tel"    name="telephone" value="<?php echo $_SESSION['telephone'] ?>"><br>
    Eメール <input type="email"   name="email" value="<?php echo $_SESSION['email'] ?>"  ><br>
    お問い合わせ内容<br>
    <textarea cols="40" rows="8" name="message" ><?php echo $_SESSION['message'] ?></textarea><br>
    
      
    <input type="submit" name="confirm" value="確認" />
  </form>


<?php } else if( $mode == 'confirm' ){ ?>
  <!-- GETの時 - 確認画面 -->
  <form action="./contactform.php" method="post">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
	職種    <?php echo $Occupation[$_SESSION['occupation']] ?><br>
    名前    <?php echo $_SESSION['fullname'] ?><br>
	ふりがな<?php echo $_SESSION['katakana'] ?><br>
    最終学歴<?php echo $_SESSION['gakureki'] ?><br>
	生年月日<?php echo $_SESSION['date'] ?><br>
	電話番号<?php echo $_SESSION['telephone'] ?><br>
    Eメール <?php echo $_SESSION['email'] ?><br>
    お問い合わせ内容<br>
	  <?php echo nl2br($_SESSION['message']) ?><br>
    <input type="submit" name="back" value="戻る" />
    <input type="submit" name="send" value="送信" />
  </form>

<?php } else { ?>
  <!-- 完了画面 -->
  送信しました。お問い合わせありがとうございました。<br>
<?php } ?>
</body>
</html>


