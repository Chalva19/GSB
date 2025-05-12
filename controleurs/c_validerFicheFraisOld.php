<?php
/**
 * Gestion de la connexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_URL);

$mois = getMois(date('d/m/Y'));

if (strpos($mois, "Erreur:") !== false) {
    echo "Erreur dans getMois: $mois";  // Affiche l'erreur si `getMois` échoue
    exit;
}
$listeMois = getDerniers12Mois($mois);
$listeVisiteurs = $pdo->getLesVisiteurs();

switch ($action) {

    case 'selectionnerVisiteur':
        include 'vues/v_listeVisiteur.php';
    break;

    case 'detailFicheFrais':
        $moiselectionne = $_POST ['lstMois'];
        $visiteurselectionne = $_POST ['lstVisiteur']; 

        $veriFiche = $pdo->getVerifFicheFrais($visiteurselectionne, $moiselectionne);

        if (!$veriFiche){
            include 'vues/v_listeVisiteur.php';
            ajouterErreur( 'Aucune fiche de frais pour le visiteur et le mois selectionné');
            include 'vues/v_erreurs.php';
        }
        else{
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurselectionne, $moiselectionne);
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurselectionne, $moiselectionne);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurselectionne, $moiselectionne);
        $numAnnee = substr($moiselectionne, 0, 4);
        $numMois = substr($moiselectionne, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);

        include 'vues/v_etatFraisC.php';
    }
        //var_dump($moiselectionne,$visiteurselectionne);
    break;

    case 'majFraisForfait':
        $lesFrais = $_POST['lesFrais'];
        var_dump($lesFrais);
        $pdo->majFraisForfait($visiteurselectionne, $moiselectionne, $lesFrais);
    break;

    case 'corrigerFraisHorsForfait':
        if (isset($_REQUEST['corriger'])) {
            // Récupérer les données du formulaire
            $id = $_POST['id'];
            $date = $_POST['date'];
            $libelle = $_POST['libelle'];
            $montant = $_POST['montant'];
            
            // Mettre à jour les données dans la base de données
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            
            // Rediriger vers la page de validation des frais
            header("Location: index.php?uc=validerFrais&action=voirEtatFrais");
            exit;
        } elseif (isset($_REQUEST['reinitialiser'])) {
            // ID du frais à réinitialiser
            $id = $_POST['reinitialiser'];
            
            // Récupérer les valeurs originales du frais (celles saisies par le visiteur)
            // Exemple: $fraisOriginal = $pdo->query("SELECT * FROM fraishorsforfait WHERE id=?", [$id])->fetch();
            
            // Mettre à jour les données dans la base de données avec les valeurs originales
            // Exemple: $pdo->execute("UPDATE fraishorsforfait SET date=?, libelle=?, montant=? WHERE id=?", 
            //    [$fraisOriginal['date'], $fraisOriginal['libelle'], $fraisOriginal['montant'], $id]);
            
            // Rediriger vers la page de validation des frais
            header("Location: index.php?uc=validerFrais&action=voirEtatFrais");
            exit;
        }
    break ;
}
?>