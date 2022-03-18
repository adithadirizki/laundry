<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdersModel extends Model
{
   protected $table      = 'tb_orders';
   protected $primaryKey = 'id';

   protected $returnType     = 'object';
   protected $useSoftDeletes = true;

   protected $allowedFields = ['order_code', 'order_status', 'ordered_by', 'created_by'];

   protected $useTimestamps = true;
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';
   protected $deletedField  = 'deleted_at';

   /**
    * Total Orders with filter deleted
    */
   public function totalOrders()
   {
      $this->selectCount("id", "total_orders");
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_orders;
   }

   public function totalOrdersFiltered($search)
   {
      $this->join("tb_users b", "b.id = tb_orders.created_by");
      $this->join("tb_costumers c", "c.id = tb_orders.ordered_by");
      $this->selectCount("tb_orders.id", "total_orders");
      $this->groupStart();
      $this->like("order_code", $search);
      $this->orLike("order_status", $search);
      $this->orLike("costumer_name", $search);
      $this->orLike("name", $search);
      $this->orLike("tb_orders.created_at", $search);
      $this->groupEnd();
      $this->where("tb_orders.deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_orders;
   }

   public function orders($status, $search, $order_column, $order_dir, $limit, $offset)
   {
      $this->join("tb_users b", "b.id = tb_orders.created_by");
      $this->join("tb_costumers c", "c.id = tb_orders.ordered_by");
      $this->join("tb_transactions d", "d.order_id = tb_orders.id", "left");
      $this->select("tb_orders.id, order_code, order_status, trx_code, ordered_by, costumer_name, tb_orders.created_by, name, tb_orders.created_at");
      $this->groupStart();
      $this->like("order_code", $search);
      $this->orLike("order_status", $search);
      $this->orLike("costumer_name", $search);
      $this->orLike("name", $search);
      $this->orLike("tb_orders.created_at", $search);
      $this->groupEnd();
      is_numeric($status) ? $this->where("order_status", $status) : null;
      $this->where("tb_orders.deleted_at IS NULL");
      $this->orderBy($order_column, $order_dir);
      $this->limit($limit, $offset);
      return $this->get()->getResultObject();
   }

   public function detail_order($id)
   {
      $this->join("tb_users b", "b.id = tb_orders.created_by");
      $this->join("tb_costumers c", "c.id = tb_orders.ordered_by");
      $this->select("tb_orders.id, order_code, order_status, name, costumer_name, tb_orders.created_at");
      $this->where("tb_orders.id", $id);
      $this->where("order_status", 0);
      $this->where("tb_orders.deleted_at IS NULL");
      return $this->get()->getFirstRow();
   }

   /**
    * Total Orders without filter
    */
   public function total_orders()
   {
      $this->selectCount("id", "total_orders");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_orders;
   }

   /**
    * Statistics in the last 7 days
    */
   public function statistics()
   {
      $this->select('COUNT(id) AS total_order, DAYOFWEEK(created_at) AS dayofweek');
      $this->where('DAYOFYEAR(created_at) BETWEEN DAYOFYEAR(NOW()) - 7 AND DAYOFYEAR(NOW())');
      $this->groupBy("dayofweek");
      return $this->get()->getResultObject();
   }
}
