<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Auth extends BaseController
{
	public function login()
	{
		// redirect to dashboard when has login
		if (session()->has("id")) return redirect()->back();

		$method = $this->request->getMethod();
		if ($method === "get") {
			$data = [
				"title" => "Login"
			];

			return view('login', $data);
		} elseif ($method === "post") {
			$is_valid = $this->validate([
				"username" => "required|string|max_length[25]",
				"password" => "required|string|min_length[6]",
			]);

			if ($is_valid === false) {
				return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
			}

			$username = $this->request->getPost("username");
			$password = $this->request->getPost("password");

			$m_users = new UsersModel();
			$session = session();

			if ($result = $m_users->login($username, $password)) {
				$session->set("id", $result->id);
				$session->set("name", $result->name);
				$session->set("role", $result->role);
				$session->setFlashdata("message", "Login berhasil.");
				$session->setFlashdata("message_type", "success");

				return redirect()->to("/");
			} else {
				$session->setFlashdata("message", "Username atau password yang digunakan salah.");
				$session->setFlashdata("message_type", "danger");

				return redirect()->back()->withInput();
			}
		}
	}

	public function logout()
	{
		session()->destroy();
		return redirect()->to('/login');
	}
}
