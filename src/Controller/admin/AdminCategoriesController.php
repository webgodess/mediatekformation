<?php
namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;


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
        return $this->render(self::RENDER_PATH, [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/categories/categorie/{id}/remove', name: 'admin.categories.remove')]

    public function remove(Categorie $categorie, Request $request): Response
    {
        $formations = $categorie->getFormations();
        $token = $request->getPayload()->get('token');

        if ($this->isCsrfTokenValid('delete-categorie', $token)) {
            if (count($formations) === 0) {
                $this->categorieRepository->remove($categorie);
                $this->addFlash("success", "Catégorie supprimée avec succès");

            } else {
                $this->addFlash("danger", "Impossible de supprimer une catégorie qui contient des formations");
            }

        } else {
            $this->addFlash("danger", "Token CSRF invalide");
            return $this->redirectToRoute("admin.categories");
        }



        return $this->redirectToRoute("admin.categories");

    }

    #[Route('/admin/categories/categorie/add', name: 'admin.categories.add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $name = $request->get("name");
        $categorieExists = $this->categorieRepository->findOneBy(["name" => $name]);
        $token = $request->getPayload()->get('token');

        if (!$this->isCsrfTokenValid('add-categorie', $token)) {
            $this->addFlash("danger", "Token CSRF invalide");
            return $this->redirectToRoute("admin.categories");
        } else {

            if ($categorieExists) {
                $this->addFlash("danger", "Une catégorie avec ce nom existe déjà");
                return $this->redirectToRoute("admin.categories");
            }


            if (trim($name) !== "") {
                $categorie = new Categorie();
                $categorie->setName($request->get("name"));
                $this->categorieRepository->add($categorie);
                $this->addFlash("success", "Catégorie ajoutée avec succès");

            } else {
                $this->addFlash("danger", "Le nom de la catégorie ne peut pas être vide");
            }

        }





        return $this->redirectToRoute("admin.categories");

    }

}
