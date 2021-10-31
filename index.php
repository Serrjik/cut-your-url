<?php

// Если в адресную строку передан URL сайта:
if (isset($_GET['url']) && !empty($_GET['url'])) {
	include_once __DIR__ . "/includes/functions.php";
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

include_once __DIR__ . "/includes/header.php";
?>
<main class="container">
	<!-- Если пользователь не авторизован: -->
	<?php if (!isset($_SESSION['user']['id'])) { ?>
		<div class="row mt-5">
			<div class="col">
				<h2 class="text-center">Необходимо <a href="<?= get_url("register.php") ?>">зарегистрироваться</a> или <a href="<?= get_url("login.php") ?>">войти</a> под своей учетной записью</h2>
			</div>
		</div>
	<?php } ?>

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
<?php include_once "./includes/footer.php"; ?>