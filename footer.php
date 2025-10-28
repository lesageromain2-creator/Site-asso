<?php
// footer.php
?>
</main>
<footer class="site-footer">
<div class="container">
<p>© <?=date('Y')?> Association d'Art — Tous droits réservés.</p>
<form action="subscribe.php" method="post" class="newsletter-inline">
<label for="sub_email">Newsletter:</label>
<input type="email" name="email" id="sub_email" placeholder="votre@email.com" required>
<button type="submit">S'abonner</button>
</form>
</div>
</footer>
</body>
</html>