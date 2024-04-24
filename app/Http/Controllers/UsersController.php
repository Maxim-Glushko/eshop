<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use View;

class UsersController extends Controller {
    
    public function getRegister() {
        return view('users/register');
    }
    
    
    
    public function postRegister(Request $request) {
        // �������� ������� ������
        $rules = User::$validation;
        $validation = Validator::make($request->input(), $rules);
        if ($validation->fails()) {
            // � ������ �������, ���������� ������� � �������� � ������ ���������� �������
            return Redirect::to('users/register')->withErrors($validation)->withInput();
        }
 
        // ���� ����������� � ��� ������������ �������
        $user = new User();
        $user->fill($request->input());
        $id = $user->register();
 
        // ����� ��������������� ��������� �� ���������� �����������
        return $this->getMessage(
            "����������� ����� ���������. ��� ���������� ����������� e-mail, ��������� ��� �����������, ������� �� ������ � ������."
        );
    }
    
    
    
    protected function getMessage($message, $redirect = false) {
        return View::make('message', array(
            'message'   => $message,
            'redirect'  => $redirect,
        ));
    }
    
    
    
    public function getActivate($userId, $activationCode) {
        // �������� ���������� ������������
        $user = User::find($userId);
        if (!$user) {
            return $this->getMessage("�������� ������ �� ��������� ��������.");
        }
 
        // �������� ��� ������������ � ��������� �����
        if ($user->activate($activationCode)) {
            // � ������ ������ �������������� ���
            Auth::login($user);
            // � ������� ��������� �� ������
            return $this->getMessage("������� �����������", "/");
        }
 
        // � ��������� ������ �������� �� ������
        return $this->getMessage("�������� ������ �� ��������� ��������, ���� ������� ������ ��� ������������.");
    }
    
    
    
    public function getLogin() {
        return View::make('users/login');
    }
    
    
    
    public function postLogin() {
        // ��������� ������� ����� ������ ��� �����������
        // (isActive => 1 ����� ��� ����, ����� ������������� ����� ������
        // �������������� ������������)
        $creds = array(
            'password' => Input::get('password'),
            'isActive'  => 1,
        );
 
        // � ����������� �� ����, ��� ������������ ������ � ���� username,
        // ��������� ��������������� ������
        $username = Input::get('username');
        if (strpos($username, '@')) {
            $creds['email'] = $username;
        } else {
            $creds['username'] = $username;
        }
 
        // �������� ������������ ������������
        if (Auth::attempt($creds, Input::has('remember'))) {
            Log::info("User [{$username}] successfully logged in.");
            return Redirect::intended();
        } else {
            Log::info("User [{$username}] failed to login.");
        }
 
        $alert = "�������� ���������� ����� (email) � ������, ���� ������� ������ ��� �� ������������.";
 
        // ���������� ������������ ����� �� ����� ����� � ��������� ����������
        // ���������� alert (withAlert)
        return Redirect::back()->withAlert($alert);
    }
    
    
    
    public function getLogout() {
        Auth::logout();
        return Redirect::to('/');
    }
    
    
}