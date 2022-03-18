<?php

namespace App\Controllers;

use App\Models\OrderItemsModel;
use App\Models\TransactionsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Transactions extends BaseController
{
  public function index()
  {
    $data = [
      "title" => "Daftar Transaksi",
      "nav_active" => "transactions"
    ];

    return view('transaction_list', $data);
  }

  public function listTransaction()
  {
    $offset = $_POST['start'];
    $limit = $_POST['length'];
    $search = $_POST['search']['value'];
    $order_column = $_POST['columns'][$_POST['order'][0]['column']]['data'];
    $order_dir = $_POST['order'][0]['dir'];

    $m_transactions = new TransactionsModel();
    $total_transactions = $m_transactions->totalTransactions();
    $total_transactions_filtered = $m_transactions->totalTransactionsFiltered($search);
    $data_transactions = $m_transactions->transactions($search, $order_column, $order_dir, $limit, $offset);

    $data =  [
      "recordsTotal" => $total_transactions,
      "recordsFiltered" => $total_transactions_filtered,
      "data" => $data_transactions
    ];
    return json_encode($data);
  }

  public function detailTransaction($code, $print = false)
  {
    $m_transactions = new TransactionsModel();
    $transactions = $m_transactions->detail_transaction($code);

    if (!$transactions) {
      throw new PageNotFoundException();
    }

    $m_order_items = new OrderItemsModel();
    $order_items = $m_order_items->detail_order_item($transactions->order_id);

    if (!$order_items) {
      throw new PageNotFoundException();
    }

    $data = [
      "title" => "Detail Transaksi",
      "nav_active" => "transactions",
      "data" => $transactions,
      "order_items" => $order_items
    ];

    return $print ? view("transaction_print", $data) : view("transaction_detail", $data);
  }
}
