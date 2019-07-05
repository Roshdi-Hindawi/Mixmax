<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use MainBundle\Entity\Salary;
use MainBundle\Entity\Worker;

class DefaultController extends Controller
{
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $oldmonth = $em->getRepository(Salary::class)->findOneBy(['month'=>intval(date('m')), 'year'=>intval(date('Y'))]);
        
        
        if (!$oldmonth) {
            $salary = new Salary();
            $workers = $em->getRepository('MainBundle:Worker')->findByStatus('En Poste');
            $days=[];
            $paid=[];
            $month = date('m');
            $year = date('Y');

            for($d=1; $d<=31; $d++) {
                $time=mktime(0, 0, 0, intval($month), $d, intval($year));
                if (date('m', $time)==$month)
                    //$list[]=date('l, d-m-Y', $time);
                    foreach ($workers as $worker) {
                        $paid[$worker->getId()] = 0;
                        if (date('w', $time)==0)
                            $days[$time][$worker->getId()] = ['work' => 0,
                                                              'advance' => 0];
                        else
                            $days[$time][$worker->getId()] = ['work' => 1,
                                                              'advance' => 0];
                    }
            }

            $salary->setYear(intval($year));
            $salary->setMonth(intval($month));
            $salary->setPaid($paid);
            $salary->setDays($days);
            $em->persist($salary);
            $em->Flush();
        }

        return $this->redirectToRoute('admin_facility_index');
    }

    public function homeAction()
    {
        return $this->render('@Main/Default/index.html.twig');
    }

    public function monthAction () {
        
        $em = $this->getDoctrine()->getManager();
        $months = $em->getRepository(Salary::class)->findByYear( intval(date('Y')) );
        $workers = $em->getRepository(Worker::class)->findByStatus('En Poste');
        
        return $this->render('@Main/Administration/modules/months.html.twig', ['months' => $months]);
    }

    public function total($salary, $workers) {
        $total = 0;
        $sal = 0;

        foreach ($workers as $worker) {
            $work = 0;
            $advance = 0;
            if ($salary and $salary->getPaid()[$worker->getId()] == 0) {
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

        return $total;
    }

}
