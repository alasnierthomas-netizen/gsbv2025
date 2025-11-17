<?php
if (!isset($_REQUEST['action']) || empty($_REQUEST['action'])) {
	$action = "listCollaborateurs";
} else {
	$action = $_REQUEST['action'];
}