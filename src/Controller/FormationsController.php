<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController
{

    /**
     * Repository des formations, utilisé pour accéder aux données des formations.
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Repository des catégories, utilisé pour accéder aux données des catégories.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Chemin vers le template Twig utilisé pour la liste des formations.
     */

    private const RENDER_PATH = "pages/formations.html.twig";

    /**
     *
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */


    /**
     * Constructeur du contrôleur.
     *
     * @param FormationRepository $formationRepository Le repository pour accéder aux formations
     * @param CategorieRepository $categorieRepository Le repository pour accéder aux catégories
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche toutes les formations et leurs catégories.
     *
     * @return Response La réponse HTTP contenant la vue de la liste des formations
     */

    #[Route('/formations', name: 'formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAllWithLimit(50);
        $categories = $this->categorieRepository->findAllWithLimit(50);
        return $this->render(self::RENDER_PATH, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }


    /**
     * Affiche la liste des formations triées selon un champ et un ordre donnés.
     * Permet de trier sur un champ d'une table associée si précisée.
     *
     * @param string $champ  Le nom du champ sur lequel effectuer le tri
     * @param string $ordre  L'ordre de tri
     * @param string $table   
     * @return Response 
     */


    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche toutes les formations dont un champ contient la valeur recherchée.
     * @param string  $champ   Le nom du champ sur lequel effectuer la recherche
     * @param Request $request La requête HTTP contenant le paramètre 'recherche'
     * @param string  $table   
     * @return Response 
     */

    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }


    /**
     * Affiche le détail d'une formation identifiée par son identifiant.
     *
     * @param int $id L'identifiant unique de la formation à afficher
     * @return Response 
     */

    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);
    }

}
