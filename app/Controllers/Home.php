<?php

namespace App\Controllers;

use App\Models\CostumersModel;
use App\Models\OrdersModel;
use App\Models\TransactionsModel;
use App\Models\UsersModel;

class Home extends BaseController
{
	public function index()
	{
		helper('number');
		$m_users = new UsersModel();
		$m_costumers = new CostumersModel();
		$m_orders = new OrdersModel();
		$m_transactions = new TransactionsModel();

		$curr_day = date("w"); // 0 for Sunday
		$curr_month = date("n"); // 1 for January

		$transaction = [
			"months" => ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
			"data" => array_fill(0, 12, 0)
		];

		$order = [
			"days" => ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
			"data" => array_fill(0, 7, 0)
		];

		$statistic_transactions = $m_transactions->statistics();
		foreach ($statistic_transactions as $value) {
			$transaction['data'][$value->month - 1] += $value->income;
		}

		$statistic_orders = $m_orders->statistics();
		foreach ($statistic_orders as $value) {
			$order['data'][$value->dayofweek - 1] = $value->total_order;
		}

		// reindex - current date will pass to last index
		$splice = array_splice($transaction['months'], $curr_month);
		$transaction['months'] = array_merge($splice, $transaction['months']);
		array_splice($transaction['months'], 0, 6); // cut 6 months
		$splice = array_splice($transaction['data'], $curr_month);
		$transaction['data'] = array_merge($splice, $transaction['data']);
		array_splice($transaction['data'], 0, 6); // cut 6 months

		$splice = array_splice($order['days'], $curr_day + 1);
		$order['days'] = array_merge($splice, $order['days']);
		$splice = array_splice($order['data'], $curr_day + 1);
		$order['data'] = array_merge($splice, $order['data']);

		$data = [
			"title" => "Selamat Datang, " . session()->get("name"),
			"breadcrum" => "Dashboard",
			"nav_active" => "dashboard",
			"data" => (object) [
				"total_users" => $m_users->total_users(),
				"total_costumers" => $m_costumers->total_costumers(),
				"total_orders" => $m_orders->total_orders(),
				"total_transactions" => $m_transactions->totalTransactions(),
				"total_income" => number_to_currency($m_transactions->total_income(), 'IDR', 'id-ID'),
				"statistic_transactions" => $transaction,
				"statistic_orders" => $order,
			]
		];

		return view("index", $data);
	}
}
