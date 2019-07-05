<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Facility;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Facility controller.
 *
 */
class FacilityController extends Controller
{
    /**
     * Lists all facility entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $facilities = $em->getRepository('MainBundle:Facility')->findAll();

        return $this->render('@Main/Administration/facility/index.html.twig', array(
            'facilities' => $facilities,
        ));
    }

    /**
     * Creates a new facility entity.
     *
     */
    public function newAction(Request $request)
    {
        $facility = new Facility();
        $form = $this->createForm('MainBundle\Form\FacilityType', $facility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($facility);
            $em->flush();

            $this->addFlash('success', $facility->getName().' Ajouter avec success');

            return $this->redirectToRoute('admin_facility_show', array('id' => $facility->getId()));
        }

        return $this->render('@Main/Administration/facility/new.html.twig', array(
            'facility' => $facility,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a facility entity.
     *
     */
    public function showAction(Request $request, Facility $facility)
    {
        // $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($facility);
        // $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')-1), 'year' => intval(date('Y'))]);
        // $workers = $em->getRepository('MainBundle:Worker')->findByFacility($facility);
        $editForm = $this->createForm('MainBundle\Form\FacilityType', $facility);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $facility->getName().' a ete modifier avec success');

            return $this->redirectToRoute('admin_facility_show', array('id' => $facility->getId()));
        }

        // $total = total($salary, $workers);

        return $this->render('@Main/Administration/facility/show.html.twig', [
            'facility' => $facility,
            // 'total' => $total,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            ]);
    }

    /**
     * Displays a form to edit an existing facility entity.
     *
     */
    public function editAction(Request $request, Facility $facility)
    {
        $deleteForm = $this->createDeleteForm($facility);
        $editForm = $this->createForm('MainBundle\Form\FacilityType', $facility);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $facility->getName().' a ete modifier avec success');

            return $this->redirectToRoute('admin_facility_edit', array('id' => $facility->getId()));
        }

        return $this->render('@Main/Administration/facility/edit.html.twig', array(
            'facility' => $facility,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a facility entity.
     *
     */
    public function deleteAction(Request $request, Facility $facility)
    {
        $form = $this->createDeleteForm($facility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($facility);
            $em->flush();
        }

        $this->addFlash('success', $facility->getName().' a ete supprimer avec success');

        return $this->redirectToRoute('admin_facility_index');
    }

    /**
     * Creates a form to delete a facility entity.
     *
     * @param Facility $facility The facility entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Facility $facility)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_facility_delete', array('id' => $facility->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function totalAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')-1), 'year' => intval(date('Y'))]);
        $workers = $em->getRepository('MainBundle:Worker')->findByStatus('En Poste');
        
        // $total = total($salary, $workers);

        $total = 0;
        $sal = 0;

        foreach ($workers as $worker) {
            $work = 0;
            $advance = 0;
            if ($salary and array_key_exists ( $worker->getId() , $salary->getPaid() ) and $salary->getPaid()[$worker->getId()] == 0) {
                foreach ($salary->getDays() as $day) {
                    if ($day[$worker->getId()]['work'] == 1) {
                    $work ++;
                    }
                    $advance = $advance + $day[$worker->getId()]['advance'];
                }
                 $sal = (($worker->getSalary() / 26) * $work) - $advance;
            }
            $total = $total + $sal;
        }

        return $this->render('@Main/Administration/modules/total.html.twig', ['total' => $total]);
    }
}
