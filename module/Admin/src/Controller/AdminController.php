<?php
namespace Admin\Controller;

use Admin\Form\AdminForm;
use Admin\Model\Admin;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\AdminTable;
use Zend\Session\Container;

class AdminController extends AbstractActionController
{
    protected $adminTable;
    protected $sessionManager;
    protected $route = 'admin';

    public function __construct(AdminTable $adminTable, \Zend\Session\SessionManager $sessionManager)
    {
        $this->adminTable = $adminTable;
        $this->sessionManager = $sessionManager;
    }

    private function layoutView()
    {
        $this->layout()->setTemplate('layout/layoutAdmin');
    }

    private function getFormsInformation()
    {
        $filter = new Admin();

        $filterPos = $this->getRequest()->getQuery()->get('position');
        $filter->position = $filterPos;

        $filterGender = $this->getRequest()->getQuery()->get('gender');
        $filter->gender = $filterGender;

        $filterOlder = $this->getRequest()->getQuery()->get('older');
        $filter->older = $filterOlder;

        $filterUnder = $this->getRequest()->getQuery()->get('under');
        $filter->under = $filterUnder;

        $filterMoreInformation = $this->getRequest()->getQuery()->get('name');
        $filter->moreInformation = $filterMoreInformation;

        $filterActive = $this->getRequest()->getQuery()->get('active');
        $filter->active = $filterActive;

        $filterAdmin = $this->getRequest()->getQuery()->get('adminCheck');
        $filter->admin = $filterAdmin;

        $lastname =$this->getRequest()->getQuery()->get('lastname');
        $filter->lastname = $lastname;

        $firstname = $this->getRequest()->getQuery()->get('firstname');
        $filter->firstname = $firstname;

        $patronymic = $this->getRequest()->getQuery()->get('patronymic');
        $filter->patronymic = $patronymic;

        $bithday = $this->getRequest()->getQuery()->get('bithday');
        $filter->bithday = $bithday;

        $skype = $this->getRequest()->getQuery()->get('skype');
        $filter->skype = $skype;

        return $filter;
    }

    public function redirectToEntrance()
    {
        $container = new Container('userAdmin');
        $thisUserId = $container->userAdmin;
        if ($thisUserId == null)
            return $this->redirect()->toRoute('entrance', ['action' => 'index']);
        else return $thisUserId;
    }

    public function indexAction()
    {
        $this->layoutView();
        $this->redirectToEntrance();
    }

    public function userInformationAction()
    {
        $this->layoutView();
        $thisUserId = $this->redirectToEntrance();
        return new ViewModel([
            'changeUserDataUrl' => $this->url()->fromRoute('admin', ['action' => 'changeuserinformation']),
            'user'              => $this->adminTable->fetchUser($thisUserId),
            'route'             => $this->route,
            'userId'            => $thisUserId,
        ]);
    }

    public function changeUserInformationAction()
    {
        $this->redirectToEntrance();
        $form = new AdminForm();
        $this->layoutView();
        $form->get('submit')->setValue("Change");
        $form->get('position')->setLabel("Enter Id of postion ");
        $userId = $this->params('id');
        foreach ($this->adminTable->fetchUser($userId) as $user):
            $form->get('active')->setValue($user->active);
            $form->get('adminCheck')->setValue($user->admin);
            $form->get('firstname')->setValue($user->firstname);
            $form->get('lastname')->setValue($user->lastname);
            $form->get('patronymic')->setValue($user->patronymic);
            $form->get('gender')->setValue($user->gender);
            $form->get('bithday')->setValue($user->bithday);
            $form->get('skype')->setValue($user->skype);
        endforeach;
        $userInformation = $this->getFormsInformation();
        $checkFullInformation = $userInformation->active != 0 & $userInformation->admin != 0 & $userInformation->firstname != null
        & $userInformation->lastname != null & $userInformation->patronymic != null & $userInformation->gender != 0
        & $userInformation->bithday != 0 & $userInformation->skype != null & $userInformation->position != null;
        if (isset($_GET['submit']) & $checkFullInformation)
        {
            $this->adminTable->changeUser($userInformation, $userId);
            return $this->redirect()->toRoute('admin', ['action' => 'listusers']);
        }
        if (isset($_GET['submitCancel']))
            return $this->redirect()->toRoute('admin', ['action' => 'listusers']);
        if (isset($_GET['submitChangePassword']))
            return $this->redirect()->toRoute('admin', ['action' => 'changepassword', 'id' => $userId]);
        if (isset($_GET['submitDelete']))
            return $this->redirect()->toRoute('admin', ['action' => 'deleteuser', 'id' => $userId]);
        return new ViewModel([
            'changePasswordUrl' => $this->url()->fromRoute('admin', ['action' => 'changepassword', 'id' => $userId]),
            'form'              => $form,
            'userId'            => $userId,
        ]);
    }

