<?php

namespace Quizz\Model;

use Quizz\Core\Service\DatabaseService;
use Quizz\Entity\Etudiant;


class EtudiantModel
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = DatabaseService::getConnect();
    }

    public function getFetchAll(){
        $requete = $this->bdd->prepare('SELECT * FROM etudiants');
        $requete->execute();
        $tabEtudiants = [];

        foreach ($requete->fetchAll() as $value)
        {
            $etudiant = new Etudiant();
            $etudiant->setIdEtudiant($value['idEtudiant']);
            $etudiant->setLogin($value['login']);
            $etudiant->setEmail($value["email"]);
            $etudiant->setMotDePasse($value['motDePasse']);
            $etudiant->setNom($value['nom']);
            $etudiant->setPrenom($value['prenom']);
            $tabEtudiants[] = $etudiant;
        }

        return $tabEtudiants;
    }

    public function getFetchId(int $id)
    {
        $requete = $this->bdd->prepare('SELECT * FROM etudiants where idEtudiant = ' . $id);
        $requete->execute();
        $result = $requete->fetch();

        $etudiant = new Etudiant();
        $etudiant->setIdEtudiant($result['idEtudiant']);
        $etudiant->setLogin($result['login']);
        $etudiant->setEmail($result["email"]);
        $etudiant->setMotDePasse($result['motDePasse']);
        $etudiant->setNom($result['nom']);
        $etudiant->setPrenom($result['prenom']);

        return  $etudiant;
    }

    public function insertEtudiant(Etudiant $etudiant){
        $request = $this->bdd->prepare("INSERT INTO etudiants (login, motDePAsse, nom, prenom, email) VALUES ('{$etudiant->getLogin()}','{$etudiant->getMotDePasse()}','{$etudiant->getNom()}','{$etudiant->getPrenom()}','{$etudiant->getEmail()}')");
        $request->execute();
    }

    public function deleteEtudiant(int $idEtudiant){
        $request = $this->bdd->prepare("delete from etudiants where idEtudiant = {$idEtudiant}");
        $request->execute();
    }

    public function updateEtudiant(Etudiant $etudiant){
        $request = $this->bdd->prepare("update etudiants 
                set nom = '{$etudiant->getNom()}', 
                    prenom = '{$etudiant->getPrenom()}', 
                    email = '{$etudiant->getEmail()}',
                    login = '{$etudiant->getLogin()}' 
                where idEtudiant = {$etudiant->getIdEtudiant()}");
        $request->execute();
    }
}