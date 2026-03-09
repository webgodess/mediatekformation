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
 * Controleur des formations côté admin
 *
 * @author s.n
 */

class AdminCategoriesController extends AbstractController
{
    /**
     * Repository des catégories, utilisé pour accéder aux données des catégories.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Repository des formations, utilisé pour vérifier les formations liées à une catégorie.
     * @var FormationRepository
     */
    private $formationRepository;



    /**
     * Chemin vers le template Twig utilisé pour la liste des catégories en administration.
     */

    private const RENDER_PATH = "pages/admin/admin.categories.html.twig";

    /**
     * Constructeur du contrôleur.
     * @param CategorieRepository $categorieRepository Le repository pour accéder aux catégories
     * @param FormationRepository $formationRepository Le repository pour accéder aux formations
     *
     */

    public function __construct(CategorieRepository $categorieRepository, FormationRepository $formationRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;

    }

    /**
     * Affiche la liste complète de toutes les catégories
     *
     * @return Response La réponse HTTP contenant la vue de la liste des catégories
     */

    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'categories' => $categories
        ]);
    }

    /**
     * Supprime une catégorie après validation du token CSRF.
     * La suppression est refusée si la catégorie contient des formations associées.
     * Redirige vers la liste des catégories avec un message de succès ou d'erreur.
     *
     * @param Categorie $categorie La catégorie à supprimer, résolue automatiquement par Symfony
     * @param Request   $request   La requête HTTP contenant le token CSRF dans le corps de la requête
     * @return Response La redirection vers la liste des catégories après traitement
     */

    #[Route('/admin/categories/categorie/{id}/remove', name: 'admin.categories.remove')]

    public function remove(Categorie $categorie, Request $request): Response
    {
        $formations = $categorie->getFormations();
        $token = $request->getPayload()->get('_token');

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

    /**
     * Ajoute une nouvelle catégorie après validation du token CSRF.
     * Vérifie que le nom n'est pas vide et qu'aucune catégorie du même nom n'existe déjà.
     * Redirige vers la liste des catégories avec un message de succès ou d'erreur.
     *
     * @param Request $request La requête HTTP contenant le nom de la catégorie
     *                         et le token CSRF dans le corps de la requête (méthode POST)
     * @return Response La redirection vers la liste des catégories après traitement
     */

    #[Route('/admin/categories/categorie/add', name: 'admin.categories.add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $name = $request->get("name");
        $categorieExists = $this->categorieRepository->findOneBy(["name" => $name]);
        $token = $request->getPayload()->get('_token');

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
