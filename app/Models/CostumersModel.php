<?php

namespace App\Models;

use CodeIgniter\Model;

class CostumersModel extends Model
{
   protected $table      = 'tb_costumers';
   protected $primaryKey = 'id';

   protected $returnType     = 'object';
   protected $useSoftDeletes = true;

   protected $allowedFields = ['costumer_name', 'costumer_contact', 'costumer_gender', 'costumer_address'];

   protected $useTimestamps = true;
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';
   protected $deletedField  = 'deleted_at';

   /**
    * Total Costumers with filter deleted
    */
   public function totalCostumers()
   {
      $this->selectCount("id", "total_costumers");
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_costumers;
   }

   public function totalCostumersFiltered($search)
   {
      $this->selectCount("id", "total_costumers");
      $this->groupStart();
      $this->like("costumer_name", $search);
      $this->orLike("costumer_contact", $search);
      $this->orLike("costumer_gender", $search);
      $this->orLike("costumer_address", $search);
      $this->orLike("created_at", $search);
      $this->groupEnd();
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_costumers;
   }

   public function costumers($search, $order_column, $order_dir, $limit, $offset)
   {
      $this->select("id, costumer_name, costumer_contact, costumer_gender, costumer_address, created_at");
      $this->groupStart();
      $this->like("costumer_name", $search);
      $this->orLike("costumer_contact", $search);
      $this->orLike("costumer_gender", $search);
      $this->orLike("costumer_address", $search);
      $this->orLike("created_at", $search);
      $this->groupEnd();
      $this->where("deleted_at IS NULL");
      $this->orderBy($order_column, $order_dir);
      $this->limit($limit, $offset);
      return $this->get()->getResultObject();
   }

   public function detail_costumer($id)
   {
      $this->select("id, costumer_name, costumer_contact, costumer_gender, costumer_address, created_at");
      $this->where("id", $id);
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow();
   }

   /**
    * Total Costumers without filter
    */
   public function total_costumers()
   {
      $this->selectCount("id", "total_costumers");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_costumers;
   }
}
