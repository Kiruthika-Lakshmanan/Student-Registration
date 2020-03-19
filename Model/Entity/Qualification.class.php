<?php
/*
class Qualification {
    protected $id;
    protected $board;
    protected $percent;
    protected $year;
    protected $name;
    protected $student;
    
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setStudent($student)
    {
        $this->student = $student;
    }
    public function getStudent()
    {
        return $this->student;
    } 
    
    public function  setBoard($board)
    {
        $this->board = $board;
    }
    public function getBoard()
    {
        return $this->board;
    }
    public function  setPercent($percent)
    {
        $this->percent = $percent;
    }
    public function getPercent()
    {
        return $this->percent;
    } 
    public function  setYear($year)
    {
        $this->year = $year;
    }
    public function getYear()
    {
        return $this->year;
    }
    public function  setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }

   public function insert() {
        $db = DB::getInstance();
//            echo var_dump(var_export($this->student, true));die;   
            if (empty($this->id)) {
                 $sql = "INSERT INTO student_qualification(student_id,name,board,percent,year)
                 VALUES({$this->student->getId()},'$this->name','$this->board', $this->percent,$this->year)";
            } else {
                   $sql = '
                       UPDATE  studentDetails
                            SET name = "' . $this->name . '",
                                board = "'. $this->board.'",
                                percent = "' . $this->percent . '",
                                year = "' . $this->year . '",           
                                WHERE student_id = "'.$this->student->getId().'"
                            ';            
        }
        return $this->db->query($sql);
        }
      public function getQualification()
      {
        if (empty($this->id)) {
            return false;
        }

        $query  = 'SELECT * FROM studentDetails WHERE student_id = "' .$this->student->getId()  . '"';
        $result = $this->db->query($query);
        $user   = $result->fetch_assoc();
        
        $this->id = $user['student_id'];
        $this->name = $user['name'];
        $this->board = $user['board'];
        $this->percent = $user['percent'];
        $this->year = $user['year'];
        $this->id = $user['id']; 
//          $result = $this->db->query('SELECT * FROM student_qualification WHERE student_id = "' .$this->$this->student->getId()  . '"'); 
//          $resultArray = [];
//           while ($row = $result->fetch_assoc()) {
//            $resultArray[] = new User($row['id']);
//        }
//        return $resultArray;
      }
      public function deleteQualification()
      {
         if(!empty($this->id)) {
               $sql = 'DELETE * FROM student_qualification WHERE student_id = "' .$this->student->getId()  . '"';
                return $this->db->query($sql); 
         }
      }
}
       
   



