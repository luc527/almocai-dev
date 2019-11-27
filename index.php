<?php

/**
 * Redireciona o usuário que acabou de entrar no sistema para sua interface correspondente. Ou para a página de login, caso não haja seção.
 */

include('valida_secao.php');
valida_secao("");

if ($_SESSION['tipo'] == 'ADMINISTRADOR') {
	header("location:administrador/");

} else if ($_SESSION['tipo'] == 'FUNCIONARIO') {
	header("location:funcionario/");

} else if ($_SESSION['tipo'] == 'ALUNO') {
	header("location:aluno/");

}
?>


<script>
	if ('serviceWorker' in navigator) {
		window.addEventListener('load', function() {
			navigator.serviceWorker.register('{root_path}service-worker.js', {scope: '{./}'})
				.then(reg => {
					console.log('Service worker registered! 😎', reg);
				})
				.catch(err => {
					console.log('😥 Service worker registration failed: ', err);
				});
		});
	}
</script>