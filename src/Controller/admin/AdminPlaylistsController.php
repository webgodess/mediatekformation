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
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use App\Form\PlaylistType;


/**
 * Description of PlaylistsController
 *
 * @author emds
 */
class AdminPlaylistsController extends AbstractController
{

    /**
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;

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

    private const RENDER_PATH = "pages/admin/admin.playlists.html.twig";

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

    #[Route('/admin/playlists/add', name: 'admin.playlists.add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->playlistRepository->add($playlist);
            $this->addFlash("success", "playlist ajoutée");
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("pages/admin/admin.playlists/add.html.twig", [
            'form' => $form
        ]);
    }


    #[Route('/admin/playlists/playlist/{id}/remove', name: 'admin.playlists.remove', methods: ['POST'])]
    public function remove(Playlist $playlist, Request $request): Response
    {
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-playlist-' . $playlist->getId(), $token)) {
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


    #[Route('/admin/playlists/playlist/{id}/edit', name: 'admin.playlists.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Playlist $playlist): Response
    {

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->playlistRepository->add($playlist);
            $this->addFlash("success", "playlist modifiée");
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("pages/admin/admin.playlists/edit.html.twig", [
            'form' => $form->createView(),
            'playlist' => $playlist
        ]);
    }

}
