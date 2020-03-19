<?php
namespace Student\Controller;

class LoginController {
    protected $template;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->template = new \HTML_Template_Sigma(ROOT_DIR . '/View/Template/');
    }

    /**
     * actual URL: http://localhost/student/login/forgotpassword
     */
    public function forgotPassword()
    {
        $this->template->loadTemplateFile('forgotPassword.html');
        return 'forgotPassword';
    }

    /**
     * http://localhost/student/login/resetpassword
     */
    public function resetPassword()
    {
        $this->template->loadTemplateFile('resetPassword.html');
        return 'resetPassword';
    }

    /**
     * 
     * http://localhost/student/login/signup
     */
    public function signup()
    {
        $this->template->loadTemplateFile('signup.html');
        $postValues = $this->request->getPostParams();
        $user = new \Student\Model\Entity\User();
        if (isset($postValues['submit'])) {
            $user->setUserId($postValues['userId']);
            $user->setFirstName($postValues['firstName']);
            $user->setMiddleInitial($postValues['middleInitial']);
            $user->setLastName($postValues['lastName']);
            $user->setEmail($postValues['email']);
            $user->setPassword($postValues['password']);
            $user->setConfirmPassword($postValues['confirmPassword']);
            $user->setAddress($postValues['address']);
            $user->setCity($postValues['city']);
            $user->setState($postValues['state']);
            $user->setPincode($postValues['pincode']);
            $user->setCountry($postValues['country']);
            $user->setPhone($postValues['phone']);
            $user->setLanguage($postValues['language']);
            $error = '';
            if (!$user->save()) {
                $error = implode(' and ', $user->getError());
            }
        }
        $availableStates = [
            1 => 'Tamilnadu',
            2 => 'Kerala',
            3 => 'Andhra Pradesh',
            4 => 'Karantaka',
            5 => 'Goa'
        ];
        foreach ($availableStates as $stateId => $availableState) {
            $selected = isset($postValues['state']) && $postValues['state'] == $stateId ? 'selected' : '';
            $this->template->setVariable([
                'STATE_NAME' => $availableState,
                'STATE_ID'   => $stateId,
                'STATE_SELECTED' => $selected
            ]);
            $this->template->parse('show_state');
        }
        $availableCountrys = [
            1 => 'India',
            2 => 'America',
            3 => 'England',
            4 => 'France',
            5 => 'Italy'
        ];
        foreach ($availableCountrys as $countryId => $availableCountry) {
            $selected = (isset($postValues['country']) && $postValues['country'] == $countryId) ? 'selected' : '';
            $this->template->setVariable([
                'COUNTRY_NAME' => $availableCountry,
                'COUNTRY_ID'   => $countryId,
                'COUNTRY_SELECTED' => $selected
            ]);
            $this->template->parse('show_country');
        }
        $availableLanguages = [
            1 => 'Tamil',
            2 => 'English',
            3 => 'Malayalam',
            4 => 'Telugu',
            5 => 'French'
        ];
        foreach ($availableLanguages as $languageId => $availableLanguage) {
            $selected = isset($postValues['language']) && $postValues['language'] == $languageId ? 'selected' : '';
            $this->template->setVariable([
                'LANGUAGE_NAME' => $availableLanguage,
                'LANGUAGE_ID'   => $languageId,
                'LANGUAGE_SELECTED' => $selected
            ]);
            $this->template->parse('show_language');
        }
        $this->template->setVariable([
            'PAGE_TITLE' => 'student Registration',
            'USER_ID'  => $user->getUserId(),
            'FIRST_NAME' => $user->getFirstName(),
            'MIDDLE_INITIAL' => $user->getMiddleInitial(),
            'LAST_NAME'  => $user->getLastName(),
            'EMAIL'      => $user->getEmail(),
            'ADDRESS'      => $user->getAddress(),
            'CITY'   => $user->getCity(),
            'STATE' => $user->getState(),
            'PINCODE'   => $user->getPincode(),
            'COUNTRY'   => $user->getCountry(),
            'PHONE'   => $user->getPhone(),
            'LANGUAGE'   => $user->getLanguage(),
            'ERROR_MESSAGE' => !empty($error) ? $error : ''
        ]);
        return $this->template->get();
    }
    public function registration()
    {
        $this->template->loadTemplateFile('registration.html');
        $postValues = $this->request->getPostParams();
        $student = new \Student\Model\Entity\Student();
        if (isset($postValues['submit'])) {
                   
                    $student->setFirstName($postValues['firstName']);
                    $student->setLastName($postValues['lastName']);
                    $student->setEmailId($postValues['emailId']);
                    $student->setPhoneNumber($postValues['mobileNumber']);
                    $student->setAddress($postValues['address']);
                    $student->setCity($postValues['city']);
                    $student->setPinCode($postValues['pinCode']);
                    $student->setDateOfBirth($postValues['dob']);
                    $student->setHobbies($postValues['hobbies']);
                    $student->setCourses($postValues['Course']);
                    $student->setCountry($postValues['country']);
                    $student->setGender($postValues['gender']); 
                    $error = '';
            if (!$user->save()) {
                $error = implode(' and ', $user->getError());
            }
        }
            $this->template->setVariable([
            'PAGE_TITLE' => 'student Registration',
            'USER_ID'  => $student->getUserId(),
            'FIRST_NAME' => $student->getFirstName(),
            'LAST_NAME'  => $student->getLastName(),
            'EMAIL'      => $student->getEmailId(),
            'ADDRESS'      => $student->getAddress(),
            'CITY'   => $student->getCity(),
            'STATE' => $student->getState(),
            'PINCODE'   => $student->getPinCode(),
            'COUNTRY'   => $student->getCountry(),
            'PHONE'   => $student->getPhoneNumber(),
            'GENDER'  => $student->getGender(),
            'HOBBIES' => $student->getHobbies(), 
            'DATE_OF_BIRTH' => $student->GetDateOfBirth(), 
            'ERROR_MESSAGE' => !empty($error) ? $error : ''
        ]);
        return $this->template->get();
    }
    /**
     * http://localhost/student/login
     */
    public function authenticate()
    {
        $this->template->loadTemplateFile('login.html');
        $postValues = $this->request->getPostParams();
        $user = new \Student\Model\Entity\User();
        if (isset($postValues['submit'])) {
            $user->setUserId($postValues['userId']);
            $user->setPassword($postValues['password']);
            $error = '';
            if (!$user->checkLogin()) {
                $error = implode('  ', $user->getError());
            }
        }
        $this->template->setVariable([
            'PAGE_TITLE' => 'LOGIN',
            'USER_ID'    => $user->getUserId(),
            'ERROR_MESSAGE' => !empty($error) ? $error : ''
        ]);
        return $this->template->get();
    }
    /**
     * 
     * http://localhost/student/login/overview
     */
    public function overView() {
        $this->template->loadTemplateFile('overview.html');
        $postValues = $this->request->getPostParams();
        $user = new \Student\Model\Entity\User();
        if (isset($postValues['submit'])) {
            $user->getAll();
        }
        $student = new \Student();
        $student->setId($_GET['id']);
        $student->getStudent();
    }
    
}