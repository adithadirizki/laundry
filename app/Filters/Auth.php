<?php namespace App\Filters;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
      // only has login
      if (!session()->has('id')) {
         return redirect()->to(base_url('/login'));
      }

      // only admin
      if (isset($arguments) && $arguments[0] === "onlyadmin") {
        if (session()->get('role') === "staff") {
          throw new PageNotFoundException();
        }
      }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}