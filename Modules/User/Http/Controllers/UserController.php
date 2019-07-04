<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\User\Repositories\FormRepository;
use Modules\User\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * @var FormRepository
     */
    protected $formRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     *
     * @param  FormRepository  $formRepository
     * @param  UserRepository  $userRepository
     */
    public function __construct(FormRepository $formRepository, UserRepository $userRepository)
    {
        $this->formRepository = $formRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword()
    {
        $form = $this->formRepository->formChangePassword();

        return view('user.change-password', compact('form'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendPasswordRecovery()
    {
        $form = $this->formRepository->formRecoveryPassword();

        return view('user.send-password-recovery', compact('form'));
    }
}
