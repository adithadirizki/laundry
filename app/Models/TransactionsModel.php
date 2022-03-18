<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
   protected $table      = 'tb_transactions';
   protected $primaryKey = 'id';

   protected $returnType     = 'object';
   protected $useSoftDeletes = true;

   protected $allowedFields = ['trx_code', 'order_id', 'discount', 'additional_cost', 'cash', 'created_by'];

   protected $useTimestamps = true;
   protected $createdField  = 'created_at';
   protected $updatedField  = '';
   protected $deletedField  = '';

   /**
    * Total Transactions without filter
    */
   public function totalTransactions()
   {
      $this->selectCount("id", "total_transactions");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_transactions;
   }

   public function totalTransactionsFiltered($search)
   {
      $this->join("tb_orders b", "b.id = tb_transactions.order_id");
      $this->join("tb_users c", "c.id = b.created_by");
      $this->join("tb_costumers d", "d.id = b.ordered_by");
      $this->selectCount("tb_transactions.id", "total_transactions");
      $this->groupStart();
      $this->like("trx_code", $search);
      $this->orLike("order_code", $search);
      $this->orLike("costumer_name", $search);
      $this->orLike("name", $search);
      $this->orLike("tb_transactions.created_at", $search);
      $this->groupEnd();
      $this->limit(1);
      return $this->get()->getFirstRow()->total_transactions;
   }

   public function transactions($search, $order_column, $order_dir, $limit, $offset)
   {
      $this->join("tb_orders b", "b.id = tb_transactions.order_id");
      $this->join("tb_users c", "c.id = b.created_by");
      $this->join("tb_costumers d", "d.id = b.ordered_by");
      $this->select("tb_transactions.id, trx_code, order_code, costumer_name, name, tb_transactions.created_at");
      $this->groupStart();
      $this->like("trx_code", $search);
      $this->orLike("order_code", $search);
      $this->orLike("costumer_name", $search);
      $this->orLike("name", $search);
      $this->orLike("tb_transactions.created_at", $search);
      $this->groupEnd();
      $this->orderBy($order_column, $order_dir);
      $this->limit($limit, $offset);
      return $this->get()->getResultObject();
   }

   public function detail_transaction($code)
   {
      $this->join("tb_orders b", "b.id = tb_transactions.order_id");
      $this->join("tb_users c", "c.id = tb_transactions.created_by");
      $this->join("tb_costumers d", "d.id = b.ordered_by");
      $this->select("tb_transactions.id, trx_code, order_id, order_code, discount, additional_cost, cash, costumer_name, name, tb_transactions.created_at");
      $this->where("trx_code", $code);
      return $this->get()->getFirstRow();
   }

   public function report_transactions($start_date, $end_date)
   {
      $this->join("tb_order_items b", "b.order_id = tb_transactions.order_id");
      $this->select("trx_code, SUM(price * quantity) subtotal, discount, additional_cost, created_at");
      $this->where('DATE_FORMAT(created_at, "%Y-%m-%d") BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
      $this->groupBy("tb_transactions.order_id");
      return $this->get()->getResultObject();
   }

   /**
    * Statistics in the last 6 months
    */
   public function statistics()
   {
      $this->join("tb_order_items b", "b.order_id = tb_transactions.order_id");
      $this->select('SUM((price * quantity) + additional_cost - discount) AS income, MONTH(created_at) AS month');
      $this->where('MONTH(created_at) BETWEEN MONTH(NOW()) - 6 AND MONTH(NOW())');
      $this->groupBy("tb_transactions.order_id");
      return $this->get()->getResultObject();
   }

   
   public function total_income()
   {
      $this->join("tb_order_items b", "b.order_id = tb_transactions.order_id");
      $this->select("SUM((price * quantity) + additional_cost - discount) AS total_income");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_income;
   }
}
