<?php

namespace App\Controllers;

use App\Models\ServicesModel;

class Services extends BaseController
{
  public function index()
  {
    $data = [
      "title" => "Daftar Layanan",
      "nav_active" => "services"
    ];

    return view('service_list', $data);
  }

  public function listService()
  {
    $offset = $_POST['start'];
    $limit = $_POST['length'];
    $search = $_POST['search']['value'];
    $order_column = $_POST['columns'][$_POST['order'][0]['column']]['data'];
    $order_dir = $_POST['order'][0]['dir'];

    $m_services = new ServicesModel();
    $total_services = $m_services->totalServices();
    $total_services_filtered = $m_services->totalServicesFiltered($search);
    $data_services = $m_services->services($search, $order_column, $order_dir, $limit, $offset);


    $data =  [
      "recordsTotal" => $total_services,
      "recordsFiltered" => $total_services_filtered,
      "data" => $data_services
    ];
    return json_encode($data);
  }

  public function addService()
  {
    $is_valid = $this->validate([
      "service_name" => [
        "label" => "Nama Layanan",
        "rules" => "required|max_length[50]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "max_length" => "Panjang maksimal 50 karakter."
        ]
      ],
      "service_price" => [
        "label" => "Harga",
        "rules" => "required|integer",
        "errors" => [
          "required" => "{field} harus diisi.",
          "integer" => "{field} harus berisi bilangan bulat.",
        ]
      ],
      "unit_price" => [
        "label" => "Satuan Harga",
        "rules" => "required|max_length[15]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "max_length" => "Panjang maksimal 15 karakter."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $service_name = $this->request->getPost('service_name');
    $service_price = $this->request->getPost('service_price');
    $unit_price = $this->request->getPost('unit_price');

    $m_services = new ServicesModel();

    $data['service_name'] = htmlentities($service_name, ENT_QUOTES, 'UTF-8');
    $data['service_price'] = htmlentities($service_price, ENT_QUOTES, 'UTF-8');
    $data['unit_price'] = htmlentities($unit_price, ENT_QUOTES, 'UTF-8');

    if ($m_services->insert($data)) {
      return redirect()->back()->with("message", "Layanan berhasil ditambahkan.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Layanan gagal ditambahkan.")->with("message_type", "danger");
    }
  }

  public function editService()
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
      "service_name" => [
        "label" => "Nama Layanan",
        "rules" => "required|max_length[50]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "max_length" => "Panjang maksimal 50 karakter."
        ]
      ],
      "service_price" => [
        "label" => "Harga",
        "rules" => "required|integer",
        "errors" => [
          "required" => "{field} harus diisi.",
          "integer" => "{field} harus berisi bilangan bulat.",
        ]
      ],
      "unit_price" => [
        "label" => "Satuan Harga",
        "rules" => "required|max_length[15]",
        "errors" => [
          "required" => "{field} harus diisi.",
          "max_length" => "Panjang maksimal 15 karakter."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $id = $this->request->getPost('id');
    $service_name = $this->request->getPost('service_name');
    $service_price = $this->request->getPost('service_price');
    $unit_price = $this->request->getPost('unit_price');

    $m_services = new ServicesModel();

    $data['service_name'] = htmlentities($service_name, ENT_QUOTES, 'UTF-8');
    $data['service_price'] = htmlentities($service_price, ENT_QUOTES, 'UTF-8');
    $data['unit_price'] = htmlentities($unit_price, ENT_QUOTES, 'UTF-8');

    if ($m_services->update($id, $data)) {
      return redirect()->back()->with("message", "Perubahan berhasil disimpan.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Perubahan gagal disimpan.")->with("message_type", "danger");
    }
  }

  public function deleteService()
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

    $m_services = new ServicesModel();

    if ($m_services->delete($id)) {
      return redirect()->back()->with("message", "Layanan berhasil dihapus.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Layanan gagal dihapus.")->with("message_type", "danger");
    }
  }
}
