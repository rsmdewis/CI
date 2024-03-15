<?php

namespace App\Controllers;

use App\Models\ModelAuth;

class Auth extends BaseController
{
    public function __construct()
    {
        helper('form');
        $this->ModelAuth = new ModelAuth();
    }
    public function login()
    {
        $data = array(
            'title' => 'Login'
        );
        return view('v_login', $data);
    }
    public function cek_login()
    {
        if ($this->validate([
            'email' => [
                'label' => 'E-Mail',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi Dan Tidak Bolh Kosong !!!'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi Dan Tidak Bolh Kosong !!!'
                ]
            ],

        ])) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $cek = $this->ModelAuth->login($email, $password);
            if ($cek) {
                session()->set('log', true);
                session()->set('nama_user', $cek['nama_user']);
                session()->set('email', $cek['email']);
                session()->set('level', $cek['level']);
                session()->set('foto', $cek['foto']);
                return redirect()->to(base_url('home'));
            }
        }else {
            session()->setFlashdata('pesan', 'Login Gagal !!, Username atau Password Tidak Cocok !!');
            return redirect()->to(base_url('auth/login'));
        }
    }
    public function register()
    {
        $data = array(
            'title' => 'Registrasi'
        );
        return view('v_register', $data);
    }
    public function save_register()
    {
        if ($this->validate([
            'nama_user' => [
                'label' => 'Nama User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi Dan Tidak Bolh Kosong !!!'
                ]
            ],
            'email' => [
                'label' => 'E-Mail',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi Dan Tidak Bolh Kosong !!!'
                ]
            ],
            'no_hp' => [
                'label' => 'No Handphone',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi Dan Tidak Bolh Kosong !!!'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi Dan Tidak Bolh Kosong !!!'
                ]
            ],
            'repassword' => [
                'label' => 'Konfimasi Password',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => '{field} Wajib Diisi Dan Tidak Bolh Kosong !!!',
                    'matches' => '{field} Pasword Tidak Sama !!!'
                ]
            ],

        ])) {
            $data = array (
                'nama_user' => $this->request->getPost('nama_user'),
                'email' => $this->request->getPost('email'),
                'no_hp' => $this->request->getPost('no_hp'),
                'password' => $this->request->getPost('password'),
                'level' => 3
            );
            $this->ModelAuth->save_register($data);
            session()->setFlashdata('pesan', 'Registrasi Berhasil !!!');
            
            
        }else {
            session()->setFlashdata('error', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('auth/register'));
        }
    }
}
