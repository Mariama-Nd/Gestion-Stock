<?php
session_start();
try {
    include '../../Categorie/Categorie/config/db.php';
    $date = date('Y-m-d H:i:s');
    if (isset($_SESSION["generation"])) {
        $_SESSION["generation"] += 1;
        $auto_gen = "BC" . $_SESSION["generation"] . "/" . $date;

    } else {
        $_SESSION["generation"] = 1;
        $auto_gen = "BC" . $_SESSION["generation"] . "/" . $date;
    }
    $nomBC = htmlSpecialChars($_GET["nomBC"]);
    $r = "INSERT INTO Bon_commande(date, idBC_gen,nomBC) VALUES (?, ?, ?)";
    $stmt = $connexion->prepare($r);
    $stmt->execute([$date, $auto_gen, $nomBC]);
    if ($stmt) {
        header("Location:commander.php?auto_gen=" . $auto_gen . "&nomBC=" . $nomBC);
    } else {
        header("Location:commander.php?auto_gen=" . $auto_gen);
    }
    unset($_SESSION["id"]);
} catch (\Throwable $th) {
    echo json_encode(["error" => $th->getMessage()]);
}