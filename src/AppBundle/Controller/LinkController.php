<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Link;
use AppBundle\Entity\Sheet;
use AppBundle\Entity\SheetDev;
use AppBundle\Entity\Delivery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Link controller.
 *
 */
class LinkController extends Controller
{
    /**
     * Lists all link entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $links = $em->getRepository('AppBundle:Link')->findAll();

        return $this->render('link/index.html.twig', array(
            'links' => $links,
        ));
    }

    /**
     * Create a new Link Entity
     */
    public function newLinkAction(){

        $sheetPost = $_GET['sheetDatas'];
        $fileName = $sheetPost[0];
        $sheetId = $sheetPost[1];
        $sheetLink = $sheetPost[2];

        if ($sheetLink == 1){

            $em= $this->getDoctrine()->getManager()->getRepository('AppBundle:SheetDev');
            $sheetDevId = $em->find($sheetId);

            $link = new Link();
            $link->setLinkname($fileName);
            $link->setLink('media/documents/devis/'.$fileName);
            $link->setSheetdev1($sheetDevId);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('sheetdev_index');
        }
        elseif ($sheetLink == 2){
            $link = new Link();
            $link->setLinkname($fileName);
            $link->setLink('media/documents/devis/'.$fileName);
            $link->setSheet1($sheetId);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('sheet_index');
        }
        else{
            $link = new Link();
            $link->setLinkname($fileName);
            $link->setLink('media/documents/devis/'.$fileName);
            $link->setDelivery1($sheetId);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('delivery_index');
        }

    }
    /**
     * Finds and displays a link entity.
     *
     */
    public function showAction(Link $link)
    {
        $deleteForm = $this->createDeleteForm($link);

        return $this->render('link/show.html.twig', array(
            'link' => $link,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a link entity.
     *
     */
    public function deleteAction(Request $request, Link $link)
    {
        $form = $this->createDeleteForm($link);
        $form->handleRequest($request);

        $filename = $link->getLinkname();
        $sheet = $link->getId();

        if ($form->isSubmitted() && $form->isValid()) {

            unlink($this->container->getParameter('link_directory') . '/' . $filename);

            $em = $this->getDoctrine()->getManager();
            $em->remove($link);
            $em->flush();
        }

            return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a form to delete a link entity.
     *
     * @param Link $link The link entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Link $link)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('link_delete', array('id' => $link->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
