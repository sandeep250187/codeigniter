<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 public function __construct() {
        parent::__construct();
        require_once APPPATH . 'third_party/PHPExcel.php';
        $this->excel = new PHPExcel();
    }

    public function index() {
        if (!empty($_FILES)) {
            if (isset($_FILES['file']['name'])) {
                $path = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $phone = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $email = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $status = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $data = array(
                            'name' => $name,
                            'phone' => $phone,
                            'email' => $email,
                            'status' => $status
                        );
                        $this->db->insert('tbl_employee', $data);
                        $insert_id = $this->db->insert_id();
                        $employee_data = array(
                            'emp_id' => $insert_id,
                            'dept_title' => 'Development',
                            'dept_manager' => 'Manager'
                        );
                        $this->db->insert('tbl_employee_department', $employee_data);
                        $employee_details = array(
                            'emp_id' => $insert_id,
                            'employee_postal_address' => '110049',
                            'employee_permanent_address' => '709 Ghaziabad'
                        );
                        $this->db->insert('tbl_employee_address', $employee_details);
                    }
                }
                echo 'Data Inserted Successfully';
            }
        } else {
            $this->load->view('welcome_message');
        }
    }

    public function import_excel() {
        
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */