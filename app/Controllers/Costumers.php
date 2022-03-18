<?php

namespace App\Controllers;

use App\Models\CostumersModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Costumers extends BaseController
{
  public function index()
  {
    $data = [
      "title" => "Daftar Pelanggan",
      "nav_active" => "costumers"
    ];

    return view('costumer_list', $data);
  }

  public function listCostumer()
  {
    $offset = $_POST['start'];
    $limit = $_POST['length'];
    $search = $_POST['search']['value'];
    $order_column = $_POST['columns'][$_POST['order'][0]['column']]['data'];
    $order_dir = $_POST['order'][0]['dir'];

    $m_costumers = new CostumersModel();
    $total_costumers = $m_costumers->totalCostumers();
    $total_costumers_filtered = $m_costumers->totalCostumersFiltered($search);
    $data_costumers = $m_costumers->costumers($search, $order_column, $order_dir, $limit, $offset);


    $data =  [
      "recordsTotal" => $total_costumers,
      "recordsFiltered" => $total_costumers_filtered,
      "data" => $data_costumers
    ];
    return json_encode($data);
  }

  public function addCostumer()
  {
    $method = $this->request->getMethod();

    if ($method === "get") {
      $data = [
        "title" => "Tambah Pelanggan",
        "nav_active" => "costumers"
      ];

      return view('costumer_add', $data);
    } elseif ($method === "post") {
      $is_valid = $this->validate([
        "costumer_name" => [
          "label" => "Nama",
          "rules" => "required|max_length[50]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "max_length" => "Panjang maksimal 50 karakter."
          ]
        ],
        "costumer_contact" => [
          "label" => "No. Telp",
          "rules" => "permit_empty|numeric|max_length[15]",
          "errors" => [
            "numeric" => "{field} harus berisi angka.",
            "max_length" => "Panjang maksimal 15 karakter."
          ]
        ],
        "costumer_gender" => [
          "label" => "Jenis Kelamin",
          "rules" => "required|in_list[male,female]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "in_list" => "{field} tidak valid."
          ]
        ],
        "costumer_address" => [
          "label" => "Alamat",
          "rules" => "permit_empty|string|max_length[100]",
          "errors" => [
            "string" => "{field} harus berupa string.",
            "max_length" => "Panjang maksimal 100 karakter."
          ]
        ],
      ]);

      if ($is_valid === false) {
        return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
      }

      $costumer_name = $this->request->getPost('costumer_name');
      $costumer_contact = $this->request->getPost('costumer_contact');
      $costumer_gender = $this->request->getPost('costumer_gender');
      $costumer_address = $this->request->getPost('costumer_address');

      $m_costumers = new CostumersModel();

      $data['costumer_name'] = htmlentities($costumer_name, ENT_QUOTES, 'UTF-8');
      $data['costumer_contact'] = htmlentities($costumer_contact, ENT_QUOTES, 'UTF-8');
      $data['costumer_gender'] = htmlentities($costumer_gender, ENT_QUOTES, 'UTF-8');
      $data['costumer_address'] = htmlentities($costumer_address, ENT_QUOTES, 'UTF-8');

      if ($m_costumers->insert($data)) {
        return redirect()->back()->with("message", "Pelanggan berhasil ditambahkan.")->with("message_type", "success");
      } else {
        return redirect()->back()->with("message", "Pelanggan gagal ditambahkan.")->with("message_type", "danger");
      }
    }
  }

  public function detailCostumer($id)
  {
    $m_costumers = new CostumersModel();
    $costumers = $m_costumers->detail_costumer($id);

    if (!$costumers) {
      throw new PageNotFoundException();
    }

    $data = [
      "title" => "Detail Pelanggan",
      "nav_active" => "costumers",
      "data" => (array) $costumers
    ];

    return view("costumer_detail", $data);
  }

  public function editCostumer()
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
      "costumer_name" => [
        "label" => "Nama",
        "rules" => "required|max_length[25]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "max_length" => "Panjang maksimal 25 karakter."
        ]
      ],
      "costumer_contact" => [
        "label" => "No. Telp",
        "rules" => "permit_empty|numeric|max_length[15]",
        "errors" => [
          "numeric" => "{field} harus berisi angka.",
          "max_length" => "Panjang maksimal 15 karakter."
        ]
      ],
      "costumer_gender" => [
        "label" => "Jenis Kelamin",
        "rules" => "required|in_list[male,female]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "in_list" => "{field} tidak valid."
        ]
      ],
      "costumer_address" => [
        "label" => "Alamat",
        "rules" => "permit_empty|string|max_length[100]",
        "errors" => [
          "string" => "{field} harus berupa string.",
          "max_length" => "Panjang maksimal 100 karakter."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $id = $this->request->getPost('id');
    $costumer_name = $this->request->getPost('costumer_name');
    $costumer_contact = $this->request->getPost('costumer_contact');
    $costumer_gender = $this->request->getPost('costumer_gender');
    $costumer_address = $this->request->getPost('costumer_address');

    $m_costumers = new CostumersModel();

    $data['costumer_name'] = htmlentities($costumer_name, ENT_QUOTES, 'UTF-8');
    $data['costumer_contact'] = htmlentities($costumer_contact, ENT_QUOTES, 'UTF-8');
    $data['costumer_gender'] = htmlentities($costumer_gender, ENT_QUOTES, 'UTF-8');
    $data['costumer_address'] = htmlentities($costumer_address, ENT_QUOTES, 'UTF-8');

    if ($m_costumers->update($id, $data)) {
      return redirect()->back()->with("message", "Perubahan berhasil disimpan.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Perubahan gagal disimpan.")->with("message_type", "danger");
    }
  }

  public function deleteCostumer()
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

    $m_costumers = new CostumersModel();

    if ($m_costumers->delete($id)) {
      return redirect()->back()->with("message", "Pelanggan berhasil dihapus.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Pelanggan gagal dihapus.")->with("message_type", "danger");
    }
  }
}
