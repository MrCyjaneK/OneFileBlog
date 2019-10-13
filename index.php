<?php
/********************/
/*Change line below */
/********************/
$password = "admin";
/********************/
/*Load all varibles */
/********************/
if (!file_exists("./config.json")) {
	file_put_contents("./config.json", "{
	\"blog_name\": \"OneFileBlog\",
	\"blog_bio\": \"Minimal blogging engine\",
	\"started_year\": \"2019\",
	\"host\": \"http://127.0.0.1\"
}");
	die("config.json created, edit password and config now.");
}
$config = json_decode(file_get_contents("./config.json"));
if (empty(scandir("./data"))) {
	mkdir("./data");
}
$path = "";
$pwd = "";
foreach ($_GET as $key => $value) {
	$path = $key;
	$pwd = $value;
	break;
}
if ($path == "") {
	ob_start();
    header('Location: '.$config->host."?/&".$pwd);
    ob_end_flush();
    die();
}
if (substr($path, -2) == "__") {
	ob_start();
	$newpath = "";
	$expl = explode("/", $path);
	print_r($expl);
	unset($expl[count($expl)-1]);
	unset($expl[count($expl)-1]);
	print_r($expl);
	//die();
	$newpath = join("/",$expl);
    header('Location: '.$config->host."?".$newpath."&".$pwd);
    ob_end_flush();
    die();
}
$path = str_replace("//", "/", $path);
$admin = false;
if ($pwd == $password) {
	$admin = true;
}
?>
<?php
/********************/
/*  Css for website */ 
/********************/
?>
<style>
	@import url('https://fonts.googleapis.com/css?family=Roboto');
	body{
	    margin: 0;
	    padding: 0;
	    font-family: 'Roboto', sans-serif;
	    background-color: #101214;
	    color: #7A7C80;
	}
	h2,.white{
	    color: #fff;
	}
	a{
    	color: #7A7C80;
    	text-decoration: none;
	}
	.section-1{
    	padding-top: 10vh;
    	text-align: center;
	}
	.section-1 p{
	    font-size: 1.1rem;
	    padding-bottom: 10px;
	    margin:0;
	}	
	.section-1 h2{
    	font-size: 1.7rem;
    	margin-bottom: 10px;
	}
	.section-1 a{
    	font-size: 1.1rem;
   		padding: 10px;
	}
</style>
<?php
/********************/
/*Content of website*/
/********************/
if ($admin && !isset($_GET['post'])) {
	echo "
		<script>
			post = prompt('What would you like to post?');
			prompt('Ok, visit link below to post.', '".$config->host."?/=".$pwd."&post='+encodeURIComponent(post))
		</script>
	";
}
?>
<div class="section-1">
    <i class="fas fa-code fa-5x white"></i>
    <h2 id="blog_name"><?php echo htmlspecialchars($config->blog_name); ?></h2>
    <p id="blog_bio"><?php echo htmlspecialchars($config->blog_bio); ?></p>
    <?php
    $posts = [];
    foreach(scandir("./data".$path) as $dir) {
    	if ($dir == '.') {
    		continue;
    	}
    	if (substr($dir, -4) == ".txt") {
    		$posts[] = $dir;
    		continue;
    	}
    	echo '<br /><a href="'.$config->host."/?".$path."/".$dir."&".$pwd.'">'.$dir.'</a>';
    }
    foreach ($posts as $post) {
    	echo "<br />######### - $post</br>";
    	echo file_get_contents("./data".$path."/".$post);
    }
    if ($admin && isset($_GET['post'])) {
    	mkdir("./data/".date("Y")."/".date('m')."/".date("d")."/",0777,true);
	    file_put_contents("./data/".date("Y")."/".date('m')."/".date("d")."/".date('H:i').".txt",$_GET['post']);
	}
    ?>
</div>
&copy; <?php 
$copyYear = $config->started_year;
$curYear = date('Y'); 
echo $copyYear . (($copyYear != $curYear) ? '-' . $curYear : '')." ".$config->blog_name;