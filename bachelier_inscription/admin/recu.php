<?php
require('libs/fpdf/fpdf.php');
require 'db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
$stmt->execute([$id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    die("Étudiant non trouvé.");
}

// Création du PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reçu d\'inscription', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nom: ' . $etudiant['nom'], 0, 1);
$pdf->Cell(0, 10, 'Prénom: ' . $etudiant['prenom'], 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $etudiant['email'], 0, 1);
$pdf->Cell(0, 10, 'Téléphone: ' . $etudiant['telephone'], 0, 1);
$pdf->Cell(0, 10, 'CIN: ' . $etudiant['cin'], 0, 1);
$pdf->Cell(0, 10, 'Code Massar: ' . $etudiant['massar'], 0, 1);
$pdf->Cell(0, 10, 'Ville: ' . $etudiant['ville'], 0, 1);
$pdf->Cell(0, 10, 'Académie: ' . $etudiant['academie'], 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Note Math: ' . $etudiant['note_math'], 0, 1);
$pdf->Cell(0, 10, 'Note Physique: ' . $etudiant['note_physique'], 0, 1);
$pdf->Cell(0, 10, 'Note Français: ' . $etudiant['note_francais'], 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Moyenne Nationale: ' . $etudiant['moyenne_nationale'], 0, 1);
$pdf->Cell(0, 10, 'Moyenne Régionale: ' . $etudiant['moyenne_regionale'], 0, 1);
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Date d\'inscription: ' . $etudiant['date_inscription'], 0, 1);

$pdf->Output();
