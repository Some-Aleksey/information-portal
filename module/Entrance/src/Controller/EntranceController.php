<?php
namespace Entrance\Controller;

use Entrance\Model\EntranceTable;
use Entrance\Model\Entrance;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Entrance\Form\EntranceForm;

class EntranceController extends AbstractActionController
{
    protected $entranceTable;
    protected $sessionManager;

    public function __construct(EntranceTable $entranceTable, \Zend\Session\SessionManager $sessionManager)
    {
        $this->entranceTable = $entranceTable;
        $this->sessionManager = $sessionManager;
    }

    private function layoutView()
    {
        $this->layout()->setTemplate('layout/layoutEntrance');
    }

    private function singIn()
    {
        $singInformation = new Entrance();

        $login = $this->getRequest()->getQuery()->get('login');
        $singInformation->login = $login;

        $password = $this->getRequest()->getQuery()->get('password');
        $singInformation->password = $password;

        $position = $this->getRequest()->getQuery()->get('position');
        $singInformation->position = $position;

        return $singInformation;
    }

    public function indexAction()
    {
        $containerAdmin = new Container('userAdmin');
        $containerRegularUser = new Container('userRegular');
        $this->layoutView();
        $form = new EntranceForm();
        $singInInformation = $this->singIn();
        if (isset($_GET['submitRegistration']))
            return $this->redirect()->toRoute('entrance', ['action' => 'registration']);
        if (isset($_GET['submitRecoveryPassword']))
            return $this->redirect()->toRoute('entrance', ['action' => 'recoverypassword']);
        foreach ($this->entranceTable->checkUser($singInInformation->login) as $userInformation):
            if ($singInInformation->login == $userInformation->login & $singInInformation->password == $userInformation->password & isset($_GET['submit']))
            {
                if ($userInformation->admin == 2)
                {
                    $containerAdmin->userAdmin = $userInformation->id;
                    return $this->redirect()->toRoute('admin', ['action' => 'index']);
                }
                else
                    {
                        $containerRegularUser->userRegular = $userInformation->id;
                        return $this->redirect()->toRoute('user', ['action' => 'index']);
                    }
            }
            endforeach;
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function recoveryPasswordAction()
    {
        $this->layoutview();
        $form = new EntranceForm();
        if (isset($_GET['submitCancel']))
            return $this->redirect()->toRoute('entrance', ['action' => 'index']);
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function registrationAction()
    {
        $this->layoutview();
        $form = new EntranceForm();
        $form->get('submit')->setValue('Registration');
        $form->get('position')->setLabel("Enter Id of postion ");
        $singInInformation = $this->singIn();
        $password2 = $this->getRequest()->getQuery()->get('password2');
        if (isset($_GET['submit']) & $password2 == $singInInformation->password & $singInInformation->login != null &
            $singInInformation->password != null)
        {
            $this->entranceTable->addNewUser($singInInformation->position);
            $this->entranceTable->addAllTheRest($singInInformation);
            return $this->redirect()->toRoute('entrance', ['action' => 'index']);
        }
        if (isset($_GET['submitCancel']))
            return $this->redirect()->toRoute('entrance', ['action' => 'index']);
        return new ViewModel([
           'form' => $form,
        ]);
    }
}

