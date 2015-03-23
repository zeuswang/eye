<?php 
	$code=isset($_GET["code"])?($_GET["code"]):null;
	echo "-------$code<br>\n";
?>

<form>
	input_php_code <input type=text style='width:1000px;height:500px;' name="code" value="<?php echo $code; ?>">
	<input type=submit value="submit">
</form>

<?php
	$arr=Array(1,2,3,4,5,6);
	if($code==null){
		$code="echo 1000000000000;";
	}
	echo "code=$code, eval(code)=";
	echo eval($code);
?>
