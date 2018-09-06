<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}

$query="";
if(isset($_GET['query'])){
$query=$_GET['query'];
}

if(strlen($query)<2){
$query=null;
}

$msg="";

if($_POST){
	$name=$_POST['name'];
	$desc = $_POST['desc'];
	if(!$name || strlen($name)<2){
		$msg="Error: Have a longer name";
	}else{
		$tag=strtolower(trim($name));
		$tag=trim(str_replace('-',' ',$tag));
		$tag=preg_replace("/[^A-Za-z0-9 ]/", "", $tag);
		$tag=trim(str_replace(' games','',$tag));
		$tag=trim(str_replace(' ','-',$tag));
		$name=trim(str_replace('-games','',$tag));
		
		//check if exists
		$stmt = $db->prepare("SELECT id FROM tags_en WHERE name = :tag LIMIT 1");
		$stmt->bindValue(':tag', $name, PDO::PARAM_STR);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$tag=array();
		$tag['id']=0;
		if($results){
		$tag=$results[0];
		}
		if(!$tag['id'] || $tag['id']<1){
		$stmt = $db->prepare("INSERT IGNORE INTO tags_en (name,seodesc,isparent) VALUES(:field1,:field2,:field3)");
		$stmt->execute(array(':field1' => $name, ':field2' => $desc, ':field3' => 1));
		}else{
		$tagid=$tag['id'];
		$stmt = $db->prepare("UPDATE tags_en SET isparent=1 WHERE id=:id");
		$stmt->bindValue(':id', $tagid, PDO::PARAM_INT);
		$stmt->execute();
		}
		
		$msg="Category added";	
	}
}

if(isset($_GET['msg'])){
$msg=$_GET['msg'];
}
if($msg=='edit'){
$msg="Category has been edited";
}else if($msg=='delete'){
$msg="Category has been deleted";
}

$sort = "name";

if($query){
$sql = "SELECT * FROM tags_en WHERE name LIKE ?";
$params = array("%$query%");
$stmt = $db->prepare($sql);
$stmt->execute($params);
$total_items = $stmt->rowCount();	
}else{
$stmt = $db->query("SELECT * FROM tags_en WHERE isparent=1 ");
$total_items = $stmt->rowCount();
}

$smarty->assign('query',$query,true);
$smarty->assign('total_items',$total_items,true);

if($total_items>0){
$p=1;
if(isset($_GET['p'])){
$p = intval($_GET['p']);
}
if ((!$p) || (is_numeric($p) == false) || ($p < 0) || ($p > $total_items)){
$p = 1;
}

$per_page = 50;
$total_pages = ceil($total_items / $per_page);
if($p>$total_pages){
$p=$total_pages;
}
$page_from=($p - 15 > 0 ? $p - 15 : 1);
$page_to=($p + 15 <= $total_pages ? $p + 15 : $total_pages);
$set_limit = $p * $per_page - ($per_page);
$smarty->assign('p',$p,true);
$smarty->assign('total_pages',$total_pages,true);
$smarty->assign('page_from',$page_from,true);
$smarty->assign('page_to',$page_to,true);

if($query){
$sql = "SELECT t.id,t.name,t.seodesc FROM tags_en t WHERE t.name LIKE ? ORDER BY t.id DESC LIMIT $set_limit, $per_page";
$params = array("%$query%");
$stmt = $db->prepare($sql);
$stmt->execute($params);
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);	
}else{
$stmt = $db->prepare("SELECT t.id,t.name,t.seodesc FROM tags_en t WHERE t.isparent=1 ORDER BY {$sort} ASC LIMIT {$set_limit}, {$per_page}");
$stmt->execute();
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$smarty->assign('games',$content,true);
}
$smarty->assign('msg',$msg,true);
$smarty->assign('page','cats',true);
$smarty->display('category.tpl');
?>