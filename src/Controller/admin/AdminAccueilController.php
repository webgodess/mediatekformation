<?php
namespace App\Controller\admin;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur de la page d'accueil de l'administration
 *
 * @author s.n
 */

class AdminAccueilController extends AbstractController
{

    /**
     * @var FormationRepository
     */

    private $repository;

    /**
     *
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Affiche la page d'accueil avec les 2 dernières formations
     * @return Response
     */


    #[Route('/admin', name: 'admin.accueil')]
    public function index(): Response
    {
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/admin/admin.accueil.html.twig", [
            'formations' => $formations
        ]);
    }

    /**
     * Affiche la page des conditions générales d'utilisation
     * @return Response
     */


    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render("pages/cgu.html.twig");
    }
}
