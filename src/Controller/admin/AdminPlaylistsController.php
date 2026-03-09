<?php
namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PlaylistType;


/**
 * Controleur des playlists côté admin
 *
 * @author s.n
 */

class AdminPlaylistsController extends AbstractController
{

    /**
     * Repository des playlists, utilisé pour accéder aux données des playlists.
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * Repository des formations, utilisé pour accéder aux formations d'une playlist.
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Repository des catégories, utilisé pour accéder aux catégories des playlists.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Chemin vers le template Twig utilisé pour la liste des playlists en administration.
     */


    private const RENDER_PATH = "pages/admin/admin.playlists.html.twig";

    /**
     * Constructeur du contrôleur.
     * @param PlaylistRepository  $playlistRepository  Le repository pour accéder aux playlists
     * @param CategorieRepository $categorieRepository Le repository pour accéder aux catégories
     * @param FormationRepository $formationRepository Le repository pour accéder aux formations
     */

    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    /**
     * * Affiche la liste complète de toutes les playlists triées par nom (ordre croissant)
     * avec leurs catégories dans l'interface d'administration.
     * @Route("/playlists", name="playlists")
     * @return Response
     */

    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche la liste des playlists triées selon un champ et un ordre donnés.
     *
     * @param string $champ  Le nom du champ sur lequel effectuer le tri
     *                       ('name' ou 'nombreFormations')
     * @param string $ordre  L'ordre de tri
     * @return Response La réponse HTTP contenant la vue de la liste des playlists triées
     */

    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response
    {
        if ($champ === 'name') {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === 'nombreFormations') {
            $playlists = $this->playlistRepository->findAllOrderByNumberFormations($ordre);
        } else {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche la liste des playlists dont un champ contient la valeur recherchée.
     * La valeur de recherche est récupérée depuis les paramètres de la requête HTTP.
     *
     * @param string  $champ   Le nom du champ sur lequel effectuer la recherche
     * @param Request $request La requête HTTP contenant le paramètre 'recherche'
     * @param string  $table
     * @return Response
     */

    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::RENDER_PATH, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche le détail d'une playlist identifiée par son identifiant
     * avec ses formations et ses catégories associées.
     *
     * @param int $id L'identifiant unique de la playlist à afficher
     * @return Response
     */

    #[Route('/admin/playlists/playlist/{id}', name: 'admin.playlists.showone')]
    public function showOne($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/admin/admin.playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);
    }

    /**
     * Affiche le formulaire d'ajout d'une nouvelle playlist et traite sa soumission.
     * Si le formulaire est valide, la playlist est persistée en base de données
     * et l'utilisateur est redirigé vers la liste des playlists.
     *
     * @param Request $request La requête HTTP contenant les données du formulaire
     * @return Response
     */

    #[Route('/admin/playlists/add', name: 'admin.playlists.add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->playlistRepository->add($playlist);
            $this->addFlash("success", "Playlist ajoutée");
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("pages/admin/admin.playlists/add.html.twig", [
            'form' => $form
        ]);
    }

    /**
     * Supprime une playlist après validation du token CSRF.
     * La suppression est refusée si la playlist contient des formations associées.
     * Redirige vers la liste des playlists avec un message de succès ou d'erreur.
     *
     * @param Playlist $playlist La playlist à supprimer, résolue automatiquement par Symfony
     * @param Request  $request  La requête HTTP contenant le token CSRF dans le corps de la requête
     * @return Response La redirection vers la liste des playlists après traitement
     */

    #[Route('/admin/playlists/playlist/{id}/remove', name: 'admin.playlists.remove', methods: ['POST'])]
    public function remove(Playlist $playlist, Request $request): Response
    {
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-playlist' . $playlist->getId(), $token)) {
            if ($playlist->getNombreFormations() === 0) {
                $this->playlistRepository->remove($playlist);
                $this->addFlash("success", "Playlist supprimée");
            } else {
                $this->addFlash("error", "Impossible de supprimer une playlist qui contient des formations");
            }


        } else {
            $this->addFlash("error", "Token CSRF invalide");
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->redirectToRoute('admin.playlists');
    }

    /**
     * Affiche le formulaire de modification d'une playlist existante et traite sa soumission.
     * Si le formulaire est valide, les modifications sont ajoutées en base de données
     * et l'utilisateur est redirigé vers la liste des playlists.
     *
     * @param Request  $request  La requête HTTP contenant les données du formulaire
     * @param Playlist $playlist La playlist à modifier, résolue automatiquement par Symfony
     * @return Response La réponse HTTP contenant la vue du formulaire de modification
     *                  ou une redirection après succès
     */

    #[Route('/admin/playlists/playlist/{id}/edit', name: 'admin.playlists.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Playlist $playlist): Response
    {

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->playlistRepository->add($playlist);
            $this->addFlash("success", "Playlist modifiée");
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("pages/admin/admin.playlists/edit.html.twig", [
            'form' => $form->createView(),
            'playlist' => $playlist
        ]);
    }

}
