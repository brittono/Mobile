<?php

include_once('../api/comment.inc');

$action= "";

extract($_POST);

$comment= new Comment();

switch($action)
{
	case 'Load':
		$comment->FetchComments($article_id, $enc_type);
		break;
	case 'Add':
		$comment->AddComment($article_id, $comment_email, $comment_intro, $comment_text, $comment_image, "json");
		break;
	case 'Count':
		$comment->FetchCommentCount($page_id, "json");
		break;
}

?>
