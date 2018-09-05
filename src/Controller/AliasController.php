<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\LinkAlias;
use App\Entity\LinkAliasStatistic;
use App\Form\LinkAliasType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AliasController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET", "POST"})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $form = $this->createForm(LinkAliasType::class, new LinkAlias());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var LinkAlias $alias */
            $alias = $form->getData();
            try {
                $this->getDoctrine()->getManager()->persist($alias);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('alias_view', ['alias' => $alias->getId()]);
            } catch (UniqueConstraintViolationException $exception) {
                $form->get('alias')->addError(new FormError('This alias already exist. Try again.'));
            }
        }

        return $this->render('alias/link-form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/alias/{alias}", name="alias_view")
     * @param LinkAlias $alias
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(LinkAlias $alias)
    {
        return $this->render('alias/view.html.twig', ['alias' => $alias]);
    }

    /**
     * @Route("/{alias}", methods={"GET"}, name="redirect_to_origin")
     * @param Request   $request
     * @param LinkAlias $linkAlias
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToOrigin(Request $request, LinkAlias $linkAlias)
    {
        $statistic = new LinkAliasStatistic();
        $statistic->fromRequest($request)->fromLinkAlias($linkAlias);
        $this->getDoctrine()->getManager()->persist($statistic);
        $this->getDoctrine()->getManager()->flush();

        if ($linkAlias->isExpired()) {
            throw new NotFoundHttpException('This alias is expired.');
        }

        return $this->redirect($linkAlias->getOriginalLink());
    }
}