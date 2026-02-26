<?php
namespace App\Controller\admin;

use App\Entity\Categorie;
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

class AdminCategoriesController extends AbstractController
{
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    private const RENDER_PATH = "pages/admin/admin.categories.html.twig";

    /**
     *
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRepository
     *
     */

    public function __construct(CategorieRepository $categorieRepository, FormationRepository $formationRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;

    }

    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        $formations = $this->formationRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'categories' => $categories,
            'formations' => $formations
        ]);
    }

    #[Route('/admin/categories/tri/{champ}/{ordre}/{table}', name: 'admin.categories.sort')]
    public function sort($champ, $ordre, $table = ""): Response
    {
        $categories = $this->categorieRepository->findAllOrderBy($champ, $ordre, $table);
        $formations = $this->formationRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'categories' => $categories,
            'formations' => $formations
        ]);
    }

    #[Route('/admin/categories/recherche/{champ}/{table}', name: 'admin.categories.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'categories' => $categories,
            'formations' => $formations,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/admin/categories/categorie/{id}', name: 'admin.categories.showone')]
    public function showOne($id): Response
    {
        $categorie = $this->categorieRepository->find($id);
        return $this->render("pages/admin/admin.categorie.html.twig", [
            'categorie' => $categorie
        ]);
    }

    #[Route('/admin/categories/categorie/{id}/remove', name: 'admin.categories.remove')]

    public function remove(categorie $categorie): Response
    {
        $this->categorieRepository->remove($categorie);
        #$playlist = $categorie->getFormations();


        $this->addFlash("success", "categorie supprimée");
        return $this->redirectToRoute("admin.categories");
    }



    #[Route('/admin/categories/add', name: 'admin.categories.add')]
    public function add(Request $request): Response
    {
        return "to be added";
    }

}