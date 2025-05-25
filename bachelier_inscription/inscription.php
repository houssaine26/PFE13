<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Bachelier</title>
    <style>
        body {
            background-color: #f1f3f5;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input, select {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-list {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error-list ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulaire d'inscription</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <ul>
                    <?php foreach ($errors as $field => $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="traitement.php" method="POST" enctype="multipart/form-data">
            <label>Nom:</label>
            <input type="text" name="nom" required value="<?= htmlspecialchars($old['nom'] ?? '') ?>">

            <label>Prénom:</label>
            <input type="text" name="prenom" required value="<?= htmlspecialchars($old['prenom'] ?? '') ?>">

            <label>Email:</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">

            <label>Téléphone:</label>
            <input type="text" name="telephone" required value="<?= htmlspecialchars($old['telephone'] ?? '') ?>">

            <label>CIN:</label>
            <input type="text" name="cin" pattern="[A-Z]{1}[0-9]{6,}" required value="<?= htmlspecialchars($old['cin'] ?? '') ?>">

            <label>Code Massar:</label>
            <input type="text" name="massar" pattern="[A-Z][0-9]{9}$" required value="<?= htmlspecialchars($old['massar'] ?? '') ?>">

            <label>Ville:</label>
            <select name="ville" required>
                <option value="">Choisir</option>
                <option value="Oujda" <?= (isset($old['ville']) && $old['ville'] == 'Oujda') ? 'selected' : '' ?>>Oujda</option>
                <option value="Fès" <?= (isset($old['ville']) && $old['ville'] == 'Fès') ? 'selected' : '' ?>>Fès</option>
                <option value="Rabat" <?= (isset($old['ville']) && $old['ville'] == 'Rabat') ? 'selected' : '' ?>>Rabat</option>
            </select>

            <label>Académie:</label>
            <select name="academie" required>
                <option value="">Choisir</option>
                <option value="Oriental" <?= (isset($old['academie']) && $old['academie'] == 'Oriental') ? 'selected' : '' ?>>Oriental</option>
                <option value="Fès-Meknès" <?= (isset($old['academie']) && $old['academie'] == 'Fès-Meknès') ? 'selected' : '' ?>>Fès-Meknès</option>
                <option value="Rabat-Salé" <?= (isset($old['academie']) && $old['academie'] == 'Rabat-Salé') ? 'selected' : '' ?>>Rabat-Salé</option>
            </select>

            <label>Note Math:</label>
            <input type="number" name="math" step="0.1" min="0" max="20" required value="<?= htmlspecialchars($old['math'] ?? '') ?>">

            <label>Note Physique:</label>
            <input type="number" name="physique" step="0.1" min="0" max="20" required value="<?= htmlspecialchars($old['physique'] ?? '') ?>">

            <label>Note Français:</label>
            <input type="number" name="francais" step="0.1" min="0" max="20" required value="<?= htmlspecialchars($old['francais'] ?? '') ?>">

            <label>Copie du Bac (PDF):</label>
            <input type="file" name="copie_bac" accept=".pdf" required>

            <label>Relevé des Notes (PDF):</label>
            <input type="file" name="releve" accept=".pdf" required>

            <label>Scan CIN (PDF):</label>
            <input type="file" name="cin_scan" accept=".pdf" required>

            <input type="submit" value="S'inscrire">
        </form>
    </div>
</body>
</html>
