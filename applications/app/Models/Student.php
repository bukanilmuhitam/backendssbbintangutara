<?php namespace App\Models;

use CodeIgniter\Model;

class Student extends Model
{

    protected $table    = 'students';
    protected $primaryKey = 'id';

    protected $allowedFields = ['fullname' , 'address' , 'place_of_birth' , 'date_of_birth' , 'age' , 'height' , 'weight' , 'parent_income' , 'bmi' , 'description_bmi'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}