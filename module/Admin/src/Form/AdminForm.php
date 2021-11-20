<?php
namespace Admin\Form;

use Zend\Form\Form;

class AdminForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('admin');

        $this->setAttribute('method', 'GET');

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'position',
            'type' => 'Text',
            'options' => [
                'label' => 'Position ',
            ],
        ]);

        $this->add([
            'name' => 'gender',
            'type' => 'Select',
            'options' => [
                'label' => 'Gender ',
                'value_options' => [
                    '0' => 'Select your gender',
                    '2' => 'Male',
                    '1' => 'Female'
                ]
            ],
        ]);

        $this->add([
            'name' => 'older',
            'type' => 'Text',
            'options' => [
                'label' => 'Older ',
            ],
        ]);

        $this->add([
            'name' => 'under',
            'type' => 'Text',
            'options' => [
                'label' => 'Under ',
            ],
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'Text',
            'options' => [
                'label' => 'Full name, telephone, email ',
            ],
        ]);

        $this->add([
            'name' => 'active',
            'type' => 'Select',
            'options' => [
                'label' => 'Active ',
                'value_options' => [
                    '0' => '-',
                    '2' => 'Active',
                    '1' => 'Not active',
                ]
            ],
        ]);

        $this->add([
            'name' => 'adminCheck',
            'type' => 'Select',
            'options' => [
                'label' => 'Admin ',
                'value_options' => [
                    '0' => '-',
                    '2' => 'Admin',
                    '1' => 'Not admin'
                ]
            ],
        ]);

        $this->add([
            'name' => 'bithday',
            'type' => 'Date',
            'options' => [
                'label' => 'bithday ',
            ],
        ]);

        $this->add([
            'name' => 'lastname',
            'type' => 'Text',
            'options' => [
                'label' => 'Lastname ',
            ],
        ]);

        $this->add([
            'name' => 'firstname',
            'type' => 'Text',
            'options' => [
                'label' => 'Firstname ',
            ],
        ]);

        $this->add([
            'name' => 'skype',
            'type' => 'Text',
            'options' => [
                'label' => 'Skype ',
            ],
        ]);

        $this->add([
            'name' => 'patronymic',
            'type' => 'Text',
            'options' => [
                'label' => 'Patronymic ',
            ],
        ]);

        $this->add([
            'name' => 'telephone',
            'type' => 'Text',
            'options' => [
                'label' => 'Number ',
            ],
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'Text',
            'options' => [
                'label' => 'New password ',
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
            'name' => 'textMessage',
            'type' => 'Text',
            'options' => [
                'label' => 'Message ',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Go',
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

        $this->add([
            'name' => 'submitDelete',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Delete user',
                'id' => 'submitbutton',
            ],
        ]);

        $this->add([
            'name' => 'submitChangePassword',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Change password',
                'id' => 'submitbutton',
            ],
        ]);
    }
}