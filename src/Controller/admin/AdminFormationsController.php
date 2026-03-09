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
 * Controleur des formations
 *
 * @author emds
 */
class AdminFormationsController extends AbstractController
{

    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    private const RENDER_PATH = "pages/admin/admin.formations.html.twig";

    /**
     *
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

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

    #[Route('/admin/formations/formation/{id}', name: 'admin.formations.showone')]
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/admin/admin.formation.html.twig", [
            'formation' => $formation
        ]);
    }
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

