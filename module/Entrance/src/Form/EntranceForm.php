<?php
namespace Entrance\Form;

use Zend\Form\Form;

class EntranceForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('entrance');

        $this->setAttribute('method', 'GET');

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'login',
            'type' => 'Text',
            'options' => [
                'label' => 'Login ',
            ],
        ]);

        $this->add([
            'name' => 'position',
            'type' => 'Text',
            'options' => [
                'label' => 'Position ',
            ],
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'Text',
            'options' => [
                'label' => 'Password ',
            ],
        ]);

        $this->add([
            'name' => 'password2',
            'type' => 'Text',
            'options' => [
                'label' => 'Repeat password ',
            ],
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'Text',
            'options' => [
                'label' => 'Email ',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Sing In',
                'id' => 'submitbutton',
            ],
        ]);

        $this->add([
            'name' => 'submitRecoveryPassword',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Recovery password',
                'id' => 'submitbutton',
            ],
        ]);

        $this->add([
            'name' => 'submitRegistration',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Registration',
                'id' => 'submitbutton',
            ],
        ]);

        $this->add([
            'name' => 'submitCancel',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Cancel',
                'id' => 'submitbutton',
            ],
        ]);
    }
}