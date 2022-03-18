<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Users extends BaseController
{
  public function index()
  {
    $data = [
      "title" => "Daftar Pengguna",
      "nav_active" => "users"
    ];

    return view('user_list', $data);
  }

  public function login()
  {
    $is_valid = $this->validate([
      "username" => "required|is_string|max_length[25]",
      "password" => "required|is_string|min_length[6]",
    ]);

    if (false === $is_valid) {
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

  public function profile()
  {
    $id = session()->get('id');
    return $this->detailUser($id);
  }
  
  public function editProfile()
  {
    $is_valid = $this->validate([
      "name" => [
        "label" => "Nama",
        "rules" => "required|max_length[50]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "max_length" => "Panjang maksimal 50 karakter."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $id = session()->get('id');
    $name = $this->request->getPost('name');

    $m_users = new UsersModel();

    $data['name'] = htmlentities($name, ENT_QUOTES, 'UTF-8');

    if ($m_users->update($id, $data)) {
      return redirect()->back()->with("message", "Perubahan berhasil disimpan.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Perubahan gagal disimpan.")->with("message_type", "danger");
    }
  }

  public function profileEditPassword()
  {
    $is_valid = $this->validate([
      "password" => [
        "label" => "Password",
        "rules" => "required|string|min_length[6]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "string" => "{field} harus berupa string.",
          "min_length" => "Panjang minimal 6 karakter."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $id = session()->get('id');
    $password = $this->request->getPost('password');

    $m_users = new UsersModel();

    $data['password'] = password_hash($password, PASSWORD_BCRYPT);

    if ($m_users->update($id, $data)) {
      return redirect()->back()->with("message", "Password berhasil diubah.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Password gagal diubah.")->with("message_type", "danger");
    }
  }

  public function listUser()
  {
    $offset = $_POST['start'];
    $limit = $_POST['length'];
    $search = $_POST['search']['value'];
    $order_column = $_POST['columns'][$_POST['order'][0]['column']]['data'];
    $order_dir = $_POST['order'][0]['dir'];

    $m_users = new UsersModel();
    $total_users = $m_users->totalUsers();
    $total_users_filtered = $m_users->totalUsersFiltered($search);
    $data_users = $m_users->users($search, $order_column, $order_dir, $limit, $offset);


    $data =  [
      "recordsTotal" => $total_users,
      "recordsFiltered" => $total_users_filtered,
      "data" => $data_users
    ];
    return json_encode($data);
  }

  public function addUser()
  {
    $method = $this->request->getMethod();

    if ($method === "get") {
      $data = [
        "title" => "Tambah Pengguna",
        "nav_active" => "users"
      ];

      return view('user_add', $data);
    } elseif ($method === "post") {
      $is_valid = $this->validate([
        "username" => [
          "label" => "Username",
          "rules" => "required|max_length[25]|is_unique[tb_users.username]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "max_length" => "Panjang maksimal 25 karakter.",
            "is_unique" => "{field} sudah digunakan."
          ]
        ],
        "name" => [
          "label" => "Nama",
          "rules" => "required|max_length[50]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "max_length" => "Panjang maksimal 50 karakter."
          ]
        ],
        "role" => [
          "label" => "Role",
          "rules" => "required|in_list[admin,staff]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "in_list" => "{field} tidak valid."
          ]
        ],
        "password" => [
          "label" => "Password",
          "rules" => "required|string|min_length[6]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "string" => "{field} harus berupa string.",
            "min_length" => "Panjang minimal 6 karakter."
          ]
        ],
      ]);

      if ($is_valid === false) {
        return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
      }

      $username = $this->request->getPost('username');
      $name = $this->request->getPost('name');
      $role = $this->request->getPost('role');
      $password = $this->request->getPost('password');

      $m_users = new UsersModel();

      $data['username'] = htmlentities($username, ENT_QUOTES, 'UTF-8');
      $data['name'] = htmlentities($name, ENT_QUOTES, 'UTF-8');
      $data['role'] = htmlentities($role, ENT_QUOTES, 'UTF-8');
      $data['password'] = htmlentities($password, ENT_QUOTES, 'UTF-8');

      if ($m_users->insert($data)) {
        return redirect()->back()->with("message", "Pengguna berhasil ditambahkan.")->with("message_type", "success");
      } else {
        return redirect()->back()->with("message", "Pengguna gagal ditambahkan.")->with("message_type", "danger");
      }
    }
  }

  public function detailUser($id)
  {
    $m_users = new UsersModel();
    $users = $m_users->detail_user($id);

    if (!$users) {
      throw new PageNotFoundException();
    }

    $data = [
      "title" => "Detail Pengguna",
      "nav_active" => "users",
      "data" => (array) $users
    ];

    return view("user_detail", $data);
  }

  public function editUser()
  {
    $is_valid = $this->validate([
      "id" => [
        "label" => "ID",
        "rules" => "required|integer",
        "errors" => [
          "required" => "{field} harus diisi.",
          "integer" => "{field} harus berisi bilangan bulat."
        ]
      ],
      "name" => [
        "label" => "Nama",
        "rules" => "required|max_length[50]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "max_length" => "Panjang maksimal 50 karakter."
        ]
      ],
      "role" => [
        "label" => "Role",
        "rules" => "required|in_list[admin,staff]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "in_list" => "{field} tidak valid."
        ]
      ]
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $id = $this->request->getPost('id');
    $name = $this->request->getPost('name');
    $role = $this->request->getPost('role');

    $m_users = new UsersModel();

    $data['name'] = htmlentities($name, ENT_QUOTES, 'UTF-8');
    $data['role'] = htmlentities($role, ENT_QUOTES, 'UTF-8');

    if ($m_users->update($id, $data)) {
      return redirect()->back()->with("message", "Perubahan berhasil disimpan.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Perubahan gagal disimpan.")->with("message_type", "danger");
    }
  }

  public function editPassword()
  {
    $is_valid = $this->validate([
      "id" => [
        "label" => "ID",
        "rules" => "required|integer",
        "errors" => [
          "required" => "{field} harus diisi.",
          "integer" => "{field} harus berisi bilangan bulat."
        ]
      ],
      "password" => [
        "label" => "Password",
        "rules" => "required|string|min_length[6]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "string" => "{field} harus berupa string.",
          "min_length" => "Panjang minimal 6 karakter."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $id = $this->request->getPost('id');
    $password = $this->request->getPost('password');

    $m_users = new UsersModel();

    $data['password'] = password_hash($password, PASSWORD_BCRYPT);

    if ($m_users->update($id, $data)) {
      return redirect()->back()->with("message", "Password berhasil diubah.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Password gagal diubah.")->with("message_type", "danger");
    }
  }

  public function deleteUser()
  {
    $is_valid = $this->validate([
      "id" => [
        "label" => "ID",
        "rules" => "required|integer",
        "errors" => [
          "required" => "{field} harus diisi.",
          "integer" => "{field} harus berisi bilangan bulat."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $id = $this->request->getPost('id');

    $m_users = new UsersModel();

    if ($m_users->delete($id)) {
      return redirect()->back()->with("message", "Pengguna berhasil dihapus.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Pengguna gagal dihapus.")->with("message_type", "danger");
    }
  }
}
