<?php
require('../../fpdf186/fpdf.php');

try {
    $id = $_GET["id"];
    include '../../Categorie/Categorie/config/db.php';

    if (!$connexion) {
        throw new Exception("Connexion à la base de données échouée.");
    }

    class PDF extends FPDF {
        function Header() {
            global $id, $connexion;
            $r = "SELECT * FROM bon_sortie WHERE idBS = :id";
            $requette = $connexion->prepare($r);
            $requette->execute(['id' => $id]);
            $reponse = $requette->fetch(PDO::FETCH_ASSOC);
            $this->SetFont('Arial', 'B', 16);
            $this->Image('../../img/logoUAHB.jpeg', 10, 6, 30);
            $this->Cell(0, 10, 'Bon de Sortie', 0, 1, 'C');
            $this->Ln(10);
            $this->SetFont('Arial', 'I', 12);
            $this->Cell(0, 10, 'Numero de Sortie: ' . $reponse["idBS"], 0, 1, 'C');
            $this->Cell(0, 10, 'Date: ' . date("Y-m-d"), 0, 1, 'C');
            $this->Ln(5);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(50); // Décalage à droite
            $this->Cell(90, 10, 'NomProduit', 1, 0, 'C');
            $this->Cell(30, 10, 'Quantités', 1, 1, 'C');
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times', '', 12);

    $r = "SELECT p.nomproduit, bcp.quantite FROM bon_sortie_produit bcp, product p WHERE bcp.idP = p.idP AND idS = :id";
    $requette = $connexion->prepare($r);
    $requette->execute(['id' => $id]);
    $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);

    if (empty($reponse)) {
        throw new Exception("Aucun produit trouvé pour cet ID.");
    }

    foreach ($reponse as $ventes) {
        $pdf->Cell(50); // Décalage à droite
        $pdf->Cell(90, 10, $ventes["nomproduit"], 1, 0, 'C');
        $pdf->Cell(30, 10, $ventes["quantite"], 1, 1, 'C');
    }

    // Ajout d'une note ou d'informations supplémentaires
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 10);

    $pdf->Output('I', 'Bon_de_sortie.pdf');
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}