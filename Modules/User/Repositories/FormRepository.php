<?php

namespace Modules\User\Repositories;

use Z1lab\Form\Models\Form;
use Z1lab\Form\Models\Inputs\Text;

class FormRepository
{
    /**
     * @return Form
     */
    public function formRecoveryPassword(): Form
    {
        $form = new Form;

        $inputs['email'] = new Text;

        $inputs['email']->type('email')->name('email')->col('col-12')->validate('required|email');

        $form->action(route('api.password.email'));
        $form->callback('false');
        $form->header('Recuperação de Conta', 'Informe abaixo o endereço de e-mail e nós enviaremos um e-mail com instruções para redefinir senha.');
        $form->method('POST');

        $form->createMany($inputs);

        return $form;
    }

    /**
     * @return Form
     */
    public function formChangePassword(): Form
    {
        $form = new Form;

        $inputs['old_password'] = new Text;
        $inputs['password'] = new Text;
        $inputs['password_confirmation'] = new Text;

        $inputs['old_password']->type('password')->name('old_password')->col('col-12')->validate('required');
        $inputs['password']->type('password')->name('password')->col('col-12')->validate('required');
        $inputs['password_confirmation']->type('password')->name('password_confirmation')->col('col-12')->validate('required');

        $form->action(route('api.users.password'));
        $form->callback(route('home'));
        $form->header('Criando nova Senha');
        $form->method('PUT');

        $form->createMany($inputs);

        return $form;
    }
}
