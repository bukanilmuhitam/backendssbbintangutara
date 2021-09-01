<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Student;

class Students extends ResourceController {


    use ResponseTrait;
    public function index()
    {
        $model = new Student;
        $data = $model->findAll();
        return $this->respond($data , 200);
    }
 

    public function show($id = null)
    {
        $model = new Student;
        $data = $model->where(["id" => $id])->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }
 
    
    public function create()
    {
        $model = new Student();
        $fullname = $this->request->getPost('fullname');
        $date_of_birth = $this->request->getPost('date_of_birth');
        $height =  $this->request->getPost('height');
        $weight =  $this->request->getPost('weight');
        $parent_income =  $this->request->getPost('parent_income');

        
        if(!strtotime($date_of_birth) || strlen($date_of_birth) != 10){
            return $this->fail('Format tanggal salah format harus dd/mm/yyyy');
            exit();
        }

        if(stripos($weight , ".")){
            return $this->fail("Inputan decimal menggunakan tanda , (koma) sebagai pemisah");
            exit();
        }

        if(stripos($parent_income , ",")){
            return $this->fail("Inputan nominal menggunakan tanda . (titik) sebagai pemisah");
            exit();
        }

        $weight = floatval(str_replace(',' , '.' , $weight));
        $parent_income = str_replace('.', ''  , $parent_income);
 

        $date_of_birth  = strtotime($date_of_birth);
        $time_now    = time();
        $diff   = $time_now - $date_of_birth;
        $age = floor($diff / (60 * 60 * 24 * 365));


        if(!$age >= 13 && $age<= 25){
            return $this->fail('Maaf, maksimal umur minimal 13 tahun maksimal 25 tahun');
            exit();
        }

        
        
        $bmi = $weight / pow(($height/100) , 2);

        if($bmi <= 18.4){
            $description_bmi = "Berat badan calon siswa kurang";
        }else if($bmi >= 18.5 && $bmi <= 24.9){
            $description_bmi = "Berat badan calon siswa normal atau ideal";
        }else if($bmi >= 25 && $bmi <= 29.9){
            $description_bmi = "Berat badan calon siswa kelebihan";
        }else {
            $description_bmi = "Calon siswa kegumukan atau obesitas";
        }
       
  
        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'address' => $this->request->getPost('address'),
            'place_of_birth' => $this->request->getPost('place_of_birth'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'age' => $age,
            'height' => $this->request->getPost('height'),
            'weight' => $weight,
            'parent_income' => $parent_income,
            'bmi' => $bmi,
            'description_bmi' => $description_bmi,
        ];

        if($model->insert($data)){
            return $this->respondCreated([
                'status'   => 201,
                'error'    => null,
                'message' => 'Data berhasil tersimpan',
            ], 201);
        }else{
            return $this->fail('Data gagal disimpan');
        }
       
    }
 
    
    public function update($id = null)
    {
        $model = new Student();
        $json = $this->request->getJSON();

     
        $fullname = $json->fullname;
        $date_of_birth = $json->date_of_birth;
        $height = $json->height;
        $weight =  $json->weight;
        $parent_income =  $json->parent_income;

      
        if(!strtotime($date_of_birth)){
            return $this->fail('Format tanggal salah');
            exit();
        }

        if(stripos($weight , ".")){
            return $this->fail("Inputan decimal menggunakan tanda , (koma) sebagai pemisah");
            exit();
        }

        if(stripos($parent_income , ",")){
            return $this->fail("Inputan nominal menggunakan tanda . (titik) sebagai pemisah");
            exit();
        }

        $weight = floatval(str_replace(',' , '.' , $weight));
        $parent_income = str_replace('.', ''  , $parent_income);
        

        $date_of_birth  = strtotime($date_of_birth);
        $time_now    = time();
        $diff   = $time_now - $date_of_birth;
        $age = floor($diff / (60 * 60 * 24 * 365));


        if(!$age >= 13 && $age<= 25){
            return $this->fail('Maaf, maksimal umur minimal 13 tahun maksimal 25tahun');
            exit();
        }
       

        $bmi = $weight / pow(($height/100) , 2);

        if($bmi <= 18.4){
            $description_bmi = "Berat badan calon siswa kurang";
        }else if($bmi >= 18.5 && $bmi <= 24.9){
            $description_bmi = "Berat badan calon siswa normal atau ideal";
        }else if($bmi >= 25 && $bmi <= 29.9){
            $description_bmi = "Berat badan calon siswa kelebihan";
        }else {
            $description_bmi = "Calon siswa kegumukan atau obesitas";
        }
       

        $data = [
            'fullname' => $json->fullname,
            'address' => $json->address,
            'place_of_birth' => $json->place_of_birth,
            'date_of_birth' => $json->date_of_birth,
            'date_of_birth' => $age,
            'height' =>$json->height,
            'weight' => $json->weight,
            'parent_income' => $json->parent_income,
            'bmi' => $bmi,
            'description_bmi' => $description_bmi,
        ];
        
        if($model->update($id , $data)){
            return $this->respondCreated([
                'status'   => 201,
                'error'    => null,
                'message' =>  'Data berhasil diupdate',
            ], 201);
        }else{
            return $this->fail('Data gagal diupdate');
        }
        
      
    }
 
   
    public function delete($id = null)
    {
        $model = new Student();
        $data = $model->find($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'message' => 'Data berhasil terhapus',
            ];
             
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }


}