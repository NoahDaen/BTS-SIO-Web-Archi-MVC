<?php

namespace Quizz\Controller\Etudiant;

use Quizz\Core\Controller\ControllerInterface;
use Quizz\Core\View\TwigCore;
use Quizz\Model\EtudiantModel;

class ListeEtudiantController implements ControllerInterface
{
    private $idEtudiant;
    private $delete;

    public function inputRequest(array $tabInput)
    {
        if (!empty($tabInput['POST'])){
            $this->delete = $tabInput['POST']['delete'];
        }

        if(!empty($tabInput['VARS'])){
            $this->idEtudiant = $tabInput['VARS']['id'];
        }
    }

    public function outputEvent()
    {
        $etudiantModel = new EtudiantModel();
        $tabEtudiants = $etudiantModel->getFetchAll();

        if ($this->delete){
            $etudiantModel->deleteEtudiant($this->delete);
            return TwigCore::getEnvironment()->render(
                'liste/listeEtudiant.html.twig',[
                    'tabEtudiants' => $etudiantModel->getFetchAll(),
                    'post' => false,
                    'delete' => true,
                ]
            );
        }
        else{
            if ($this->idEtudiant){
                return TwigCore::getEnvironment()->render(
                    'liste/listeEtudiant.html.twig',[
                        'etudiant' => $etudiantModel->getFetchId($this->idEtudiant),
                        'post' => true,
                        'delete' => false,
                    ]
                );

            } else{
                return TwigCore::getEnvironment()->render(
                    'liste/listeEtudiant.html.twig',[
                        'tabEtudiants' => $etudiantModel->getFetchAll(),
                        'post' => false,
                        'delete' => false
                    ]
                );
            }
        }

    }
}