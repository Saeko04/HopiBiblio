<div class="compte-container">
	<h2>Mon Compte</h2>
	<form method="post" action="index.php?action=compteClient">
		<label for="nom">Nom :</label>
		<input type="text" name="nom" id="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" required><br>
		<label for="prenom">Prénom :</label>
		<input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" required><br>
		<label for="login">Login :</label>
		<input type="text" name="login" id="login" value="<?= htmlspecialchars($user['login'] ?? '') ?>" required><br>
		<button type="submit" class="btn">Valider</button>
	</form>

	<form method="post" action="index.php?action=exportDonnees" id="exportDonneesForm" style="margin-top:16px;">
		<button type="submit" class="btn">Récupérer mes données</button>
	</form>
	<div id="exportResult" style="margin-top:10px;"></div>
</div>

<script>
document.getElementById('exportDonneesForm').onsubmit = async function(e) {
	e.preventDefault();
	const res = await fetch('index.php?action=exportDonnees', {method:'POST'});
	const data = await res.json();
	let html = '';
	if (data.download_url) {
		html += `<a href='${data.download_url}' class='btn'>Télécharger mes données JSON</a><br>`;
		html += `<small>Hash SHA256 : <code>${data.hash}</code></small><br>`;
		html += `<small>Valable jusqu'au : ${data.expire}</small>`;
	} else {
		html += 'Erreur lors de la génération du fichier.';
	}
	document.getElementById('exportResult').innerHTML = html;
};
</script>
