<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
   protected $table      = 'tb_users';
   protected $primaryKey = 'id';

   protected $returnType     = 'object';
   protected $useSoftDeletes = true;

   protected $allowedFields = ['username', 'name', 'password', 'role'];

   protected $useTimestamps = true;
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';
   protected $deletedField  = 'deleted_at';

   public function login($username, $password)
   {
      $this->select("id, name, password, role");
      $this->where("username", $username);
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      $result = $this->get()->getFirstRow();
      
      if ($result === null) return false;

      return password_verify($password, $result->password) ? $result : false;
   }

   /**
    * Total Users with filter deleted
    */
   public function totalUsers()
   {
      $this->selectCount("id", "total_users");
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_users;
   }

   public function totalUsersFiltered($search)
   {
      $this->selectCount("id", "total_users");
      $this->groupStart();
      $this->like("username", $search);
      $this->orLike("name", $search);
      $this->orLike("role", $search);
      $this->orLike("created_at", $search);
      $this->groupEnd();
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_users;
   }

   public function users($search, $order_column, $order_dir, $limit, $offset)
   {
      $this->select("id, username, name, role, created_at");
      $this->groupStart();
      $this->like("username", $search);
      $this->orLike("name", $search);
      $this->orLike("role", $search);
      $this->orLike("created_at", $search);
      $this->groupEnd();
      $this->where("deleted_at IS NULL");
      $this->orderBy($order_column, $order_dir);
      $this->limit($limit, $offset);
      return $this->get()->getResultObject();
   }

   public function detail_user($id)
   {
      $this->select("id, username, name, role, created_at");
      $this->where("id", $id);
      $this->where("deleted_at IS NULL");
      $this->limit(1);
      return $this->get()->getFirstRow();
   }

   /**
    * Total Users without filter
    */
   public function total_users()
   {
      $this->selectCount("id", "total_users");
      $this->limit(1);
      return $this->get()->getFirstRow()->total_users;
   }
}
