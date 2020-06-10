<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Worker;
use MainBundle\Entity\Facility;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Worker controller.
 *
 */
class WorkerController extends Controller
{
    /**
     * Lists all worker entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $workers = $em->getRepository('MainBundle:Worker')->findAll();
        $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')), 'year' => intval(date('Y'))]);
        $paidsalary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')-1), 'year' => intval(date('Y'))]);
        //$days = date_diff(date_create(date('Y-m-01')), date_create(date('Y-m-d')));
        /*$timeDiff = abs(strtotime(date('Y-m-01')) - strtotime(date('Y-m-d')));
        $days = $timeDiff/86400;  // 86400 seconds in one day
        //convert to integer
        $days = intval($days);*/
        /*$days = 0;
        $counter = mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y')));
        while (date("n", $counter) == intval(date('n')) and date("d", $counter) <= date('d')) {
            if (date("w", $counter)!= 0) {
                $days++;
            }
            $counter = strtotime("+1 day", $counter);
        }*/
            $workdays=[];
            $advance=[];
        foreach ( $workers as $worker )
        {
            $workdays[$worker->getId()] = 0 ;
            $advance[$worker->getId()] = 0 ;
            foreach ( $salary->getDays() as $key => $day )
                {
                //$date=mktime(0, 0, 0, intval(date('m')), $key, intval(date('Y')));
                if ( date('d', $key) <= date('d') and date('w', $key) != 0 and $day[$worker->getId()]['work']==1) 
                    $workdays[$worker->getId()] ++ ;
                
                $advance[$worker->getId()] = $advance[$worker->getId()] + $day[$worker->getId()]['advance'] ;
                }
        }//dump($workdays);die();

