<?php
/**
 * Gestion de l'accueil
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

if ($estConnecte) {
    if (isset($_SESSION['idVisiteur'])){
        include 'vues/v_accueilV.php';
     }
if (isset($_SESSION['idComptable'])){
        include 'vues/v_accueilc.php';
    }
} else {
    include 'vues/v_connexion.php';
}
