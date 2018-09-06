<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}

$type=$_GET['type'];
$tid=intval($_GET['id']);
$error=false;
$msg=false;
$tag=array();

if($type==='delete'){
	$stmt = $db->prepare("UPDATE tags_en SET isparent=0 WHERE id=:id");
	$stmt->bindValue(':id', $tid, PDO::PARAM_INT);
	$stmt->execute();
	
	header('Location: '.$settings['siteurl'].'/'.$settings['adminfolder'].'/category.php?msg=delete');
	die();
	
}else{
	$stmt = $db->prepare("SELECT id,name,seodesc FROM tags_en WHERE id = :id LIMIT 1");
	$stmt->bindValue(':id', $tid, PDO::PARAM_INT);
	$stmt->execute();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$tag=$results[0];	
}

if($_POST){
	$name=$_POST['name'];
	$desc = $_POST['descc'];
	if(!$name || strlen($name)<2){
		$error="Error: Name must be at least 2 characters";	
	}else{
		$tag=strtolower(trim($name));
		$tag=trim(str_replace('-',' ',$tag));
		$tag=preg_replace("/[^A-Za-z0-9 ]/", "", $tag);
		$tag=trim(str_replace(' games','',$tag));
		$tag=trim(str_replace(' ','-',$tag));
		$name=trim(str_replace('-games','',$tag));
		
		$stmt = $db->prepare("UPDATE tags_en SET name=:name,seodesc=:descc WHERE id=:id");
		$stmt->bindValue(':name', $name, PDO::PARAM_STR);
		$stmt->bindValue(':descc', $desc, PDO::PARAM_STR);
		$stmt->bindValue(':id', $tid, PDO::PARAM_INT);
		$stmt->execute();
		
		header('Location: '.$settings['siteurl'].'/'.$settings['adminfolder'].'/category.php?msg=edit');
		die();
	}
}

$smarty->assign('msg',$msg,true); 
$smarty->assign('errors',$error,true); 
$smarty->assign('tag',$tag,true);
$smarty->assign('page','cats',true);
$smarty->display('category_edit.tpl');
?>