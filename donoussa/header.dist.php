<!DOCTYPE html>
<html lang="<?php print C_HTML_LANG ?>">
<head>
	<meta charset="utf-8">
	<title><?php print gettext($app->page_title) ?></title>
	<meta name="description"
		  content="<?php print gettext($app->page_description) ?>">
	<meta name="viewport"
		  content="width=device-width, initial-scale=1.0">

	<?php print $app->page_depedencies_html ?>

	<script type="text/javascript">
		$.ajaxSetup({
			url: "<?php print C_PROJECT_URL . $app->real_url . '/' ?>"
		});

		<?php if(isset($_SESSION['X-CSRF-Token'])) { ?>
		$(document).ajaxSend(function(e, xhr, options) {
			xhr.setRequestHeader("X-CSRF-Token", "<?php print $_SESSION['X-CSRF-Token']?>");
		});
		<?php } ?>
	</script>

</head>

<body>

<!-- Navbar
============================================================================ -->
<?php
$menu_id = isset($_SESSION['user_role_id']) ? $_SESSION['user_role_id'] : '0';
include_once C_PROJECT_PATH . '/app/common/menu/menu_' . $menu_id . '.php';
?>

<!-- Main section
============================================================================ -->
<section class="main">