<?php
namespace Student\Model\Entity;

class Student {
    protected $id;
    protected $errors = [];
    protected $success = [];
    protected $firstName;
    protected $lastName;
    protected $emailId;
    protected $password;
    protected $userName;
    protected $dateOfBirth;
    protected $phoneNumber;
    protected $gender;
    protected $address;
    protected $city;
    protected $pinCode;
    protected $country;
    protected $hobbies;
    protected $courses;
    protected $resetKey;
    // protected $qualifications = [];
   
    public function __construct($id = 0)
    {
        $this->resetKey = '';
        if (!empty($id)) {
            $this->id = $id;
            $this->get();
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function setResetKey($resetKey)
    {
        $this->resetKey = $resetKey;
    }

    public function getResetKey()
    {
        return $this->resetKey;
    }

    public function  setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function  setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function  setEmailId($emailId)
    {
        $this->emailId = $emailId;
    }

    public function getEmailId()
    {
        return $this->emailId;
    }
   
    public function setPassword($password)
    {
         $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }
    
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function  setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function  setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function  setGender($gender)
   {
       $this->gender = $gender;
   }

    public function getGender()
    {
        return $this->gender;
    }

    public function  setAddress($address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function  setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function  setPinCode($pinCode)
    {
        $this->pinCode = $pinCode;
    }

    public function getPinCode()
    {
        return $this->pinCode;
    }

    public function  setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function  setHobbies($hobbies)
    {
        $this->hobbies = $hobbies;
    }

    public function getHobbies()
    {
        return $this->hobbies;
    }

    public function  setCourses($courses)
    {
        $this->courses = $courses;
    }

    public function getCourses()
    {
        return $this->courses;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getSuccess()
    {
        return $this->success;
    }

   public function validation()
    {  
        if (empty($this->firstname)) {
            $this->errors[] = 'this field is required';
        } elseif (empty($this->lastName)) {
            $this->errors[] = 'this field is required';
        } elseif (empty($this->emailId)) {
            $this->errors[] = 'this is required';
        } 
        
        if (!empty($this->errors)) {
           return false;
        } else {    
        
           return true;
        }
        
    }
    

    public function save() 
    {
        if (!empty($this->validation())) {
            return false;
        }

        $sql = (empty($this->id) ? 'INSERT INTO' : 'UPDATE') . ' `studentDetails`
            SET first_name    = "' . $this->firstName . '",
                last_name     = "' . $this->lastName .'",
                email         = "' . $this->emailId . '",
                phone_number  = "' . $this->phoneNumber . '", 
                address       = "' . $this->address . '",
                city          = "' . $this->city . '",
                pin_code      = "' . $this->pinCode . '",
                birth_date    = "' . $this->dateOfBirth . '",
                hobbies       = "' . $this->hobbies . '",
                courses       = "' . $this->courses . '",     
                country       = "' . $this->country . '",
                gender        = "' . $this->gender . '",
                password      = "' . $this->password . '",
                user_name     = "' . $this->userName . '",
                reset_key     = "' . $this->resetKey . '"' .
                (!empty($this->id) ? 'WHERE `student_id` = ' . $this->id : '');
        $result = \Student\Config\Db::getInstance()->query($sql);
        if (!$result) {
            $this->errors[] = 'Failed to ' . (empty($this->id) ? 'store' : 'update') . ' the student data.';
        }
        return $result;
    }
           

    public function delete()
    {
        if (!empty($this->validation())) {
            
            return false;
        }
        if (empty($this->id)) {
            return false;
        }

        return \Student\Config\Db::getInstance()->query(
            'DELETE FROM `studentDetails` WHERE `student_id` = ' . $this->id
        ); 
        
    }

    public function getAll()
    {
        $result = \Student\Config\Db::getInstance()->query('SELECT `student_id` FROM `studentDetails`');
        $resultArray = [];
//      echo $result->row_count;die;
        while ($row = $result->fetch_assoc()) {
            $resultArray[] = new Student($row['student_id']);
        }
        return $resultArray;
    }  
    
    public function get()
    {
        if (empty($this->id)) {
            return;
        }

        $sql  = 'SELECT * FROM `studentDetails` WHERE `student_id` = ' . $this->id;
        $result = \Student\Config\Db::getInstance()->query($sql);
        if ($result->num_rows != 1) {
            $this->errors[] = 'User not exists with the mentioned email ID.';
            return false;
        }

        $user   = $result->fetch_assoc();
        $this->id = $user['student_id'];
        $this->firstName = $user['first_name'];
        $this->lastName = $user['last_name'];
        $this->emailId = $user['email'];
        $this->phoneNumber = $user['phone_number'];
        $this->address = $user['address'];
        $this->city = $user['city'];
        $this->pincode = $user['pin_code'];
        $this->dateOfBirth = $user['birth_date'];
        $this->hobbies = $user['hobbies'];
        $this->courses = $user['courses'];
        $this->country = $user['country'];
        $this->gender = $user['gender'];
        $this->password = $user['password'];
        $this->userName = $user['user_name'];

        return true;
    } 
    
    public function getByEmail()
    {
        if (empty($this->emailId)) {
            return false;
        }

        $sql    = 'SELECT * FROM `studentDetails` WHERE `email` = "' . $this->emailId . '"';
        $result = \Student\Config\Db::getInstance()->query($sql);
        if ($result->num_rows != 1) {
            $this->errors[] = 'User not exists with the mentioned email ID.';
            return false;
        }

        $user              = $result->fetch_assoc();
        $this->id          = $user['student_id'];
        $this->firstName   = $user['first_name'];
        $this->lastName    = $user['last_name'];
        $this->emailId     = $user['email'];
        $this->phoneNumber = $user['phone_number'];
        $this->address     = $user['address'];
        $this->city        = $user['city'];
        $this->pincode     = $user['pin_code'];
        $this->dateOfBirth = $user['birth_date'];
        $this->hobbies     = $user['hobbies'];
        $this->courses     = $user['courses'];
        $this->country     = $user['country'];
        $this->gender      = $user['gender'];
        $this->password    = $user['password'];
        $this->userName    = $user['user_name'];
        $this->resetKey    = $user['reset_key'];

        return true;
    }
    
    protected function loginValidation()
    {
        if (empty($this->userName) || empty($this->password)) {
            $this->errors[] = 'UserId or Password missing';
        }
        if (!empty($this->errors)) {
           return false;
        } else {
           return true;
        }
    }
    
    public function checklogin()
    { 
       
        if (!$this->loginValidation()) {
            return false;
        }
       
        $sql = '
            SELECT user_name, password FROM `studentDetails`
            WHERE user_name = "' . $this->userName . '" AND
                password = "' . $this->password .'"
            ';
        $result = \Student\Config\Db::getInstance();
        $db = $result->query($sql);
       //echo ($sql);
       //echo get_class($result);
        if ($db && $db->num_rows == 1) {
            return true;
        } else {
            $this->errors[] = 'UserId or Password is incorrect';
            return false;
        }
    }
    public function checkEmail()
    {
         $sql = '
            SELECT email, reset_key FROM `studentDetails`
            WHERE email = "' . $this->emailId . '" AND
                reset_key = "' . $this->resetKey .'"
            ';
        $result = \Student\Config\Db::getInstance();
        $db = $result->query($sql);
       //echo ($query);
       //echo get_class($result);
        if ($db && $db->num_rows == 1) { 
          return true;
        } else {
            $this->errors[] = 'Your Email or reset key does not exist ...try again...';
            return false;
        }
    }
    
//    public function updatePassword()
//    {
//             $sql = '
//                UPDATE  `studentDetails`
//                     SET reset_key = " ' . $this->resetKey . '" ,
//                         password = " ' . $this->password . ' "
//                         WHERE email = "'.$this->emailId.'"
//                     ';
//        //echo($sql);         
//        $result = \Student\Config\Db::getInstance();
//        if($result->query($sql)) {
//            return true;
//        } else {
//            return false;
//        }
//        
//    } 

}
   