    public function changePasswordAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $password = $this->getRequest()->getQuery()->get('password');
        $passwordRepeat = $this->getRequest()->getQuery()->get('password2');
        $userId = $this->params('id');
        if (isset($_GET['submit']) & $password == $passwordRepeat)
        {
            $this->adminTable->changePassword($password, $userId);
            return $this->redirect()->toRoute('admin', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function deleteUserAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $form->get('submit')->setValue('Yes');
        $form->get('submitCancel')->setValue('No');
        $userId = $this->params('id');
        if (isset($_GET['submit']))
        {
            $this->adminTable->deleteUser($userId);
            return $this->redirect()->toRoute('admin', ['action' => 'listusers']);
        };
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function listMessagesAction()
    {
        $thisUser = $this->redirectToEntrance();
        $form = new AdminForm();
        $filter = $this->getFormsInformation();
        $form->get('submit')->setValue('Search');
        $this->layoutView();
        return new ViewModel([
            'users'      => $this->adminTable->filter($filter),
            'form'       => $form,
            'route'      => $this->route,
            'notDisplay' => $thisUser,
        ]);
    }

    public function messengerAction()
    {
        $thisUser = $this->redirectToEntrance();
        $this->layoutView();
        $interlocutorId = $this->params('id');
        $form = new AdminForm();
        $textNewMessage = $this->getRequest()->getQuery()->get('textMessage');
        if (isset($_GET['submit']) & $textNewMessage != null)
        {
            $this->adminTable->addNewMessage($thisUser, $interlocutorId, $textNewMessage);
            return $this->redirect()->toRoute('admin', ['action' => 'messenger', 'id' => $interlocutorId]);
        }
        return new ViewModel([
            'listMessagesUrl' => $this->url()->fromRoute('admin', ['action' => 'listmessages']),
            'messages'        => $this->adminTable->message($thisUser, $interlocutorId),
            'interlocutorId'  => $interlocutorId,
            'form'            => $form,
        ]);
    }

    public function listUsersAction()
    {
        $this->redirectToEntrance();
        $form = new AdminForm();
        $filter = $this->getFormsInformation();
        $form->get('submit')->setValue('Search');
        $this->layoutView();
        return new ViewModel([
            'changeUserDataUrl' => $this->url()->fromRoute('admin', ['action' => 'changeuserinformation']),
            'users'             => $this->adminTable->filter($filter),
            'form'              => $form,
        ]);
    }

    public function positionAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        return new ViewModel([
            'positions' => $this->adminTable->fetchPosition(),
        ]);
    }

    public function createPositionAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $form->get('submit')->setValue('Create');
        $form->get('position')->setLabel('Name');
        $newPositionName= $this->getRequest()->getQuery()->get('position');
        $positionId =$this->params('id');
        if ($newPositionName != null & isset($_GET['submit'])){
            $this->adminTable->createPosition($newPositionName, $positionId);
            return $this->redirect()->toRoute('admin', ['action' => 'position']);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'position']);
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function changePositionAction()
    {
        $this->redirectToEntrance();
        $form = new AdminForm();
        $form->get('submit')->setValue('Change');
        $form->get('position')->setLabel('New name');
        $newPositionName = $this->getRequest()->getQuery()->get('position');
        $positionId = $this->params('id');
        if ($newPositionName != null & isset($_GET['submit']))
        {
            $this->adminTable->createPosition($newPositionName, $positionId);
            return $this->redirect()->toRoute('admin', ['action' => 'position']);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'position']);
        }

        $this->layoutView();
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function deletePositionAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $form->get('submit')->setValue('Yes');
        $form->get('submitCancel')->setValue('No');
        $positionId = $this->params('id');
        if (isset($_GET['submit']))
        {
            $this->adminTable->deletePosition($positionId);
            return $this->redirect()->toRoute('admin', ['action' => 'position']);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'position']);
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function listTelephonesAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $userId = $this->params('id');
        $form->get('submitCancel')->setValue('Back');
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        return new ViewModel([
            'telephones' => $this->adminTable->fetchTelephones($userId),
            'form'       => $form,
            'route'      => $this->route,
        ]);
    }

    public function addTelephoneAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $form->get('submit')->setValue('Add');
        $newNumberPhone = $this->getRequest()->getQuery()->get('telephone');
        $userId = $this->params('id');
        if (ctype_digit($newNumberPhone) == true & $newNumberPhone != null & isset($_GET['submit']))
        {
           $this->adminTable->saveTelephone($newNumberPhone, $userId);
            return $this->redirect()->toRoute('admin', ['action' => 'listtelephones', 'id' => $userId]);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'listtelephones', 'id' => $userId]);
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function deleteTelephoneAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $form->get('submit')->setValue('Yes');
        $form->get('submitCancel')->setValue('No');
        $userId = $this->params('id');
        $telephoneId = $this->params('dopId');
        if (isset($_GET['submit']))
        {
            $this->adminTable->deleteTelephone($telephoneId);
            return $this->redirect()->toRoute('admin', ['action' => 'listtelephones', 'id' => $userId]);
        };
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'listtelephones', 'id' => $userId]);
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function listEmailsAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $userId = $this->params('id');
        $form->get('submitCancel')->setValue('Back');
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        return new ViewModel([
            'emails' => $this->adminTable->fetchEmails($userId),
            'form'   => $form,
            'route'  => $this->route,
        ]);
    }

    public function addEmailAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $form->get('submit')->setValue('Add');
        $newEmail = $this->getRequest()->getQuery()->get('email');
        $userId = $this->params('id');
        if ($newEmail != null & isset($_GET['submit']))
        {
            $this->adminTable->saveEmail($newEmail, $userId);
            return $this->redirect()->toRoute('admin', ['action' => 'listemails', 'id' => $userId]);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'listemails', 'id' => $userId]);
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function deleteEmailAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $form->get('submit')->setValue('Yes');
        $form->get('submitCancel')->setValue('No');
        $userId = $this->params('id');
        $emailId = $this->params('dopId');
        $this->layoutView();
        if (isset($_GET['submit']))
        {
            $this->adminTable->deleteEmail($emailId);
            return $this->redirect()->toRoute('admin', ['action' => 'listemails', 'id' => $userId]);
        };
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('admin', ['action' => 'listemails', 'id' => $userId]);
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }
}