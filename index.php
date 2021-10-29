<?php
include "./includes/header.php";

// http://cut-your-url/goo

// Если в адресную строку передан URL сайта:
if (isset($_GET['url']) && !empty($_GET['url'])) {
	$url = strtolower(trim($_GET['url']));

	// Ссылка в БД, соответствующая введённой пользователем.
	$link = get_link_info($url);

	// Если в БД нет ссылки, соответствующей введённой пользователем:
	if (empty($link)) {
		ob_get_clean();
		// Переход на страницу с ошибкой 404.
		header(('Location: ' . get_url("404.php")));
		die;
	}

	update_views($url);
	ob_get_clean();
	// Переход по переданному URL'у.
	header(('Location: ' . $link["long_link"]));
	die;
}
?>
<!-- http://cut-your-url/index.php?url=goo -->
<main class="container">
	<div class="row mt-5">
	<?php if (!isset($_SESSION['user']['id'])) { ?>
		<div class="col">
			<h2 class="text-center">Необходимо <a href="<?= get_url("register.php") ?>">зарегистрироваться</a> или <a href="<?= get_url("login.php") ?>">войти</a> под своей учетной записью</h2>
		</div>
	<?php } ?>

	</div>
	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Пользователей в системе: <?= $users_count ?></h2>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Ссылок в системе: <?= $links_count ?></h2>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Всего переходов по ссылкам: <?= $views_count ?></h2>
		</div>
	</div>
</main>
<?php include "./includes/footer.php"; ?>