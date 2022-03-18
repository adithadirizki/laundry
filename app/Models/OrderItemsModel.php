<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemsModel extends Model
{
   protected $table      = 'tb_order_items';
   protected $primaryKey = 'id';

   protected $returnType     = 'object';
   protected $useSoftDeletes = true;

   protected $allowedFields = ['order_id', 'service_id', 'price', 'quantity'];

   protected $useTimestamps = true;
   protected $createdField  = '';
   protected $updatedField  = '';
   protected $deletedField  = '';

   public function detail_order_item($id)
   {
      $this->join("tb_services b", "b.id = tb_order_items.service_id");
      $this->select("tb_order_items.id, service_name, price, quantity");
      $this->where("order_id", $id);
      $this->orderBy("tb_order_items.id", "ASC");
      return $this->get()->getResultObject();
   }

   public function getTotalPay($id)
   {
      $this->selectSum("(price * quantity)", "total_pay");
      $this->where("order_id", $id);
      $this->limit(1);
      return $this->get()->getFirstRow()->total_pay;
   }
}
