<?php

namespace Quizz\Controller\Etudiant;

use Quizz\Core\View\TwigCore;
use Quizz\Entity\Etudiant;
use Quizz\Model\EtudiantModel;

class AddEtudiantController implements \Quizz\Core\Controller\ControllerInterface
{
    private $post;

    public function inputRequest(array $tabInput)
    {
        if (!empty($tabInput["POST"])){
            $this->post = $tabInput["POST"];
        }
    }

    public function outputEvent()
    {
        $etudiant = new Etudiant();
        $etudiantModel = new EtudiantModel();
        $tabEtudiants = $etudiantModel->getFetchAll();

        $errorEmail=false;
        $errorLogin=false;
        $added=true;

        if(!empty($this->post)) {
            foreach ($tabEtudiants as $value) {
                if ($this->post['email'] == $value->getEmail()) {
                    $errorEmail = true;

                } elseif ($this->post['login'] == $value->getLogin()) {
                    $errorLogin = true;
                }
            }
            if ($errorEmail == true or $errorLogin == true){
                return TwigCore::getEnvironment()->render(
                    'etudiant/inscription.html.twig',
                    [
                        'errorEmail' => $errorEmail,
                        'errorLogin' => $errorLogin,
                    ]
                );
            }
            else{
                $password = password_hash($this->post['motDePasse'], PASSWORD_BCRYPT);
                $etudiant->setLogin(htmlspecialchars($this->post['login']));
                $etudiant->setEmail(htmlspecialchars($this->post["email"]));
                $etudiant->setMotDePasse($password);
                $etudiant->setNom(htmlspecialchars($this->post['nom']));
                $etudiant->setPrenom(htmlspecialchars($this->post['prenom']));
                $etudiantModel->insertEtudiant($etudiant);
                $added = true;
                return TwigCore::getEnvironment()->render(
                    'home/home.html.twig',
                    [
                        'ajouté' => $added,
                    ]
                );
            }
        }
        else{
            return TwigCore::getEnvironment()->render(
                'etudiant/inscription.html.twig',
                [
                    'errorEmail' => $errorEmail,
                    'errorLogin' => $errorLogin,
                ]
            );
        }
    }
}