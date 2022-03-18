<?php

namespace App\Controllers;

use App\Models\OrderItemsModel;
use App\Models\OrdersModel;
use App\Models\TransactionsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Orders extends BaseController
{
  public function index()
  {
    $data = [
      "title" => "Daftar Pesanan",
      "nav_active" => "orders"
    ];

    return view('order_list', $data);
  }

  public function listOrder()
  {
    $offset = $_POST['start'];
    $limit = $_POST['length'];
    $search = $_POST['search']['value'];
    $order_column = $_POST['columns'][$_POST['order'][0]['column']]['data'];
    $order_dir = $_POST['order'][0]['dir'];
    $status = $_POST['filter']['status'];

    $m_orders = new OrdersModel();
    $total_orders = $m_orders->totalOrders();
    $total_orders_filtered = $m_orders->totalOrdersFiltered($search);
    $data_orders = $m_orders->orders($status, $search, $order_column, $order_dir, $limit, $offset);

    $data =  [
      "recordsTotal" => $total_orders,
      "recordsFiltered" => $total_orders_filtered,
      "data" => $data_orders
    ];
    return json_encode($data);
  }

  public function addOrder()
  {
    $method = $this->request->getMethod();

    if ($method === "get") {
      $data = [
        "title" => "Buat Pesanan",
        "nav_active" => "orders"
      ];

      return view("order_add", $data);
    } else if ($method === "post") {

      $is_valid = $this->validate([
        "ordered_by" => [
          "label" => "Nama Pesanan",
          "rules" => "required|max_length[50]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "max_length" => "Panjang maksimal 50 karakter."
          ]
        ],
        "services.id.*" => [
          "label" => "Layanan",
          "rules" => "required|integer|greater_than[0]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "integer" => "{field} harus berisi bilangan bulat.",
            "greater_than" => "{field} harus lebih besar dari nol."
          ]
        ],
        "services.price.*" => [
          "label" => "Harga Satuan",
          "rules" => "required|integer",
          "errors" => [
            "required" => "{field} harus diisi.",
            "integer" => "{field} harus berisi bilangan bulat."
          ]
        ],
        "services.quantity.*" => [
          "label" => "Kuantitas",
          "rules" => "required|integer|greater_than[0]",
          "errors" => [
            "required" => "{field} harus diisi.",
            "integer" => "{field} harus berisi bilangan bulat.",
            "greater_than" => "{field} harus lebih besar dari nol."
          ]
        ],
      ]);

      if ($is_valid === false) {
        return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
      }

      $ordered_by = $this->request->getPost('ordered_by');
      $services = $this->request->getPost('services');

      $m_orders = new OrdersModel();

      $data['order_code'] = strtoupper(bin2hex(random_bytes(4)));
      $data['order_status'] = 0;
      $data['created_by'] = session()->get("id");
      $data['ordered_by'] = htmlentities($ordered_by, ENT_QUOTES, 'UTF-8');

      if ($resultID = $m_orders->insert($data)) {
        $m_order_items = new OrderItemsModel();
        $order_ids = array_fill(0, count($services['id']), $resultID);

        $data = array_map(function ($order_id, $service_id, $price, $quantity) {
          return [
            "order_id" => $order_id, "service_id" => $service_id, "price" => $price, "quantity" => $quantity
          ];
        }, $order_ids, $services['id'], $services['price'], $services['quantity']);

        if ($m_order_items->insertBatch($data)) {
          return redirect()->to("/order-detail/$resultID")->with("message", "Pesanan berhasil disimpan.")->with("message_type", "success");
        }
      }
      // else
      return redirect()->to('/orders')->with("message", "Pesanan gagal disimpan.")->with("message_type", "danger");
    }
  }

  public function detailOrder($id)
  {
    $m_orders = new OrdersModel();
    $orders = $m_orders->detail_order($id);

    if (!$orders) {
      throw new PageNotFoundException();
    }

    $m_order_items = new OrderItemsModel();
    $order_items = $m_order_items->detail_order_item($orders->id);

    if (!$order_items) {
      throw new PageNotFoundException();
    }

    $data = [
      "title" => "Detail Pesanan",
      "nav_active" => "orders",
      "data" => $orders,
      "order_items" => $order_items
    ];

    return view("order_detail", $data);
  }

  public function payOrder()
  {
    $is_valid = $this->validate([
      "order_id" => [
        "label" => "ID",
        "rules" => "required|integer",
        "errors" => [
          "required" => "{field} harus diisi.",
          "integer" => "{field} harus berisi bilangan bulat."
        ]
      ],
      "discount" => [
        "label" => "Diskon",
        "rules" => "required|integer",
        "errors" => [
          "required" => "{field} harus diisi.",
          "integer" => "{field} harus berisi bilangan bulat."
        ]
      ],
      "additional_cost" => [
        "label" => "Biaya Tambahan",
        "rules" => "required|integer",
        "errors" => [
          "required" => "{field} harus diisi.",
          "integer" => "{field} harus berisi bilangan bulat.",
        ]
      ],
      "cash" => [
        "label" => "Uang Tunai",
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

    $m_order_items = new OrderItemsModel();

    $trx_code = "TX-".bin2hex(random_bytes(4));
    $order_id = $this->request->getPost('order_id');
    $discount = $this->request->getPost('discount');
    $additional_cost = $this->request->getPost('additional_cost');
    $cash = $this->request->getPost('cash');
    $total_pay = $m_order_items->getTotalPay($order_id) + $additional_cost - $discount;

    // validate cash
    $is_valid = $this->validate([
      "cash" => [
        "label" => "Uang Tunai",
        "rules" => "greater_than_equal_to[$total_pay]",
        "errors" => [
          "greater_than_equal_to" => "{field} tidak boleh kurang dari {param}."
        ]
      ],
    ]);

    if ($is_valid === false) {
      return redirect()->back()->withInput()->with("errors", $this->validator->getErrors());
    }

    $m_transactions = new TransactionsModel();

    $data['trx_code'] = strtoupper($trx_code);
    $data['order_id'] = $order_id;
    $data['discount'] = $discount;
    $data['additional_cost'] = $additional_cost;
    $data['cash'] = $cash;
    $data['created_by'] = session()->get('id');

    if ($m_transactions->insert($data)) {
      $m_orders = new OrdersModel();
      
      $data = ['order_status' => 1];
      if ($m_orders->update($order_id, $data)) {
        return redirect()->to("/transaction-detail/$trx_code")->with("message", "Pembayaran berhasil.")->with("message_type", "success");
      } else {
        return redirect()->back()->with("message", "Pembayaran gagal.")->with("message_type", "danger");
      }
    }
  }

  public function cancelOrder()
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

    $m_orders = new OrdersModel();

    $data = ['order_status' => 2];

    if ($m_orders->update($id, $data)) {
      return redirect()->back()->with("message", "Pesanan berhasil dibatalkan.")->with("message_type", "success");
    } else {
      return redirect()->back()->with("message", "Pesanan gagal dibatalkan.")->with("message_type", "danger");
    }
  }
}
