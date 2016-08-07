<?php
namespace Lfarm\DevCollabBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilder;

use Lfarm\DevCollabBundle\Entity\Ticket;
use Lfarm\DevCollabBundle\Form\TicketType;

class FormCreateService
{
    // protected $doctrine;

    // public function __construct(Registry $doctrine)
    // {
    //     $this->doctrine = $doctrine;

    // }

    public function createDevCollabForm()
    {
        $forrm = $this->get('form.factory');
        $ticket = new Ticket();
        $CollabForm = $this->createForm('Lfarm\DevCollabBundle\Form\TicketType', $ticket);
    }

}
