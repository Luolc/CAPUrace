<?php
/**
 * Created by PhpStorm.
 * User: Jeldor
 * Date: 1/28/15
 * Time: 14:01
 */

class Registration extends CI_Controller {

    /*
     * Construction for Registration Controller.
     */
    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('url', 'lib'));
        $this->load->model('people_model', 'people');
        $this->load->model('team_model', 'team');
        $this->load->model('user_model', 'user');
        $this->load->model('info_model', 'info');

        if (! $this->session->userdata('logged_in')) {
            redirect(site_url('user/login'), 'refresh');
        }

        if (! $this->session->userdata('editable')) {
            redirect(site_url('user/result'));
        }
    }

    public function index() {
        $this->load->view('header_homepage');
        $this->load->view('add_hilight_nav2');
        $query = $this->info->get_info('activity');
        $data = array(
            'text' => $query['text'],
            'publish' => $query['isdraft']
        );
        $this->load->view('registration_index', $data);
        $this->load->view('footer');
    }

    /*
     * This method let the users register individuals.
     */
    public function individual() {
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $this->load->view('header_homepage');
            $this->load->view('registration_individual');
            $this->load->view('footer');
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $school_id = $this->session->userdata('id');
            $data = $this->input->post();
            $ind_post = $data['data'];
            header('Content-Type: application/json');
            // There should be some validations here.
            $tel_set = array();
            $id_card_set = array();
            $key_set = array();
            if (!$ind_post) exit(err_msg('999'));
            foreach ($ind_post as $item_post) {
                // name
                if (!validate_name($item_post['name'])) {
                    exit(err_custom_msg('1000', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
                // gender
                if (!array_key_exists($item_post['gender'], $GLOBALS['GENDER'])) {
                    exit(err_custom_msg('1010', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
                // tel
                if (!validate_mobile($item_post['tel'])) {
                    exit(err_custom_msg('1020', array(
                        'order' => $item_post['order'] + 1,
                    )));
                } else if (array_key_exists($item_post['tel'], $tel_set)) {
                    exit(err_custom_msg('1021', array(
                        'order' => $item_post['order'] + 1,
                        'order1' => $tel_set[$item_post['tel']],
                    )));
                } else {
                    $tel_set[$item_post['tel']] = $item_post['order'] + 1;
                }
                // ifrace
                if (!array_key_exists($item_post['ifrace'], $GLOBALS['IFRACE'])) {
                    exit(err_custom_msg('1030', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
                // islam
                if (!array_key_exists($item_post['islam'], $GLOBALS['JUDGE'])) {
                    exit(err_custom_msg('1040', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
                // id_card
                if (!validate_id_card($item_post['id_card'])) {
                    exit(err_custom_msg('1050', array(
                        'order' => $item_post['order'] + 1,
                    )));
                } else if (array_key_exists($item_post['id_card'], $id_card_set)) {
                    exit(err_custom_msg('1051', array(
                        'order' => $item_post['order'] + 1,
                        'order1' => $id_card_set[$item_post['id_card']],
                    )));
                } else {
                    $id_card_set[$item_post['id_card']] = $item_post['order'] + 1;
                }
                // accommodation
                if (!array_key_exists($item_post['accommodation'], $GLOBALS['ACCOMMODATION'])) {
                    exit(err_custom_msg('1060', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
                // dinner
                if (!array_key_exists($item_post['dinner'], $GLOBALS['JUDGE'])) {
                    exit(err_custom_msg('1070', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
                // lunch
                if (!array_key_exists($item_post['lunch'], $GLOBALS['JUDGE'])) {
                    exit(err_custom_msg('1080', array(
                        'order' => $item_post['order'] + 1,
                    )));
                } else if ($item_post['ifrace'] == '1' and $item_post['lunch'] == '0') {
                    exit(err_custom_msg('1081', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
                // race
                if (!array_key_exists($item_post['race'], $GLOBALS['CAPURACE'])) {
                    exit(err_custom_msg('1090', array(
                        'order' => $item_post['order'] + 1,
                    )));
                } else if ($item_post['ifrace'] == '0' and $item_post['race'] != '0') {
                    exit(err_custom_msg('1091', array(
                        'order' => $item_post['order'] + 1,
                    )));
                } else if ($item_post['ifrace'] != '0' and ($item_post['race'] == '0' and !$item_post['ifteam'])) {
                    exit(err_custom_msg('1092', array(
                        'order' => $item_post['order'] + 1,
                    )));
                } else if ($item_post['gender'] == '1' and
                    !array_key_exists($item_post['race'], $GLOBALS['CAPURACE_M'])) {
                    exit(err_custom_msg('1093', array(
                        'order' => $item_post['order'] + 1,
                    )));
                } else if ($item_post['gender'] == '2' and
                    !array_key_exists($item_post['race'], $GLOBALS['CAPURACE_F'])) {
                    exit(err_custom_msg('1094', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
                // ifteam
                if (!array_key_exists($item_post['ifteam'], $GLOBALS['JUDGE'])) {
                    exit(err_custom_msg('1100', array(
                        'order' => $item_post['order'] + 1,
                    )));
                } else if ($item_post['ifrace'] == '0' and $item_post['ifteam'] == '1') {
                    exit(err_custom_msg('1101', array(
                        'order' => $item_post['order'] + 1,
                    )));
                }
            }
            $bill = 0;
            $ind_db = $this->people->get_people_from_school($school_id);
            foreach ($ind_db as $item_db) {
                $flag = false;
                $i = 0;
                foreach ($ind_post as $item_post) {
                    $item_post['team_key'] = individual_encode($item_post);
                    if (strcmp($item_db['team_key'], $item_post['team_key']) == 0) {
                        $flag = true;
                        unset($item_post['team_id']);
                        $fee = get_bill($item_post); // to do
                        $item_post['fee'] = $fee;
                        $this->people->update_individual($item_db['id'], $item_post);
                        $bill += $fee;
                        break;
                    }
                    $i++;
                }
                if (!$flag) {
                    $this->people->delete_people($item_db['id']);
                } else {
                    array_splice($ind_post, $i, 1);
                }
            }
            foreach ($ind_post as $item_post) {
                $item_post['team_key'] = individual_encode($item_post);
                $fee = get_bill($item_post);
                $item_post['fee'] = $fee;
                $bill += $fee;
                $this->people->add_people($item_post, $school_id);
            }
            $this->user->set_bill($school_id, $bill);
            $err_code = '200';
            exit(err_msg($err_code));
        }
    }

    /*
     * This method let the users register teams.
     */
    public function team() {
        $school_id = $this->session->userdata('id');
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['male'] = $this->people->get_male_athlete_from_school($school_id);
            $data['female'] = $this->people->get_female_athlete_from_school($school_id);
            $data['team'] = $this->team->get_team_from_school($school_id);
            $this->load->view('header_homepage');
            $this->load->view('registration_team', $data);
            $this->load->view('footer');
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = $this->input->post();
            header('Content-Type: application/json');
            $team_post = $data['data'];
            // Team Validation.
            $male_key_set = $this->people->get_male_athlete_keys_from_school($school_id);
            $female_key_set = $this->people->get_female_athlete_keys_from_school($school_id);
            $key_set = array();
            if (!$team_post) $team_post = array();
            if (sizeof($team_post) > 3) exit(err_custom_msg('2002', array()));
            foreach ($team_post as $item_post) {
                // first
                if (!array_key_exists($item_post['first'], $male_key_set)) {
                    exit(err_custom_msg('2000', array(
                        'order' => $item_post['order'],
                        'order_ind' => 1,
                    )));
                } else if (array_key_exists($item_post['first'], $key_set)) {
                    exit(err_custom_msg('2001', array(
                        'order' => $item_post['order'],
                        'order_ind' => 1,
                        'order1' => $key_set[$item_post['first']]['order'],
                        'order1_ind' => $key_set[$item_post['first']]['order_ind'],
                    )));
                } else {
                    $key_set[$item_post['first']] = array(
                        'order' => $item_post['order'],
                        'order_ind' => 1,
                    );
                }
                // second
                if (!array_key_exists($item_post['second'], $male_key_set)) {
                    exit(err_custom_msg('2000', array(
                        'order' => $item_post['order'],
                        'order_ind' => 2,
                    )));
                } else if (array_key_exists($item_post['second'], $key_set)) {
                    exit(err_custom_msg('2001', array(
                        'order' => $item_post['order'],
                        'order_ind' => 2,
                        'order1' => $key_set[$item_post['second']]['order'],
                        'order1_ind' => $key_set[$item_post['second']]['order_ind'],
                    )));
                } else {
                    $key_set[$item_post['second']] = array(
                        'order' => $item_post['order'],
                        'order_ind' => 2,
                    );
                }
                // third
                if (!array_key_exists($item_post['third'], $female_key_set)) {
                    exit(err_custom_msg('2000', array(
                        'order' => $item_post['order'],
                        'order_ind' => 3,
                    )));
                } else if (array_key_exists($item_post['third'], $key_set)) {
                    exit(err_custom_msg('2001', array(
                        'order' => $item_post['order'],
                        'order_ind' => 3,
                        'order1' => $key_set[$item_post['third']]['order'],
                        'order1_ind' => $key_set[$item_post['third']]['order_ind'],
                    )));
                } else {
                    $key_set[$item_post['third']] = array(
                        'order' => $item_post['order'],
                        'order_ind' => 3,
                    );
                }
                // fourth
                if (!array_key_exists($item_post['fourth'], $male_key_set)) {
                    exit(err_custom_msg('2000', array(
                        'order' => $item_post['order'],
                        'order_ind' => 4,
                    )));
                } else if (array_key_exists($item_post['fourth'], $key_set)) {
                    exit(err_custom_msg('2001', array(
                        'order' => $item_post['order'],
                        'order_ind' => 4,
                        'order1' => $key_set[$item_post['fourth']]['order'],
                        'order1_ind' => $key_set[$item_post['fourth']]['order_ind'],
                    )));
                } else {
                    $key_set[$item_post['fourth']] = array(
                        'order' => $item_post['order'],
                        'order_ind' => 4,
                    );
                }
            }
            $team_db = $this->team->get_team_from_school($school_id);
            $n_post = count($team_post);
            $n_db = count($team_db);
            if ($n_post >= $n_db) {
                for ($i = 0; $i < $n_db; $i++) {
                    $this->team->update_team($team_post[$i], $school_id);
                }
                for ($i = $n_db; $i < $n_post; $i++) {
                    $this->team->add_team($team_post[$i], $school_id);
                }
            } else {
                for ($i = 0; $i < $n_post; $i++) {
                    $this->team->update_team($team_post[$i], $school_id);
                }
                for ($i = $n_post; $i < $n_db; $i++) {
                    $this->team->delete_team($team_db[$i]['id'], $school_id);
                }
            }
            $err_code = '200';
            exit(err_msg($err_code));
        }
    }
}
