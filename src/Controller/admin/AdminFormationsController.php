<?php
namespace App\Controller\admin;

use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormationType;

/**
 * Controleur des formations côté admin
 *
 * @author s.n
 */
class AdminFormationsController extends AbstractController
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
     * Chemin vers le template Twig utilisé pour la liste des formations en administration.
     */

    private const RENDER_PATH = "pages/admin/admin.formations.html.twig";

    /**
     *
     * Constructeur du contrôleur.
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche toutes les formations avec leurs catégories
     * dans l'interface d'administration.
     *
     * @return Response La réponse HTTP contenant la vue de la liste des formations
     */

    #[Route('/admin/formations', name: 'admin.formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
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
     * @param string $ordre  L'ordre de tri : 'ASC' pour croissant, 'DESC' pour décroissant
     * @param string $table
     * @return Response
     */


    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
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
     * Affiche la liste des formations dont un champ contient la valeur recherchée.
     *
     *
     * @param string  $champ   Le nom du champ sur lequel effectuer la recherche
     * @param Request $request La requête HTTP contenant le paramètre 'recherche'
     * @param string  $table
     * @return Response La réponse HTTP contenant la vue de la liste des formations filtrées
     */

    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
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

    #[Route('/admin/formations/formation/{id}', name: 'admin.formations.showone')]
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/admin/admin.formation.html.twig", [
            'formation' => $formation
        ]);
    }

    /**
     * Supprime une formation après validation du token CSRF.
     * Redirige vers la liste des formations.
     *
     * @param Formation $formation La formation à supprimer
     * @param Request   $request   La requête HTTP contenant le token CSRF
     * @return Response La redirection vers la liste des formations après suppression
     */

    #[Route('/admin/formations/formation/{id}/remove', name: 'admin.formations.remove')]
    public function remove(Formation $formation, Request $request): Response
    {
        // Vérifier le token CSRF !
        if (
            $this->isCsrfTokenValid(
                'delete-formation-' . $formation->getId(),
                $request->request->get('token')
            )
        ) {
            $this->formationRepository->remove($formation);
            $this->addFlash("success", "Formation supprimée");
        } else {
            $this->addFlash("error", "Token invalide !");
        }

        return $this->redirectToRoute("admin.formations");
    }


    /**
     * Affiche le formulaire d'ajout d'une nouvelle formation et traite sa soumission.
     * Si le formulaire est valide, la formation est ajouée en base de données
     * et l'utilisateur est redirigé vers la liste des formations.
     *
     * @param Request $request La requête HTTP contenant les données du formulaire
     * @return Response La réponse HTTP contenant la vue du formulaire d'ajout
     *                  ou une redirection après succès
     */

    #[Route('/admin/formations/add', name: 'admin.formations.add')]
    public function add(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formationRepository->add($formation);
            $this->addFlash("success", "Formation ajoutée");
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("pages/admin/admin.formations/add.html.twig", [
            'form' => $form
        ]);
    }

    /**
     * Affiche le formulaire de modification d'une formation existante et traite sa soumission.
     * Si le formulaire est valide, les modifications sont ajoutées en base de données
     * et l'utilisateur est redirigé vers la liste des formations.
     *
     * @param Request   $request   La requête HTTP contenant les données du formulaire
     * @param Formation $formation La formation à modifier, résolue automatiquement par Symfony
     * @return Response
     */

    #[Route('/admin/formations/formation/{id}/edit', name: 'admin.formations.edit')]
    public function edit(Request $request, Formation $formation): Response
    {

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formationRepository->add($formation);
            $this->addFlash("success", "Formation modifiée");
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("pages/admin/admin.formations/edit.html.twig", [
            'form' => $form,
            'formation' => $formation
        ]);
    }

}

