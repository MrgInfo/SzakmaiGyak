﻿<?php

require_once 'config.php';

$prefix = isset( $_SERVER['REQUEST_URI'] ) && ( strpos( $_SERVER['REQUEST_URI'], '/koordinator/' ) !== false )
	? '..'
	: '.';

?>
		<script src="<?= $prefix ?>/script/jquery-2.1.3.min.js"></script>
		<script src="<?= $prefix ?>/script/bootstrap.min.js"></script>
		<script src="<?= $prefix ?>/script/bootstrap-table.min.js"></script>
		<script src="<?= $prefix ?>/script/bootstrap-table-hu-HU.min.js"></script>
		<script>

$("form").bind("keypress", function (e) {
	if (e.keyCode == 13) {
		return false;
	}
});

		</script>
	</body>
</html>
