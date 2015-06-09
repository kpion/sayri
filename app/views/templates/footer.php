			<footer>
				Zalogowany jako: <?=Auth::cur()['login']?Auth::cur()['login']:'niezalogowany';?>
				<?//Utils::printr(Auth::cur()['roles'])?>
			</footer>
		</div><!--end of div class='page'-->
	</body>
</html>