<?php
namespace User\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Admin\Model\AdminTable;
use Admin\Model\Admin;
use Admin\Form\AdminForm;

class UserController extends AbstractActionController
{
    protected $userTable;
    protected $sessionManager;
    protected $route = 'user';

    public function __construct(AdminTable $userTable, \Zend\Session\SessionManager $sessionManager)
    {
        $this->userTable = $userTable;
        $this->sessionManager = $sessionManager;
    }

    private function layoutView()
    {
        $this->layout()->setTemplate('layout/layoutUser');
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
        $container = new Container('userRegular');
        $thisUserId = $container->userRegular;
        if ($thisUserId == null)
            return $this->redirect()->toRoute('entrance', ['action' => 'index']);
        else return $thisUserId;
    }

    public function indexAction()
    {
       $this->redirectToEntrance();
       $this->layoutView();
    }

    public function userInformationAction()
    {
        $this->layoutView();
        $thisUser = $this->redirectToEntrance();
        return new ViewModel([
            'user'    => $this->userTable->fetchUser($thisUser),
            'route'   => $this->route,
            'userId'  => $thisUser,
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
        foreach ($this->userTable->fetchUser($userId) as $user):
            $form->get('active')->setValue($user->active);
            $form->get('position')->setValue($user->positionId);
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
            & $userInformation->bithday != 0 & $userInformation->skype != null;
        if (isset($_GET['submit']) & $checkFullInformation)
        {
            $this->userTable->changeUser($userInformation, $userId);
            return $this->redirect()->toRoute('user', ['action' => 'userinformation']);
        }
        if (isset($_GET['submitCancel']))
            return $this->redirect()->toRoute('user', ['action' => 'userinformation']);
        if (isset($_GET['submitChangePassword']))
            return $this->redirect()->toRoute('user', ['action' => 'changepassword', 'id' => $userId]);
        return new ViewModel([
            'form'    => $form,
            'userId'  => $userId,
        ]);
    }

    public function changePasswordAction()
    {
        $this->redirectToEntrance();
        $this->layoutView();
        $form = new AdminForm();
        $password1 = $this->getRequest()->getQuery()->get('password');
        $password2 = $this->getRequest()->getQuery()->get('password2');
        $userId = $this->params('id');
        if (isset($_GET['submit']) & $password1 == $password2)
        {
            $this->userTable->changePassword($password1, $userId);
            return $this->redirect()->toRoute('user', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('user', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function listUsersAction ()
    {
        $this->redirectToEntrance();
        $form = new AdminForm();
        $filter = $this->getFormsInformation();
        $form->get('submit')->setValue('Search');
        $this->layoutView();
        return new ViewModel([
            'users' => $this->userTable->filter($filter),
            'form'  => $form,
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
            'users'      => $this->userTable->filter($filter),
            'form'       => $form,
            'route'      => $this->route,
            'notDisplay' => $thisUser,
        ]);
    }

    public function messengerAction()
    {
        $this->layoutView();
        $interlocutorId = $this->params('id');
        $thisUser=$this->redirectToEntrance();
        $form = new AdminForm();
        $textNewMessage = $this->getRequest()->getQuery()->get('textMessage');
        if (isset($_GET['submit']) & $textNewMessage != null)
        {
            $this->userTable->addNewMessage($thisUser, $interlocutorId, $textNewMessage);
            return $this->redirect()->toRoute('user', ['action' => 'messenger', 'id' => $interlocutorId]);
        }
        return new ViewModel([
            'listMessagesUrl' => $this->url()->fromRoute('user', ['action' => 'listmessages']),
            'messages'        => $this->userTable->message($thisUser, $interlocutorId),
            'interlocutorId'  => $interlocutorId,
            'senderId'        => $thisUser,
            'form'            => $form,
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
            return $this->redirect()->toRoute('user', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        return new ViewModel([
            'emails' => $this->userTable->fetchEmails($userId),
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
            $this->userTable->saveEmail($newEmail, $userId);
            return $this->redirect()->toRoute('user', ['action' => 'listemails', 'id' => $userId]);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('user', ['action' => 'listemails', 'id' => $userId]);
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
            $this->userTable->deleteEmail($emailId);
            return $this->redirect()->toRoute('user', ['action' => 'listemails', 'id' => $userId]);
        };
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('user', ['action' => 'listemails', 'id' => $userId]);
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
            return $this->redirect()->toRoute('user', ['action' => 'changeuserinformation', 'id' => $userId]);
        }
        return new ViewModel([
            'telephones' => $this->userTable->fetchTelephones($userId),
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
            $this->userTable->saveTelephone($newNumberPhone, $userId);
            return $this->redirect()->toRoute('user', ['action' => 'listtelephones', 'id' => $userId]);
        }
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('user', ['action' => 'listtelephones', 'id' => $userId]);
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
        $this->layoutView();
        if (isset($_GET['submit']))
        {
            $this->userTable->deleteTelephone($telephoneId);
            return $this->redirect()->toRoute('user', ['action' => 'listtelephones', 'id' => $userId]);
        };
        if (isset($_GET['submitCancel']))
        {
            return $this->redirect()->toRoute('user', ['action' => 'listtelephones', 'id' => $userId]);
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }
}