        return $this->render('@Main/Administration/worker/index.html.twig', array(
            'workers' => $workers,
            'workdays' => $workdays,
            'advance' => $advance,
            'salary' => $paidsalary,
            //'days' => $days,
        ));
    }

    /**
     * Creates a new worker entity.
     *
     */
    public function newAction(Request $request)
    {
        $worker = new Worker();
        $form = $this->createForm('MainBundle\Form\WorkerType', $worker);
        $form->handleRequest($request);//dump(mktime(0, 0, 0, intval(date('m')), intval(date('d'), intval(date('Y')))));die();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')), 'year' => intval(date('Y'))]);

            $em->persist($worker);
            $em->flush($worker);

            $days = $salary->getDays();
            $paid = $salary->getPaid();
            foreach ($days as $key => $day) {
                if (date('Y-m-d', $key) < $worker->getStartDate()->format('Y-m-d')) {
                    $days[$key][$worker->getId()] = ['work' => 0,
                                                     'advance' => 0];
                } elseif( date('w', $key)==0 ) { 
                    $days[$key][$worker->getId()] = ['work' => 0,
                                                     'advance' => 0];
                } else {
                    $days[$key][$worker->getId()] = ['work' => 1,
                                                     'advance' => 0];
                }
                $paid[$worker->getId()] = 0;
            }

            $salary->setDays($days);
            $salary->setpaid($paid);
            $em->flush($salary);

            $this->addFlash('success', $worker->getName().' a ete ajouter avec success');

            return $this->redirectToRoute('admin_worker_show', array('id' => $worker->getId()));
        }

        return $this->render('@Main/Administration/worker/new.html.twig', array(
            'worker' => $worker,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a worker entity.
     *
     */
    public function showAction(Request $request, Worker $worker)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($worker);
        $paidsalary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')-1), 'year' => intval(date('Y'))]);
        $editForm = $this->createForm('MainBundle\Form\WorkerType', $worker);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            $this->addFlash('success', $worker->getName().' a ete modifier avec success');

            return $this->redirectToRoute('admin_worker_show', array('id' => $worker->getId()));
        }

        return $this->render('@Main/Administration/worker/show.html.twig', array(
            'worker' => $worker,
            'salary' => $paidsalary,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing worker entity.
     *
     */
    public function editAction(Request $request, Worker $worker)
    {
        $deleteForm = $this->createDeleteForm($worker);
        $editForm = $this->createForm('MainBundle\Form\WorkerType', $worker);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $worker->getName().' a ete modifier avec success');

            return $this->redirectToRoute('admin_worker_edit', array('id' => $worker->getId()));
        }

        return $this->render('@Main/Administration/worker/edit.html.twig', array(
            'worker' => $worker,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a worker entity.
     *
     */
    public function deleteAction(Request $request, Worker $worker)
    {
        $form = $this->createDeleteForm($worker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($worker);
            $em->flush();
        }

        $this->addFlash('success', $worker->getName().' a ete Supprimer');

        return $this->redirectToRoute('admin_worker_index');
    }

    /**
     * Creates a form to delete a worker entity.
     *
     * @param Worker $worker The worker entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Worker $worker)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_worker_delete', array('id' => $worker->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function avanceAction(Request $request, Worker $worker)
    {

        $time = strtotime($request->request->get('date'));
        $em = $this->getDoctrine()->getManager();
        $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m', $time)), 'year' => intval(date('Y', $time))]);
        $month = $salary->getDays();
        
        if ($request->getMethod() == 'POST' and $request->request->get('date') != null) {

            foreach ($month as $t => $day ) {

                //$date = mktime(4, 0, 0, intval(date('m', $time)), $day, intval(date('Y', $time)) );
                if ($request->request->get('date') == date('m/d/Y', $t)) {
                    $month[$t][$worker->getId()]['advance'] = $month[$t][$worker->getId()]['advance'] + intval($request->request->get('advance'));
                }
                
            }

            $salary->setDays($month);
            $em->flush();
            $this->addFlash('success', 'Un Avance de '.$request->request->get('advance').'DT a ete ajouter pour '.$worker->getName());
        }

        return $this->redirectToRoute('admin_worker_show', array('id' => $worker->getId()));
    }

    public function restDaysAction(Request $request, Worker $worker) {
        
        $time = strtotime($request->request->get('month'));
        $em = $this->getDoctrine()->getManager();
        $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m', $time)), 'year' => intval(date('Y', $time))]);
        $month = $salary->getDays();
        
        if ($request->getMethod() == 'POST' and $request->request->get('dates') != null) {

            foreach ($month as $t => $day ) {

                if(strpos($request->request->get('dates'), date('m/d/Y', $t)) !== false) {

                    $month[$t][$worker->getId()]['work'] = 0;
                }
            }
            
            $salary->setDays($month);
            $em->flush();
            $this->addFlash('success', 'jour de Repos a ete enregistrer pour '.$worker->getName());
        }

        return $this->redirectToRoute('admin_worker_show', array('id' => $worker->getId()));
    }

    public function payAction(Request $request, Worker $worker){

        $em = $this->getDoctrine()->getManager();
        $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')-1), 'year' => intval(date('Y'))]);

        $restdays=0;

        foreach($salary->getDays() as $key => $day) {
            if(array_key_exists($worker->getId(), $day)){
                if(date('w', $key)!= 0 and $day[$worker->getId()]['work']==0) {
                    $restdays = $restdays + 1;
                }
            } else{
                $this->addFlash('warning', $worker->getName().'n\'a pas encore un salaire a payer');
                return $this->redirectToRoute('admin_worker_show', array('id' => $worker->getId()));
            }
        }

        if ($request->getMethod()=='POST') {
            $paid = $salary->getPaid();

            $paid[$worker->getId()] = 1;

            $salary->setPaid($paid);
            $em->flush();
        }

        return $this->render('@Main/Administration/worker/invoice.html.twig', ['salary' => $salary, 'worker' => $worker, 'restdays' => $restdays]);
    }

     public function printAction(Request $request, Worker $worker){

        $em = $this->getDoctrine()->getManager();
        $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')-1), 'year' => intval(date('Y'))]);

        $restdays=0;

        foreach($salary->getDays() as $key => $day) {
            if(date('w', $key)!= 0 and $day[$worker->getId()]['work']==0) {
                $restdays = $restdays + 1;
            }
        }

        return $this->render('@Main/Administration/worker/print.html.twig', ['salary' => $salary, 'worker' => $worker, 'restdays' => $restdays]);
    }

    public function facilityWorkerAction(Facility $facility)
    {
        $em = $this->getDoctrine()->getManager();
        $workers = $em->getRepository('MainBundle:Worker')->findByFacility($facility);
        $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')), 'year' => intval(date('Y'))]);
        $paidsalary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')-1), 'year' => intval(date('Y'))]);
        
            $workdays=[];
            $advance=[];
        foreach ( $workers as $worker )
        {
            $workdays[$worker->getId()] = 0 ;
            $advance[$worker->getId()] = 0 ;
            foreach ( $salary->getDays() as $key => $day )
            {
                if ( date('d', $key) <= date('d') and date('w', $key) != 0 and $day[$worker->getId()]['work']==1) 
                    $workdays[$worker->getId()] ++ ;
                
                $advance[$worker->getId()] = $advance[$worker->getId()] + $day[$worker->getId()]['advance'] ;
            }
        }

        return $this->render('@Main/Administration/worker/index.html.twig', array(
            'workers' => $workers,
            'workdays' => $workdays,
            'salary' => $paidsalary,
            'advance' => $advance,
        ));
    }

    public function monthDeatilsAction(Request $request, Worker $worker){

        $em = $this->getDoctrine()->getManager();
        $salary = $em->getRepository('MainBundle:Salary')->findOneBy(['month' => intval(date('m')), 'year' => intval(date('Y'))]);

        $restdays=0;

        foreach($salary->getDays() as $key => $day) {
            if(array_key_exists($worker->getId(), $day)){
                if(date('w', $key)!= 0 and $day[$worker->getId()]['work']==0) {
                    $restdays = $restdays + 1;
                }
            } else{
                $this->addFlash('warning', $worker->getName().'n\'a pas encore un salaire a payer');
                return $this->redirectToRoute('admin_worker_show', array('id' => $worker->getId()));
            }
        }

        if ($request->getMethod()=='POST') {
            $paid = $salary->getPaid();

            $paid[$worker->getId()] = 1;

            $salary->setPaid($paid);
            $em->flush();
        }

        return $this->render('@Main/Administration/worker/monthDetails.html.twig', ['salary' => $salary, 'worker' => $worker, 'restdays' => $restdays]);
    }

}
