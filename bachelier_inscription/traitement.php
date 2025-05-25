<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et filtrage des données
    $nom = htmlspecialchars(trim($_POST["nom"]));
    $prenom = htmlspecialchars(trim($_POST["prenom"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $telephone = htmlspecialchars(trim($_POST["telephone"]));
    $cin = strtoupper(trim($_POST["cin"]));
    $massar = strtoupper(trim($_POST["massar"]));
    $ville = $_POST["ville"];
    $academie = $_POST["academie"];

    $math = floatval($_POST["math"]);
    $physique = floatval($_POST["physique"]);
    $francais = floatval($_POST["francais"]);

    // Validation des notes
    if ($math < 0 || $math > 20 || $physique < 0 || $physique > 20 || $francais < 0 || $francais > 20) {
        die("Erreur : Notes invalides (doivent être entre 0 et 20).");
    }

    // Validation des formats CIN et Massar
    if (!preg_match('/^[A-Z][0-9]{6,}$/', $cin)) {
        die("Format CIN invalide.");
    }

    if (!preg_match('/^[A-Z][0-9]{9}$/', $massar)) {
        die("Format Massar invalide.");
    }

    if (!$email) {
        die("Email invalide.");
    }

    // Calcul des moyennes
    $moy_nationale = ($math + $physique) / 2;
    $moy_regionale = ($physique + $francais) / 2;

    // Vérifier unicité CIN, Massar, Email
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE email = ? OR cin = ? OR massar = ?");
    $stmt->execute([$email, $cin, $massar]);
    if ($stmt->rowCount() > 0) {
        die("Erreur : Email, CIN ou Massar déjà utilisé !");
    }

    // Gestion des uploads (avec vérification)
    function uploadFile($file, $folder) {
        $target_dir = "uploads/$folder/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            die("Erreur lors de l'upload du fichier " . $file['name']);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            die("Seuls les fichiers PDF sont acceptés pour " . $file['name']);
        }

        $filename = uniqid() . "_" . basename($file["name"]);
        $target_file = $target_dir . $filename;

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            die("Erreur lors du déplacement du fichier " . $file['name']);
        }

        return $target_file;
    }

    $copie_bac = uploadFile($_FILES["copie_bac"], "bac");
    $releve = uploadFile($_FILES["releve"], "releve");
    $cin_scan = uploadFile($_FILES["cin_scan"], "cin");

    // Enregistrement en base
    $query = "INSERT INTO etudiants (nom, prenom, email, telephone, cin, massar, ville, academie, 
        note_math, note_physique, note_francais, moyenne_nationale, moyenne_regionale, 
        copie_bac, releve_notes, cin_scan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$nom, $prenom, $email, $telephone, $cin, $massar, $ville, $academie,
        $math, $physique, $francais, $moy_nationale, $moy_regionale,
        $copie_bac, $releve, $cin_scan]);

    echo "✅ Inscription réussie !";

} else {
    echo "Méthode non autorisée.";
}
