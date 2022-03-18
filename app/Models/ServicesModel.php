<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicesModel extends Model
{
   protected $table      = 'tb_services';
   protected $primaryKey = 'id';

   protected $returnType     = 'object';
   protected $useSoftDeletes = true;

   protected $allowedFields = ['service_name', 'service_price', 'unit_price'];

   protected $useTimestamps = true;
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';
   protected $deletedField  = 'deleted_at';

   public function totalServices()
   {
      $this->selectCount("id", "total_services");
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_services;
   }

   public function totalServicesFiltered($search)
   {
      $this->selectCount("id", "total_services");
      $this->groupStart();
      $this->like("service_name", $search);
      $this->orLike("service_price", $search);
      $this->orLike("unit_price", $search);
      $this->orLike("created_at", $search);
      $this->groupEnd();
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_services;
   }

   public function services($search, $order_column, $order_dir, $limit, $offset)
   {
      $this->select("id, service_name, service_price, unit_price, created_at");
      $this->groupStart();
      $this->like("service_name", $search);
      $this->orLike("service_price", $search);
      $this->orLike("unit_price", $search);
      $this->orLike("created_at", $search);
      $this->groupEnd();
      $this->where("deleted_at IS NULL");
      $this->orderBy($order_column, $order_dir);
      $this->limit($limit, $offset);
      return $this->get()->getResultObject();
   }
}
