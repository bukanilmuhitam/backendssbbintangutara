<?php namespace App\Filters;
 
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
 
Class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $apikey = '775e1ed5-b481-4a75-81e4-81022ee54fa5';
        if(isset($_SERVER['HTTP_X_API_KEY'])){
            $getapiheader = $_SERVER['HTTP_X_API_KEY'];
            if ($apikey != $getapiheader) {
              echo "<p>Apikey tidak ditemukan</p>";
              die();
            }
        }else{
          echo "<p>Api key harus disetting</p>";
          die();
        }
    }
 
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
      // Do something here
    }
}