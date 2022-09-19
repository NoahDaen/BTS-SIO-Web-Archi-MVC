<?php

namespace Quizz\Controller\Etudiant;

use Dotenv\Parser\Entry;
use Quizz\Core\Controller\ControllerInterface;
use Quizz\Core\DebugHandler;
use Quizz\Core\View\TwigCore;
use Quizz\Entity\Etudiant;
use Quizz\Model\EtudiantModel;

class UpdateEtudiantController implements ControllerInterface
{

    private $idEtudiant;
    private $post;

    public function inputRequest(array $tabInput)
    {
        if (!empty($tabInput['VARS'])){
            $this->idEtudiant = $tabInput["VARS"]['id'];
        }
        if (!empty($tabInput["POST"])){
            $this->post = $tabInput['POST'];
        }
    }

    public function outputEvent()
    {
        $etudiantModel = new EtudiantModel();
        $etudiant = $etudiantModel->getFetchId($this->idEtudiant);
        $tabEtudiants = $etudiantModel->getFetchAll();

        $errorEmail = false;
        $errorLogin = false;

        if (!empty($this->post)) {
            foreach ($tabEtudiants as $value) {
                if ($this->post['email'] == $value->getEmail()) {
                    $errorEmail = true;
                } elseif ($this->post['login'] == $value->getLogin()) {
                    $errorLogin = true;
                }
            }
            if ($this->post['email'] == $etudiant->getEmail()){
                $errorEmail = false;
            }
            else{
                $errorEmail = true;
            }
            if ($this->post['login'] == $etudiant->getLogin()){
                $errorLogin = false;
            }
            else{
                $errorLogin = true;
            }
            if ($errorEmail == true or $errorLogin == true) {
                return TwigCore::getEnvironment()->render(
                    'etudiant/updateEtudiant.html.twig',
                    [
                        'errorEmail' => $errorEmail,
                        'errorLogin' => $errorLogin,
                    ]
                );
            }
            $etudiant->setLogin(htmlspecialchars($this->post['login']));
            $etudiant->setEmail(htmlspecialchars($this->post["email"]));
            $etudiant->setNom(htmlspecialchars($this->post['nom']));
            $etudiant->setPrenom(htmlspecialchars($this->post['prenom']));
            $etudiantModel->updateEtudiant($etudiant);
            header('Location: /etudiant');
        }
        return TwigCore::getEnvironment()->render(
            'etudiant/updateEtudiant.html.twig', [
                'etudiant' => $etudiant,
            ]
        );
    }
}