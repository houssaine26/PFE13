<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

require_once '../db.php';

// Récupérer les filtres
$ville = $_GET['ville'] ?? '';
$academie = $_GET['academie'] ?? '';
$note_math_min = isset($_GET['note_math_min']) ? floatval($_GET['note_math_min']) : 0;

// Requête avec filtres
$query = "SELECT * FROM etudiants WHERE 1=1";
$params = [];

if ($ville !== '') {
    $query .= " AND ville = ?";
    $params[] = $ville;
}
if ($academie !== '') {
    $query .= " AND academie = ?";
    $params[] = $academie;
}
if ($note_math_min > 0) {
    $query .= " AND note_math >= ?";
    $params[] = $note_math_min;
}

$query .= " ORDER BY date_inscription DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$etudiants = $stmt->fetchAll();

// Préparer les données du graphique en fonction des résultats filtrés
$dataVille = [];
foreach ($etudiants as $e) {
    $v = $e['ville'];
    if (!isset($dataVille[$v])) $dataVille[$v] = 0;
    $dataVille[$v]++;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f8f9fa; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #e9ecef; }
        form { margin-bottom: 20px; padding: 10px; background: white; border: 1px solid #ddd; }
        h2, h3 { color: #333; }
        a { text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
        .header-links { margin-bottom: 10px; }
        .header-links a { margin-right: 15px; }
    </style>
</head>
<body>
    <h2>Bienvenue, <?= htmlspecialchars($_SESSION["admin"]) ?></h2>

    <div class="header-links">
        <a href="logout.php">🚪 Déconnexion</a>
        <a href="export.php">📤 Exporter en CSV</a>
    </div>

    <h3>Nombre d'inscrits par ville (selon filtres)</h3>
    <canvas id="chartVille" width="400" height="200"></canvas>
    <script>
        const ctx = document.getElementById('chartVille').getContext('2d');
        const data = <?= json_encode($dataVille) ?>;
        const labels = Object.keys(data);
        const values = Object.values(data);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: "Nombre d'inscrits",
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

    <h3>Rechercher des inscrits</h3>
    <form method="GET" action="dashboard.php">
        <label>Ville :</label>
        <select name="ville">
            <option value="" <?= $ville === '' ? 'selected' : '' ?>>Toutes</option>
            <option value="Oujda" <?= $ville === 'Oujda' ? 'selected' : '' ?>>Oujda</option>
            <option value="Fès" <?= $ville === 'Fès' ? 'selected' : '' ?>>Fès</option>
            <option value="Rabat" <?= $ville === 'Rabat' ? 'selected' : '' ?>>Rabat</option>
        </select>

        <label>Académie :</label>
        <select name="academie">
            <option value="" <?= $academie === '' ? 'selected' : '' ?>>Toutes</option>
            <option value="Oriental" <?= $academie === 'Oriental' ? 'selected' : '' ?>>Oriental</option>
            <option value="Fès-Meknès" <?= $academie === 'Fès-Meknès' ? 'selected' : '' ?>>Fès-Meknès</option>
            <option value="Rabat-Salé" <?= $academie === 'Rabat-Salé' ? 'selected' : '' ?>>Rabat-Salé</option>
        </select>

        <label>Note min. en Math :</label>
        <input type="number" name="note_math_min" min="0" max="20" step="0.1" value="<?= htmlspecialchars($note_math_min) ?>">

        <input type="submit" value="Rechercher">
    </form>

    <p><strong><?= count($etudiants) ?></strong> étudiant(s) trouvé(s).</p>

    <table>
        <thead>
            <tr>
                <th>Nom</th><th>Prénom</th><th>CIN</th><th>Massar</th>
                <th>Math</th><th>Physique</th><th>Français</th>
                <th>Moy. Nat</th><th>Moy. Reg</th><th>Date</th><th>Reçu</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($etudiants)): ?>
            <tr><td colspan="11">Aucun inscrit trouvé.</td></tr>
        <?php else: ?>
            <?php foreach ($etudiants as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['cin']) ?></td>
                    <td><?= htmlspecialchars($e['massar']) ?></td>
                    <td><?= htmlspecialchars($e['note_math']) ?></td>
                    <td><?= htmlspecialchars($e['note_physique']) ?></td>
                    <td><?= htmlspecialchars($e['note_francais']) ?></td>
                    <td><?= htmlspecialchars($e['moyenne_nationale']) ?></td>
                    <td><?= htmlspecialchars($e['moyenne_regionale']) ?></td>
                    <td><?= htmlspecialchars($e['date_inscription']) ?></td>
                    <td><a href="recu.php?id=<?= $e['id'] ?>" target="_blank">🧾 PDF</a></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
