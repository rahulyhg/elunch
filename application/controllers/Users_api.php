<?php
require_once(APPPATH.'controllers/Base_api.php');

class Users_api extends Base_api {

    function __construct()
    {
        parent::__construct();
        $this->load->library('common');
        $this->load->model('users_model');
    }

    /**
     * Get infomation user by id
     * url: http://localhost/user/<number_of_user_id>
     * Method: GET
     * @param       int  $user_id
     * @return      json
     */
    function user_get($user_id)
    {
        $this->authenticate();
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('get_user_profile_success', 'get_user_profile_failure'));
        $response = array();
        $result = Users_model::get_user_by('id', $user_id);
        if ($result != NULL)
        {
            $user['id'] = (int)$result->id;
            $user['email'] = $result->email;
            $user['first_name'] = $result->first_name;
            $user['last_name'] = $result->last_name;
            $user['avatar_content_file'] = $result->avatar_content_file;
            $user['want_vegan_meal'] = (boolean)$result->want_vegan_meal;
            $user['floor'] = $result->floor;
            $user['shift_id'] = (int)$result->shift_id;
            $user['shift'] = $result->shift;
            $user['start_time'] = $result->start_time;
            $user['end_time'] = $result->end_time;
            $user['max_votes'] = MAX_VOTES_FOR_USER;
            $user['what_taste'] = $result->what_taste;
            $response['status'] = $messages_lang['success'];
            $response['message'] = $messages_lang['get_user_profile_success'];
            $response['data'] = $user;
        }
        else
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['get_user_profile_failure'];
        }
        // Respond with information about a user
        $this->response($response, 200);
    }

        /**
     * Get preferences
     * url: http://localhost/preferences
     * Method: GET
     * @return      json
     */
    function preferences_get()
    {
        $this->authenticate();
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('get_user_profile_success', 'get_user_profile_failure'));
        $response = array();
        $this->load->model('Preferences_model');
        $result = $this->Preferences_model->get_all_preferences();
        if ($result != NULL)
        {
            $preferences = array();
            for ($i = 0; $i < count($result); $i++)
            {
                $temp = array();
                foreach ($result as $key2 => $value2)
                {
                    if ($result[$i]->preferences_categories_id == $value2->preferences_categories_id)
                    {
                        $value2->id = (int)$value2->id;
                        $value2->preferences_categories_id = (int)$value2->preferences_categories_id;
                        array_push($temp, $value2);
                        $i = $key2;
                    }
                }
                array_push($preferences, $temp);
            }
            $response['data'] = $preferences;
            $response['status'] = $messages_lang['success'];
            $response['message'] = $messages_lang['get_user_profile_success'];
        }
        else
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['get_user_profile_failure'];
        }
        // Respond with information about a user
        $this->response($response, 200);
    }

    /**
     * Post preferences
     * url: http://localhost/preferences
     * Method: GET
     * @return      json
     */
    function preferences_post()
    {
        $this->authenticate();
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('edit_user_profile_success', 'edit_user_profile_failure'));
        $this->verify_required_params(array('user_id'));
        $user_id = $this->post('user_id');
        $preferences_ids = array();
        if ($this->post('preferences_ids') != NULL) $preferences_ids = explode(';', $this->post('preferences_ids'));
        $response = array();
        $this->load->model('Preferences_model');
        $result = $this->Preferences_model->insert_preferences_for_user($user_id, $preferences_ids);
        if ($result != NULL)
        {
            $response['status'] = $messages_lang['success'];
            $response['message'] = $messages_lang['edit_user_profile_success'];
        }
        else
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['edit_user_profile_failure'];
        }
        // Respond with information about a user
        $this->response($response, 200);
    }

    /**
     * Get infomation user by id
     * url: http://localhost/preferences
     * Method: GET
     * @param       int  $user_id
     * @return      json
     */

    /**
     * Get preferences user by id
     * url: http://localhost/user/<user_id>/preferences
     * Method: GET
     * @param       int  $user_id
     * @return      json
     */
    function user_preferences_get($user_id)
    {
        $this->authenticate();
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('get_user_profile_success', 'get_user_profile_failure'));
        $response = array();
        $this->load->model('Preferences_model');
        $result = $this->Preferences_model->get_preferences_of_user($user_id);
        if ($result != NULL)
        {
            $preferences = array();
            for ($i = 0; $i < count($result); $i++)
            {
                $temp = array();
                foreach ($result as $key2 => $value2)
                {
                    if ($result[$i]->preferences_categories_id == $value2->preferences_categories_id)
                    {
                        $value2->id = (int)$value2->id;
                        $value2->preferences_categories_id = (int)$value2->preferences_categories_id;
                        array_push($temp, $value2);
                        $i = $key2;
                    }
                }
                array_push($preferences, $temp);
            }
            $response['data'] = $preferences;
            $response['status'] = $messages_lang['success'];
            $response['message'] = $messages_lang['get_user_profile_success'];
        }
        else
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['get_user_profile_failure'];
        }
        // Respond with information about a user
        $this->response($response, 200);
    }

    /**
     * Update infomation user by id
     * url: http://localhost/user/<number_of_user_id>
     * Method: PUT
     * @param       int  $id
     * @param       int  $want_vegan_meal
     * @param       string  $what_taste
     * @return      json
     */
    function user_put($user_id)
    {
        $this->authenticate();
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('edit_user_profile_success', 'edit_user_profile_failure'));
        $data = array();
        $data['want_vegan_meal'] = (!is_null($this->put('want_vegan_meal'))) ? $this->put('want_vegan_meal') : NULL;
        $data['what_taste'] = (!is_null($this->put('what_taste'))) ? $this->put('what_taste') : NULL;
        $result = $this->users_model->edit_profile($data, $user_id);
        if ($result == TRUE)
        {
            $response['status'] = $messages_lang['success'];
            $response['message'] = $messages_lang['edit_user_profile_success'];
        }
        else
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['edit_user_profile_failure'];
        }
        $this->response($response, 200);
    }

    /**
     * Update registion_id to use google cloud message API user by id
     * url: http://localhost/user/<user_id>/gcm_regid
     * Method: PUT
     * @param       int  $user_id
     * @param       int  $want_vegan_meal
     * @param       string  $what_taste
     * @return      json
     */
    function gcm_regid_put($user_id)
    {
        $this->authenticate();
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('edit_user_profile_success', 'edit_user_profile_failure'));
        $gcm_regid = (!is_null($this->put('gcm_regid'))) ? $this->put('gcm_regid') : '';
        $result = Users_model::update_gcm_regid($user_id, $gcm_regid);
        if ($result == TRUE)
        {
            $response['status'] = $messages_lang['success'];
            $response['message'] = $messages_lang['edit_user_profile_success'];
        }
        else
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['edit_user_profile_failure'];
        }
        $this->response($response, 200);
    }

    /**
     * User Login
     * url: http://localhost/login
     * Method: POST
     * @param       string  $email
     * @param       string  $password
     * @return      json
     */
    function login_post()
    {
        // Check for required params
        $this->verify_required_params(array('email', 'password'));
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('login_success', 'login_failure'));
        // Reading post params
        $email = $this->post('email');
        $password = $this->post('password');
        // Check for validate Email
        $this->validate_email($email);
        $response = array();
        $issue_at = time();
        list($check_login, $result) = $this->users_model->login(array(
            'email' => $this->post('email'),
            'password' => $this->post('password')));
        if($check_login)
        {
            $user['id'] = (int)$result->id;
            $user['authentication_token'] = $this->jwt->encode(array(
                                'iat' => $issue_at,
                                'nbf' => $issue_at + 10,
                                'exp' => $issue_at + 172800,
                                'tok' => $result->authentication_token,
                                'email' => $email,
                                'password' => $password
                                ), $this->config->item("secret_key"));
            $user['email'] = $result->email;
            $user['first_name'] = $result->first_name;
            $user['last_name'] = $result->last_name;
            $user['want_vegan_meal'] = (boolean)$result->want_vegan_meal;
            $user['avatar_content_file'] = $result->avatar_content_file;
            $user['shift_id'] = (int)$result->shift_id;
            $user['shift'] = $result->shift;
            $user['start_time'] = $result->start_time;
            $user['end_time'] = $result->end_time;
            $user['max_votes'] = MAX_VOTES_FOR_USER;
            $user['access_point'] = $result->access_point;
            // Response
            $response['status'] = $messages_lang['success'];
            $response['message'] = $messages_lang['login_success'];
            $response['data'] = $user;
        }
        else
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['login_failure'];
        }
        $this->response($response, 200);
    }

    /**
     * User Logout
     * url: http://localhost/logout
     * Method: POST
     * @return      json
     */
    function logout_post()
    {
        $this->destroy();
    }

    /**
     * Change password of user
     * url: http://localhost/change_password
     * Method: PUT
     * @param       int  $int
     * @param       string  $current_password
     * @param       string  $new_password
     * @param       string  $confirm_new_password
     * @return      json
     */
    function change_password_put()
    {
        $this->authenticate();
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('change_password_success', 'change_password_failure', 'password_not_match'));
        $user_id = $this->put('user_id');
        $current_password = $this->put('current_password');
        $new_password = $this->put('new_password');
        $confirm_new_password = $this->put('confirm_new_password');
        $this->verify_required_params(array('user_id', 'current_password','new_password','confirm_new_password'));
        $response= array();
        if ($new_password != $confirm_new_password)
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['password_not_match'];
        }
        else
        {
            $res = $this->users_model->change_password($user_id, $current_password, $new_password);
            if ($res == TRUE)
            {
                $result = Users_model::get_user_by('id', $user_id);
                $issue_at = time();
                $user['authentication_token'] = $this->jwt->encode(array(
                                    'iat' => $issue_at,
                                    'nbf' => $issue_at + 10,
                                    'exp' => $issue_at + 172800,
                                    'email' => $result->email,
                                    'tok' => $result->authentication_token,
                                    'password' => $new_password
                                    ), $this->config->item("secret_key"));
                $response['status'] = $messages_lang['success'];
                $response['message'] = $messages_lang['change_password_success'];
                $response['data'] = $user;
            }
            else
            {
                $response['status'] = $messages_lang['failure'];
                $response['message'] = $messages_lang['change_password_failure'];
            }
        }
        $this->response($response, 200);
    }

    /**
     * Forgot password of user
     * url: http://localhost/forgot_password
     * Method: POST
     * @param       string  $email
     * @return      json
     */
    function forgot_password_post()
    {
        $this->verify_required_params(array('email'));
        $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('variables_not_valid', 'reset_password_success', 'reset_password_failure'));
        $email = $this->post('email');
        if (Users_model::is_user_exists($email))
        {
            $this->users_model->forgot_password($email);
            list($result, $token) = $this->users_model->forgot_password($email);
            if ($result)
            {
                $this->users_model->send_mail($email, $token);
                $response['status'] = $messages_lang['success'];
                $response['message'] = $messages_lang['reset_password_success'];
            }
            else
            {
                $response['status'] = $messages_lang['failure'];
                $response['message'] = $messages_lang['reset_password_failure'];
            }
        }
        else
        {
            $response['status'] = $messages_lang['failure'];
            $response['message'] = $messages_lang['variables_not_valid'];
        }
        $this->response($response, 200);
    }
    /**
    * Validating email address
    * @param  string  $email
    * @return json
    */
    function validate_email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $messages_lang = $this->common->set_language_for_server_api('users_api',
            array('variables_not_valid'));
            $response["status"] = $messages_lang['failure'];
            $response["message"] = $messages_lang['variables_not_valid'];
            $this->response($response, 200);
        }
    }
}
