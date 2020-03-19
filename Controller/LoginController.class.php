<?php
namespace Student\Controller;
ini_set('display_errors', 'on');
error_reporting(E_ALL);
class LoginController {
    protected $template;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->template = new \HTML_Template_Sigma(ROOT_DIR . '/View/Template/');
    }
 
    public function registration()
    {
        $this->template->loadTemplateFile('registration.html');
        $postValues = $this->request->getPostParams();
        //var_dump($postValues); 
        $student = new \Student\Model\Entity\Student();
        $validate = false;
        if (isset($postValues['submit'])) {
            
            $student->setFirstName($postValues['firstName']);
            $student->setLastName($postValues['lastName']);
            $student->setPassword($postValues['password']);
            $student->setEmailId($postValues['emailId']);
            $student->setUserName($postValues['userName']);
            $student->setPhoneNumber($postValues['mobileNumber']);
            $student->setAddress($postValues['address']);
            $student->setCity($postValues['city']);
            $student->setPinCode($postValues['pinCode']);
            $student->setDateOfBirth($postValues['dob']);
            $student->setHobbies($postValues['hobbies']);
            $student->setCourses($postValues['courses']);
            $student->setCountry($postValues['country']);
            $student->setGender($postValues['gender']); 
            $errors = '';
            if ($student->save()) {
                header('Location: http://www.student.local/?module=login&msg=register');
            } else {
                $errors = implode(' ', $student->getErrors());
            }
        }
         $availableGender = [
            1 => 'Male',
            2 => 'Female',
           
        ];
        foreach ($availableGender as $genderId => $availableGender) {
            $selected = isset($postValues['gender']) && $postValues['gender'] == $genderId ? 'selected' : '';
            $this->template->setVariable([
                'GENDER_NAME' => $availableGender,
                'GENDER_ID'   => $genderId,
                'GENDER_SELECTED' => $selected
            ]);
            $this->template->parse('show_gender');
        }
         $availableCourses = [
            1 => 'B.Com',
            2 => 'B.Tech',
            3 => 'B.sc',
            4 => 'B.A',
        ];
        foreach ($availableCourses as $courseId => $availableCourses) {
            $selected = isset($postValues['courses']) && $postValues['courses'] == $courseId ? 'selected' : '';
            $this->template->setVariable([
                'COURSE_NAME' => $availableCourses,
                'COURSE_ID'   => $courseId,
                'COURSE_SELECTED' => $selected
            ]);
            $this->template->parse('show_courses');
        }
        $this->template->setVariable([
            'STUDENT_ID' => $student->getId(),
            'FIRST_NAME' => $student->getFirstName(),
            'LAST_NAME'  => $student->getLastName(),
            'EMAIL'      => $student->getEmailId(),
            'ADDRESS'    => $student->getAddress(),
            'CITY'       => $student->getCity(),
            'PASSWORD'   => $student->getPassword(),
            'USER_NAME'  => $student->getUserName(),
            'PINCODE'    => $student->getPinCode(),
            'COUNTRY'    => $student->getCountry(),
            'PHONE'      => $student->getPhoneNumber(),
            'GENDER'     => $student->getGender(),
            'COURSE'     => $student->getCourses(),
            'HOBBIES'    => $student->getHobbies(), 
            'DATE_OF_BIRTH' => $student->getDateOfBirth(), 
            'ERROR_MESSAGE' => (!empty($errors) ? $errors : '')
        ]);
        return $this->template->get();
        
    }
     
    public function login()
    {
    
        $this->template->loadTemplateFile('login.html');
        $postValues = $this->request->getPostParams();
       
        $successMsg = '';
        if (isset($_GET['msg']) && $_GET['msg'] == 'register') {
            $successMsg = "Student registered successfully.";
        }
        
        if (isset($postValues['login'])) {
            $user = new \Student\Model\Entity\Student();
            $user->setUserName($postValues['userName']);
            $user->setPassword($postValues['password']);
            
            if ($user->checklogin()) {
               
               $success = implode('  ', $user->getSuccess()); 
            } else {
               $error = implode('  ', $user->getErrors());
            }
        }
        $this->template->setVariable([
            'PAGE_TITLE' => 'LOGIN',
            'SUCCESS_MESSAGE' => !empty($successMsg) ? $successMsg : '',
            'LOGIN_SUCCESS' => !empty($success) ? $success : '',
            'ERROR_MESSAGE' => !empty($error) ? $error : ''
        ]);
        return $this->template->get();
        
    }
    
    public function forgetPassword()
    {
        $this->template->loadTemplateFile('forgotpage.html');  
        $postValues = $this->request->getPostParams();
        if (isset($postValues['submit'])) {
            $student = new \Student\Model\Entity\Student();
            $student->setEmailId($postValues['email']);
            if (!$student->getByEmail()) {
                $error = implode(' ', $student->getErrors());
            } else {
                $key = md5($student->getEmailId());
                $student->setResetKey($key);
                if (!$student->save()) {
                    $error = implode(' ', $student->getErrors());
                } else {
                    $resetMessage = '<a href="http://www.student.local/?module=login&act=reset&rk='. $key .'">Click here</a> to reset your password.';
                }
            }
        }

        if (!empty($resetMessage)) {
            $this->template->hideBlock('show_forget_password_form');
        }
        $this->template->setVariable([
            'ERROR_MESSAGE' => !empty($error) ? $error : '',
            'RESET_CONTENT' => $resetMessage ?? ''
        ]);

        return $this->template->get();
    } 
    
    public function resetPassword()
    {
        $this->template->loadTemplateFile('resetpage.html');
        $postValues = $this->request->getPostParams();
        if (isset($postValues['submit'])) {
            $student = new \Student\Model\Entity\Student();
            $student->setEmailId($postValues['email']);
            if (!$student->getByEmail()) {
                $error = implode(' ', $student->getErrors());
            } else {
                if (
                    $_GET['rk'] != $student->getResetKey() &&
                    $postValues['new_password'] != $postValues['confirm_password']
                ) {
                    $resetMsg = 'password mismatches,please enter correct password';
                } else {
                    $student->setPassword($postValues['new_password']);
                    $student->setResetKey(0);
                    $student->save();
                    $updateSucess = 'Password updated sucessfully';
                }
            }
        }

        $this->template->setVariable([
            'ERROR_MESSAGE' => !empty($error) ? $error : '',
            'RESET_MESSAGE' => !empty($resetMsg) ? $resetMsg : '',
            'UPDATE_MESSAGE' => !empty($updateSucess) ? $updateSucess : ''
        ]);
        return $this->template->get();
    }
}
 
