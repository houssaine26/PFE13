<?php
require_once '../db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="etudiants.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ["CIN", "MATH", "PHYSIQUE", "FRANÇAIS", "MOYENNE NATIONALE", "MOYENNE REGIONALE"]);

$stmt = $pdo->query("SELECT * FROM etudiants");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row["cin"],
        $row["note_math"],
        $row["note_physique"],
        $row["note_francais"],
        $row["moyenne_nationale"],
        $row["moyenne_regionale"]
    ]);
}
fclose($output);
exit;
