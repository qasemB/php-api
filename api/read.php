<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once('../core/initialize.php');

$post = new Post($db);

$res = $post->read();

$num = $res->rowCount();

if($num > 0){
   $post_arr = array();
   $post_arr['data'] = array();
   
   while($row=$res->fetch(PDO::FETCH_ASSOC)){
       extract($row);
       $post_item = [
            'id' => $id,
            'title' => $title,
            'body' => $body,
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name
       ];
       array_push($post_arr['data'] , $post_item);
   }
   echo json_encode($post_arr);
}else{
    echo json_encode(array('message' => 'no post found.'));
}



?>