<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Boq extends AdminController{
    public function index($project_id=null){
        $this->db->select('tblboq_master.*');
        $this->db->from('tblboq_master');
        if($project_id){
            $data['proj_id'] = $project_id;
            $data['project_id']=$project_id;
            $this->db->where('tblboq_master.proj_id', $project_id);
        }
        $data['boqs'] = $this->db->get()->result();
        $this->db->where('id', $project_id);
        $data['projects'] = $this->db->get('tblprojects')->result();
        // return $data['projects']->clientid;
        

        $this->load->view('admin/boq/list', $data);
    }
    public function view($boq_id){
        // Fetch BOQ master data
        $this->db->select('*');
        $this->db->from('tblboq_master');
        $this->db->join('tblprojects','tblprojects.id = tblboq_master.proj_id','left');
        $this->db->join('tblcustomer_groups','tblprojects.clientid = tblcustomer_groups.customer_id','left');
        $this->db->where('boq_id', $boq_id);
        $data['boqs'] = $this->db->get()->result();
        $project_id = $data['boqs'][0]->proj_id;
        $customer_group_id = $data['boqs'][0]->groupid;
        $data['project_id']=$project_id;
        // Fetch BOQ details along with item information
        if (!is_null($customer_group_id)) {
        $this->db->select('tblboq_details.*, tblitems.description, tblitems.unit, tbltaxes.taxrate, tblitems.long_description, tblproject_locations.location_title, tblgroup_items.group_price');
        }
        else{
            $this->db->select('tblboq_details.*, tblitems.description, tblitems.unit, tbltaxes.taxrate, tblitems.long_description, tblproject_locations.location_title');    
        }
        $this->db->from('tblboq_details');
        // $this->db->join('tblprojects','tblprojects.id = tbl','left');
        $this->db->join('tblitems', 'tblboq_details.item_id = tblitems.id');
        $this->db->join('tblproject_locations', 'tblboq_details.loc_id = tblproject_locations.loc_id', 'left');
        $this->db->join('tbltaxes', 'tblitems.tax = tbltaxes.id', 'left');
        if (!is_null($customer_group_id)) {
            $this->db->join('tblgroup_items', 'tblgroup_items.item_id = tblitems.id AND tblgroup_items.group_id = ' . $this->db->escape($customer_group_id), 'left');
        }        
        $this->db->where('tblboq_details.boq_id', $boq_id);
        $data['boq_items'] = $this->db->get()->result();
        // echo "<pre>".$this->db->last_query();die;
        //ini_set("display_errors",1);
        // $this->db->where('id', $project_id);
        // $data['projects'] = $this->db->get('tblprojects')->result();
        $this->db->select('p.*, c.*'); // Select all columns from tblprojects and tblclients
        $this->db->from('tblprojects p');
        $this->db->join('tblclients c', 'p.clientid = c.userid'); // Join tblclients on client_id
        $this->db->where('p.id', $project_id); // Filter by project_id
        $data['projects'] = $this->db->get()->result();

        $boq_status = $data['boqs'][0]->boq_status;
        switch ($boq_status) {
            case 'Rejected':
                $data['status_class'] = 'danger';
                break;
            case 'Created':
                $data['status_class'] = 'info';
                break;
            case 'Approved':
                $data['status_class'] = 'success';
                break;
            default:
                $data['status_class'] = 'danger'; // Default to empty string if status is not recognized
                break;
        }
        // Load the view with data
        $this->load->view('admin/boq/view', $data);
    }
    public function import($proj_id)
    {
        $data['project_id']=$proj_id;
        if (!has_permission('items', '', 'create')) {
            access_denied('Items Import');
        }

        $this->load->library('import/import_items_erection', [], 'import');

        // Manually map the relevant fields from the CSV
        $databaseFields = [
            'description', // Description column
            'quantity',    // Quantity column from CSV
            'unit',        // Unit column from CSV
            'rate',        // Rate - INR column from CSV
            'tax',         // Tax column from CSV
            // Add other fields as necessary...
        ];

        $this->import->setDatabaseFields($databaseFields)
            ->setCustomFields(get_custom_fields('items'));

        if ($this->input->post('download_sample') === 'true') {
            $this->import->downloadSample();
        }

        if ($this->input->post() && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
            // Check if a BOQ ID exists, if not create one
            if (!$this->input->post('boq_id')) {
                // Insert BOQ master record
                $boq_data = [
                    'proj_id' => $proj_id,
                    'boq_title' => 'Erection', // Can be dynamic based on input
                    'boq_status' => 'created',
                    'loc_id' => $this->input->post('loc_id'), // Get location ID from post input
                    'created_by' => get_staff_user_id(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'remarks' => 'Automatically created after item import of erection',
                ];
                $this->db->insert(db_prefix() . 'boq_master', $boq_data);
                $boq_id = $this->db->insert_id(); // Get inserted BOQ ID

                // Pass BOQ ID to Import Library
                $this->import->setBoqId($boq_id);
            }

            // Continue with the CSV import
            $this->import->setSimulation($this->input->post('simulate'))
                ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                ->setFilename($_FILES['file_csv']['name'])
                ->perform();

            // Display results
            $data['total_rows_post'] = $this->import->totalRows();
            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
            }
        }

        $data['title'] = _l('import');
        $this->load->view('admin/boq/import', $data);
    }
    public function import_supply($proj_id)
    {
        $data['project_id']=$proj_id;
        if (!has_permission('items', '', 'create')) {
            access_denied('Items Import');
        }

        $this->load->library('import/import_items_erection', [], 'import');

        // Manually map the relevant fields from the CSV
        $databaseFields = [
            'description', // Description column
            'quantity',    // Quantity column from CSV
            'unit',        // Unit column from CSV
            'rate',        // Rate - INR column from CSV
            'tax',         // Tax column from CSV
            // Add other fields as necessary...
        ];

        $this->import->setDatabaseFields($databaseFields)
            ->setCustomFields(get_custom_fields('items'));

        if ($this->input->post('download_sample') === 'true') {
            $this->import->downloadSample();
        }

        if ($this->input->post() && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
            // Check if a BOQ ID exists, if not create one
            if (!$this->input->post('boq_id')) {
                // Insert BOQ master record
                $boq_data = [
                    'proj_id' => $proj_id,
                    'boq_title' => 'Supply', // Can be dynamic based on input
                    'boq_status' => 'created',
                    'loc_id' => $this->input->post('loc_id'), // Get location ID from post input
                    'created_by' => get_staff_user_id(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'remarks' => 'Automatically created after item import of supply',
                ];
                $this->db->insert(db_prefix() . 'boq_master', $boq_data);
                $boq_id = $this->db->insert_id(); // Get inserted BOQ ID

                // Pass BOQ ID to Import Library
                $this->import->setBoqId($boq_id);
            }

            // Continue with the CSV import
            $this->import->setSimulation($this->input->post('simulate'))
                ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                ->setFilename($_FILES['file_csv']['name'])
                ->perform();

            // Display results
            $data['total_rows_post'] = $this->import->totalRows();
            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
            }
        }

        $data['title'] = _l('import');
        $this->load->view('admin/boq/import', $data);
    }
    public function report($boq_id){
        // Fetch BOQ master data

        $query = "
        SELECT
            i.id AS item_id,
            i.description AS description,
            i.item_type,
            IFNULL(COALESCE(SUM(d.item_qty), 0),0) AS approved_qty,
            IFNULL(COALESCE(SUM(ri.qty), 0),0) AS requested_qty,
            COALESCE(SUM(id.qty), 0) AS issued_qty,
            IFNULL(COALESCE(SUM(CASE WHEN rm.req_status='Extra' THEN ri.qty ELSE 0 END), 0),0) AS extra_requested_qty,
            IFNULL(COALESCE(SUM(CASE WHEN rm.req_status='Extra' THEN ri.qty ELSE 0 END), 0),0) AS extra_approved_qty,
            IFNULL(COALESCE(SUM(CASE WHEN rm.req_status='Extra' THEN ri.qty ELSE 0 END), 0),0) AS extra_issued_qty,
            IFNULL(COALESCE(SUM(ri.qty) - SUM(ri.qty), 0),0) AS balance_qty
        FROM
            tblitems i
        LEFT JOIN
            tblboq_details d ON i.id = d.item_id
        LEFT JOIN
            tblrequested_items ri ON i.id = ri.item_id
        LEFT JOIN
            tblitem_issue_details id ON i.id = id.item_id
        LEFT JOIN
            tblitem_requisition_master rm ON ri.req_id = rm.req_id
        WHERE
            rm.boq_id = $boq_id
        GROUP BY
            i.id

    
";

$data['items']  = $this->db->query($query)->result();



        $this->db->select('*');
        $this->db->from('tblboq_master');
        $this->db->where('boq_id', $boq_id);
        $data['boqs'] = $this->db->get()->result();
        $project_id = $data['boqs'][0]->proj_id;
        $data['project_id']=$project_id;
        // Fetch BOQ details along with item information
        $this->db->select('tblboq_details.*, tblitems.description');
        $this->db->from('tblboq_details');
        $this->db->join('tblitems', 'tblboq_details.item_id = tblitems.id');
        $this->db->where('boq_id', $boq_id);
        $data['boq_items'] = $this->db->get()->result();

        $this->db->where('id', $project_id);
        $data['projects'] = $this->db->get('tblprojects')->result();
        $boq_status = $data['boqs'][0]->boq_status;
        switch ($boq_status) {
            case 'Rejected':
                $data['status_class'] = 'danger';
                break;
            case 'Created':
                $data['status_class'] = 'info';
                break;
            case 'Approved':
                $data['status_class'] = 'success';
                break;
            default:
                $data['status_class'] = 'danger'; // Default to empty string if status is not recognized
                break;
        }
        // Load the view with data
        $this->load->view('admin/boq/report', $data);
    }
    public function item_request($boq_id){
        // Fetch BOQ master data
        $this->db->select('*');
        $this->db->from('tblboq_master');
        $this->db->where('boq_id', $boq_id);
        $data['boqs'] = $this->db->get()->result();
        $project_id = $data['boqs'][0]->proj_id;
        $data['project_id']=$project_id;
        // Fetch BOQ details along with item information
        // $this->db->select('tblboq_details.*, tblitems.description');
        // $this->db->from('tblboq_details');
        // $this->db->join('tblitems', 'tblboq_details.item_id = tblitems.id', 'left');
        // $this->db->where('boq_id', $boq_id);
        $sql = 'SELECT * FROM 
            (SELECT item_id, IFNULL(item_qty,0) approved_qty, boq_id FROM tblboq_details WHERE boq_id = '.$boq_id.') bd
            LEFT JOIN (SELECT req_id, boq_id FROM tblitem_requisition_master) rm ON (bd.boq_id =rm.boq_id)
            LEFT JOIN (SELECT item_id, IFNULL(qty,0) requested_qty, req_id FROM tblrequested_items) rd ON (rd.req_id = rm.req_id AND bd.item_id = rd.item_id )
            LEFT JOIN (SELECT issue_id, req_id FROM tblitem_issue_master) im ON (im.req_id  = rd.req_id)
            LEFT JOIN (SELECT issue_id, item_id, IFNULL(qty,0) issued_qty, serial_no, issue_item_type FROM tblitem_issue_details) id ON (id.issue_id = im.issue_id)
            LEFT JOIN (SElECT * FROM tblitems) i ON (i.id = bd.item_id)
            WHERE rm.boq_id = '.$boq_id.'
            GROUP BY bd.item_id';
        ini_set("display_errors",1);
        $query = $this->db->query($sql);
        $data['boq_items'] = $query->result();
        // $this->db->select('bd.*');
        // $this->db->from('(SELECT item_id, item_qty, boq_id FROM tblboq_details WHERE boq_id = ' . $boq_id . ') bd');
        // $this->db->join('(SELECT req_id, boq_id FROM tblitem_requisition_master) rm', 'bd.boq_id = rm.boq_id', 'left');
        // $this->db->join('(SELECT item_id, qty, req_id FROM tblrequested_items) rd', 'rd.req_id = rm.req_id AND bd.item_id = rd.item_id', 'left');
        // $this->db->join('(SELECT issue_id, req_id FROM tblitem_issue_master) im', 'im.req_id = rd.req_id', 'left');
        // $this->db->join('(SELECT issue_id, item_id, qty, serial_no, issue_item_type FROM tblitem_issue_details) id', 'id.issue_id = im.issue_id', 'left');
        // $this->db->group_by('bd.item_id');
        
        // var_dump($data);die();
        // $data['boq_items'] = $this->db->get()->result();
        
        $this->db->where('id', $project_id);
        $data['projects'] = $this->db->get('tblprojects')->result();
        $boq_status = $data['boqs'][0]->boq_status;
        switch ($boq_status) {
            case 'Rejected':
                $data['status_class'] = 'danger';
                break;
            case 'Created':
                $data['status_class'] = 'info';
                break;
            case 'Approved':
                $data['status_class'] = 'primary';
                break;
            default:
                $data['status_class'] = 'danger'; // Default to empty string if status is not recognized
                break;
        }
        // Load the view with data
        $this->load->view('admin/boq/item-request', $data);
    }
    public function request_items($project_id) {
        // Check if form is submitted
        if ($this->input->post('request_items')) {
            // Retrieve form data
            $item_ids = $this->input->post('item_id'); // Assuming checkboxes send item IDs
            $requested_qty = $this->input->post('requested_qty');
            $remarks = $this->input->post('remarks');
            $boq_id = $this->input->post('boq_id'); // Assuming you pass boq_id with the form
    
            // Insert data into tblitem_requisition_master table
            $data = array(
                'boq_id' => $boq_id,
                'requested_at' => date('Y-m-d H:i:s'), 
                'requested_by' => $this->session->userdata('staff_user_id'),  
                'req_type' => $this->input->post('req_type'),
                'remarks' => $remarks
            );
            $this->db->insert('tblitem_requisition_master', $data);
            $req_id = $this->db->insert_id(); // Get the last inserted ID as req_id
    
             foreach ($item_ids as $index => $item_id) {
                $qty = $requested_qty[$index];     
                $data = array(
                    'item_id' => $item_id,
                    'qty' => $qty,
                    'req_id' => $req_id
                );
    
                // Insert data into the tblrequested_items table
                $this->db->insert('tblrequested_items', $data);
            }
            var_dump($item_ids);
            redirect('admin/boq/'.$project_id);
        }
    }
    public function update_requisition_status($boq_id, $req_id) {//ISSUE ITEMS
        // Check if form is submitted
        // var_dump($this->input->post());die();
        // echo $boq_id;
        if ($this->input->post('req_status')) {

            $data = array(
                'req_status' => $this->input->post('req_status'),
                'remarks' => $this->input->post('remarks')
            );
            if($this->input->post('req_status')=="Rejected"){
                $this->db->where('req_id', $req_id);
                $this->db->update('tblitem_requisition_master', $data);
            }
            else{
                // Retrieve form data for items
                $item_ids = $this->input->post('item_id'); // Assuming checkboxes send item IDs
                $requested_qty = $this->input->post('requested_item_qty');
                $remarks = $this->input->post('remarks');
                // $boq_id = $this->input->post('boq_id'); // Assuming you pass boq_id with the form
                
                $data = array(
                    'req_id' => $req_id,
                    'issued_at' => date('Y-m-d H:i:s'), 
                    'issued_by' => $this->session->userdata('staff_user_id'),
                    'remarks' => $remarks
                );
                $this->db->insert('tblitem_issue_master', $data);
                $issue_id = $this->db->insert_id(); // Get the last inserted ID as req_id
                $data = array(
                    'req_status' => $this->input->post('req_status')
                );
                $this->db->where('req_id', $req_id);
                $this->db->update('tblitem_requisition_master', $data);
                foreach ($item_ids as $index => $item_id) {
                    $qty = $requested_qty[$index];     
                    $data = array(
                        'issue_id' => $issue_id,
                        'item_id' => $item_id,
                        'qty' => $qty,
                    );
        
                    $this->db->insert('tblitem_issue_details', $data);
                }
            }
            // var_dump($item_ids);
            redirect('admin/boq/requisition/'.$boq_id.'/'.$req_id);
        }
    }
    public function requisitions($boq_id, $req_id=null){//List requisitions OR single Requisition
        $data['boq_id'] = $boq_id;
        $this->db->select('tblitem_requisition_master.*, tblboq_master.proj_id');
        $this->db->from('tblitem_requisition_master');
        $this->db->join('tblboq_master','tblboq_master.boq_id = tblitem_requisition_master.boq_id','left');
        $this->db->where('tblitem_requisition_master.boq_id', $boq_id);
        if(isset($req_id)){
            $this->db->where('tblitem_requisition_master.req_id', $req_id);
        } 
        $data['requisitions'] = $this->db->get()->result();
        $project_id = $data['requisitions'][0]->proj_id;

        $this->db->where('id', $boq_id);
        // echo $this->db->last_query();
        $data['projects'] = $this->db->get('tblprojects')->result();
        
        $this->db->where('id', $project_id);
        $data['projects'] = $this->db->get('tblprojects')->result();
        $data['project_id']=$project_id;
        
        $req_status = $data['requisitions'][0]->req_status;
        switch ($req_status) {
            case 'Rejected':
                $data['status_class'] = 'danger';
                break;
            case 'Raised':
                $data['status_class'] = 'warning';
                break;
            case 'Approved':
                $data['status_class'] = 'primary';
                break;
            case 'Issued':
                $data['status_class'] = 'success';
                break;
            case 'Received':
                $data['status_class'] = 'pink';
                break;
            default:
                $data['status_class'] = 'default'; // Default to empty string if status is not recognized
                break;
        }
        if(isset($req_id)){        
            // $this->db->select('tblrequested_items.*, tblitems.description, tblboq_details.item_qty as approved_qty, 0 as issued_qty');
            // $this->db->from('tblrequested_items');
            // $this->db->join('tblitems', 'tblrequested_items.item_id = tblitems.id');
            // $this->db->join('tblboq_details', 'tblboq_details.item_id = tblrequested_items.item_id AND tblboq_details.boq_id = '.$boq_id);
            // $this->db->where('tblrequested_items.req_id', $req_id);
            $sql = 'SELECT rd.item_id, IFNULL(requested_qty,0) requested_qty, IFNULL(approved_qty,0) approved_qty, IFNULL(issued_qty,0) issued_qty, description, serial_no FROM 
            (SElECT item_id, IFNULL(qty,0) requested_qty, req_id FROM tblrequested_items WHERE req_id = '.$req_id.') rd
            LEFT JOIN (SELECT id, description FROM tblitems) i ON (i.id = rd.item_id)
            LEFT JOIN (SELECT req_id, boq_id, req_status, req_type, remarks FROM tblitem_requisition_master) rm ON (rm.req_id = rd.req_id)
            LEFT JOIN (SELECT boq_id, loc_id, item_id, IFNULL(item_qty,0) approved_qty, item_price FROM tblboq_details) bd ON (bd.boq_id = rm.boq_id AND bd.item_id = rd.item_id)
            LEFT JOIN (SELECT issue_id, req_id, remarks FROM tblitem_issue_master) im ON (im.req_id = rm.req_id)
            LEFT JOIN (SELECT issue_id, item_id, IFNULL(qty,0) issued_qty, serial_no, issue_item_type FROM tblitem_issue_details) id ON (id.issue_id = im.issue_id AND id.item_id = rd.item_id);';
                // echo "<pre>". $sql; die();
            // ini_set("display_errors",1);
            $query = $this->db->query($sql);
            $data['req_items'] = $query->result();
            // $data['req_items']=$this->db->get()->result();
            $this->load->view('admin/boq/requisition', $data);//Single request
        }
        else{
            $this->load->view('admin/boq/requisitions', $data);//All Requests List
        }
        // $this->load->view('admin/boq/requisitions', $data);//All requests

    }
    public function issue($boq_id, $req_id=null){// DISPLAY ISSUE VIEW
        // ini_set("display_errors",1);

        
        $this->db->select('tblitem_issue_master.*');
        $this->db->from('tblitem_issue_master');
        $this->db->where('tblitem_issue_master.req_id', $req_id);
        if(isset($req_id)){
            $this->db->where('tblitem_issue_master.req_id', $req_id);
        } 
        $data['boq_id']=$boq_id;
        $data['issues'] = $this->db->get()->result();
        $data['issue_id'] =$issue_id = $data['issues'][0]->issue_id;
        // $this->db->where('id', $issue_id);
        // $data['projects'] = $this->db->get('tblprojects')->result();
        // if(isset($req_id)){
            // $req_status = $data['issues'][0]->req_status;
            // switch ($req_status) {
            //     case 'Rejected':
            //         $data['status_class'] = 'danger';
            //         break;
            //     case 'Raised':
            //         $data['status_class'] = 'warning';
            //         break;
            //     case 'Approved':
            //         $data['status_class'] = 'success';
            //         break;
            //     default:
            //         $data['status_class'] = 'danger'; // Default to empty string if status is not recognized
            //         break;
            // }
            $this->db->select('tblitem_issue_details.issue_id, tblitem_issue_details.item_id, tblitem_issue_details.qty as issued_qty, tblitems.description, tblitems.long_description, 0 as approved_qty');
            $this->db->from('tblitem_issue_details');
            $this->db->join('tblitems', 'tblitem_issue_details.item_id = tblitems.id');
            // $this->db->join('tblboq_details', 'tblboq_details.item_id = tblitem_issue_details.item_id');
            $this->db->where('tblitem_issue_details.issue_id', $issue_id);
            $data['issued_items']=$this->db->get()->result();
            
            $this->db->select('proj_id');
            $this->db->from('tblboq_master');
            $this->db->where('boq_id', $boq_id);
            $data['projects']=$this->db->get()->result();

            $data['project_id']=$data['projects'][0]->proj_id;
            // echo $this->db->last_query();
            $this->load->view('admin/boq/receive-items', $data);//Single request
        // }
        // else{
        //     $this->load->view('admin/boq/requisitions', $data);//All Requests List
        // }
        // $this->load->view('admin/boq/requisitions', $data);//All requests

    }
    public function recieve($boq_id, $req_id) {//RECIEVE ITEMS ACTION
        // Check if form is submitted
        // var_dump($this->session->userdata('staff_user_id'));die();
        // var_dump($this->input->post());die();
        // echo $boq_id;die();
        redirect('admin/boq/requisition/'.$boq_id.'/'.$req_id);

        if ($this->input->post('recieve')) {
            {
                $data = array(
                    'req_status' => "Received"
                );
                $this->db->where('req_id', $req_id);
                $this->db->update('tblitem_requisition_master', $data);
                // Retrieve form data for items
                $item_ids = $this->input->post('item_id'); // Assuming checkboxes send item IDs
                $recieved_qty = $this->input->post('recieved_item_qty');
                $remarks = $this->input->post('remarks');
                // $boq_id = $this->input->post('boq_id'); // Assuming you pass boq_id with the form
                
                $data = array(
                    'req_id' => $req_id,
                    'recieved_at' => date('Y-m-d H:i:s'), 
                    'recieved_by' => $this->session->userdata('staff_user_id'),  
                    'remarks' => $remarks
                );
                $this->db->insert('tblitem_reciept_master', $data);
                $reciept_id = $this->db->insert_id(); // Get the last inserted ID as req_id
                $data = array(
                    'req_status' => 'Received',
                    'remarks' => $this->input->post('remarks')
                );
                $this->db->where('req_id', $req_id);
                $this->db->update('tblitem_requisition_master', $data);
                foreach ($item_ids as $index => $item_id) {
                    $qty = $recieved_qty[$index];     
                    $data = array(
                        'reciept_id' => $reciept_id,
                        'item_id' => $item_id,
                        'qty' => $qty,
                    );
        
                    $this->db->insert('tblitem_reciept_details', $data);
                }
            }
            // var_dump($item_ids);
            redirect('admin/boq/requisition/'.$boq_id.'/'.$req_id);
        }
    }
    public function update_status($boq_id) {
        // Check if form is submitted
        if ($this->input->post('remarks')) {
            // Get the submitted status and remarks
            $boq_status = $this->input->post('boq_status'); // 'Approved' or 'Rejected'
            $remarks = $this->input->post('remarks');
    
            // Update the status and remarks in the database
            $data = array(
                'boq_status' => $boq_status,
                'remarks' => $remarks
            );
            $this->db->where('boq_id', $boq_id);
            $this->db->update('tblboq_master', $data);
    
            // Redirect to the view page for the updated BOQ
            redirect('admin/boq/view/' . $boq_id);
        }
    
        // If form is not submitted, load the view for updating status
        // Fetch BOQ master data
        $this->db->select('*');
        $this->db->from('tblboq_master');
        $this->db->where('boq_id', $boq_id);
        $data['boqs'] = $this->db->get()->result();
    
        // Fetch BOQ details
        $this->db->select('tblboq_details.*, tblitems.description, tblitems.long_description, tblproject_locations.location_title');
        $this->db->from('tblboq_details');
        $this->db->join('tblitems', 'tblboq_details.item_id = tblitems.id');
        $this->db->join('tblproject_locations', 'tblboq_details.loc_id = tblproject_locations.loc_id', 'left');
        $this->db->where('tblboq_details.boq_id', $boq_id);
        $data['boq_items'] = $this->db->get()->result();
        $data['boq_items'] = $this->db->get()->result();
    
        // Load the view with data
        $this->load->view('admin/boq/view', $data);
    }
    
    public function add($project_id, $boq_id=null){//ADD OR COPY BOQ
        $data['project_id']=$project_id;

        if ($this->input->post('boq_title')) {
            // If form is submitted, insert BOQ master data
            $boq_title = $this->input->post('boq_title');
            $remarks = $this->input->post('remarks');
            $now = date("Y-m-d H:i:s");
            $boq_data = array(
                'boq_title' => $boq_title,
                'remarks' => $remarks,
                'proj_id' => $project_id,
                'created_at' => $now,
                
            );
            $this->db->insert('tblboq_master', $boq_data);
            $boq_id = $this->db->insert_id(); // Get the ID of the newly inserted BOQ

            // Insert BOQ details (items and quantities)
            $boq_item_ids = $this->input->post('boq_item_id');
            $boq_item_qtys = $this->input->post('boq_item_qty');
            $boq_item_prices = $this->input->post('boq_item_price');
            $boq_item_loc_ids = $this->input->post('boq_item_loc_id');

            if (!empty($boq_item_ids)) {
                foreach ($boq_item_ids as $key => $boq_item_id) {
                    $item_qty = $boq_item_qtys[$key];
                    $loc_id = $boq_item_loc_ids[$key];
                    $item_price = $boq_item_prices[$key];
                    $detail_data = array(
                        'item_id' => $boq_item_id,
                        'item_qty' => $item_qty,
                        'item_price' => $item_price,
                        'loc_id' => $loc_id,
                        'boq_id' => $boq_id,
                        'boq_type' => 'supply',
                    );
                    try{
                        $this->db->insert('tblboq_details', $detail_data);
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                    }
                }
            }

            redirect(admin_url('boq/' . $project_id));
            
        }
        if(isset($boq_id)){
            $this->db->where('boq_id', $boq_id);
            $data['boqs'] = $this->db->get('tblboq_master')->result();
            $this->db->select('tblboq_details.*, tblitems.description');
            $this->db->from('tblboq_details');
            $this->db->join('tblitems', 'tblboq_details.item_id = tblitems.id');
            $this->db->where('boq_id', $boq_id);
            $data['boq_items'] = $this->db->get()->result();

        }
        // Load project data
        $this->db->where('id', $project_id);
        $data['projects'] = $this->db->get('tblprojects')->result();

        // Load item data
        $data['items'] = $this->db->get('tblitems')->result();
        $this->db->select('tblproject_locations.*');
        $this->db->from('tblproject_locations');
        $this->db->where('proj_id', $project_id);
        $data['locations'] = $this->db->get()->result();

        $this->db->select('groupid as customer_group_id');
        $this->db->from('tblcustomer_groups');
        $this->db->where('customer_id', $data['projects'][0]->clientid);
        $data['customer_group'] = $this->db->get()->result();
        // Load the view to add a new BOQ
        $this->load->view('admin/boq/add', $data);
    }
    public function edit_boq($project_id, $boq_id=null){//EDIT BOQ
        $data['project_id']=$project_id;

        if ($this->input->post('boq_title')) {
            // If form is submitted, insert BOQ master data
            $boq_title = $this->input->post('boq_title');
            $remarks = $this->input->post('remarks');
            $now = date("Y-m-d H:i:s");
            $boq_data = array(
                'boq_title' => $boq_title,
                'remarks' => $remarks,
                'proj_id' => $project_id,
                'created_at' => $now,
                
            );
            $this->db->where('boq_id', $boq_id);
            $this->db->update('tblboq_master', $boq_data);

            // Insert BOQ details (items and quantities)
            $boq_item_ids = $this->input->post('boq_item_id');
            $boq_item_qtys = $this->input->post('boq_item_qty');
            $boq_item_prices = $this->input->post('boq_item_price');
            $boq_item_loc_ids = $this->input->post('boq_item_loc_id');

            if (!empty($boq_item_ids)) {
                foreach ($boq_item_ids as $key => $boq_item_id) {
                    $item_qty = $boq_item_qtys[$key];
                    $loc_id = $boq_item_loc_ids[$key];
                    $item_price = $boq_item_prices[$key];
                    $detail_data = array(
                        'item_id' => $boq_item_id,
                        'item_qty' => $item_qty,
                        'item_price' => $item_price,
                        'loc_id' => $loc_id,
                        'boq_id' => $boq_id,
                    );
                    try{
                        $this->db->where('item_id', $boq_item_id);
                        $this->db->update('tblboq_details', $detail_data);
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                    }
                }
            }

            redirect(admin_url('boq/' . $project_id));
            
        }
        if(isset($boq_id)){
            $this->db->where('boq_id', $boq_id);
            $data['boqs'] = $this->db->get('tblboq_master')->result();
            $this->db->select('tblboq_details.*, tblitems.description');
            $this->db->from('tblboq_details');
            $this->db->join('tblitems', 'tblboq_details.item_id = tblitems.id');
            $this->db->where('boq_id', $boq_id);
            $data['boq_items'] = $this->db->get()->result();

        }
        // Load project data
        $this->db->where('id', $project_id);
        $data['projects'] = $this->db->get('tblprojects')->result();

        // Load item data
        $data['items'] = $this->db->get('tblitems')->result();
        $this->db->select('tblproject_locations.*');
        $this->db->from('tblproject_locations');
        $this->db->where('proj_id', $project_id);
        $data['locations'] = $this->db->get()->result();

        $this->db->select('groupid as customer_group_id');
        $this->db->from('tblcustomer_groups');
        $this->db->where('customer_id', $data['projects'][0]->clientid);
        $data['customer_group'] = $this->db->get()->result();
        // Load the view to add a new BOQ
        $this->load->view('admin/boq/edit_boq', $data);
    }
    public function delete_boq($project_id, $boq_id=null) {
        // Start a transaction to ensure both deletions are successful
        $this->db->trans_start();
    
        // Delete from the BOQ details table where boq_id matches
        $this->db->where('boq_id', $boq_id);
        $this->db->delete('tblboq_details');

        $this->db->select('item_id');
        $this->db->where('boq_id', $boq_id);
        $boq_details = $this->db->get('tblboq_details')->result();

        // If there are associated items, delete them from the tblitems table
        if (!empty($boq_details)) {
            foreach ($boq_details as $detail) {
                $this->db->where('id', $detail->item_id);
                $this->db->delete('tblitems');
            }
        }
    
        // Delete from the BOQ master table where id matches
        $this->db->where('boq_id', $boq_id);
        $this->db->delete('tblboq_master');
    
        // Complete the transaction
        $this->db->trans_complete();
    
        // Check if the transaction was successful
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Error occurred while deleting BOQ');
            return false;
        } else {
            redirect(admin_url('boq/' . $project_id));
        }
    }    
    public function add_from_estimate($estimate_data){
        // var_dump($estimate_data);
        // $data['project_id']=$project_id = $estimate_data['project_id'];

        // if ($this->input->post('boq_title')) {
        //     // If form is submitted, insert BOQ master data
        //     $boq_title = "BOQ TITLE FroM CLIENT NAME/PROJECT TITLE";// $this->input->post('boq_title');
        //     $remarks = "Created from Estimate";// $this->input->post('remarks');

        //     $boq_data = array(
        //         'boq_title' => $boq_title,
        //         'remarks' => $remarks,
        //         'proj_id' => $project_id,
        //     );
        //     $this->db->insert('tblboq_master', $boq_data);
        //     $boq_id = $this->db->insert_id(); // Get the ID of the newly inserted BOQ

        //     // Insert BOQ details (items and quantities)
        //     $boq_item_ids = $estimate_data('boq_item_id');
        //     $boq_item_qtys = $this->input->post('boq_item_qty');

        //     if (!empty($boq_item_ids)) {
        //         foreach ($boq_item_ids as $key => $boq_item_id) {
        //             $item_qty = $boq_item_qtys[$key];
        //             $detail_data = array(
        //                 'item_id' => $boq_item_id,
        //                 'item_qty' => $item_qty,
        //                 'boq_id' => $boq_id,
        //             );
        //             try{
        //                 $this->db->insert('tblboq_details', $detail_data);
        //             }
        //             catch (Exception $e){
        //                 echo $e->getMessage();
        //             }
        //         }
        //     }

        //     redirect(admin_url('boq/' . $project_id));
            
        // }
        // if(isset($boq_id)){
        //     $this->db->where('boq_id', $boq_id);
        //     $data['boqs'] = $this->db->get('tblboq_master')->result();
        //     $this->db->select('tblboq_details.*, tblitems.description');
        //     $this->db->from('tblboq_details');
        //     $this->db->join('tblitems', 'tblboq_details.item_id = tblitems.id');
        //     $this->db->where('boq_id', $boq_id);
        //     $data['boq_items'] = $this->db->get()->result();

        // }
        // // Load project data
        // $this->db->where('id', $project_id);
        // $data['projects'] = $this->db->get('tblprojects')->result();

        // // Load item data
        // $data['items'] = $this->db->get('tblitems')->result();

        // // Load the view to add a new BOQ
        // $this->load->view('admin/boq/add', $data);
    }

    public function edit($project_id, $location_id){//EDIT project location
        // Handle form submission to edit a project location
        if ($this->input->post('location_title')) {
            $location_title = $this->input->post('location_title');

            // Update the project location
            $data = array(
                'location_title' => $location_title,
            );
            $this->db->where('loc_id', $location_id)->update('tblproject_locations', $data);

            // Redirect back to the project locations page
            redirect(admin_url('project-locations/' . $project_id));
        }

        // Fetch the project location details based on $location_id
        $data['location'] = $this->db->where('loc_id', $location_id)->get('tblproject_locations')->row();
        $data['project'] = $this->db->where('id', $project_id)->get('tblprojects')->row();
        // Load the view to edit the project location
        $this->load->view('admin/project_locations/edit', $data);
    }

    public function delete($project_id, $location_id){//DELETE Project Locatio
        // Implement logic to delete the project location based on $location_id
        $this->db->where('loc_id', $location_id)->delete('tblproject_locations');

        // Redirect back to the project locations page after deletion
        redirect(admin_url('project-locations/' . $project_id));
    }
    public function get_item_price(){
        // Get the item ID from the AJAX request
        $item_id = $this->input->post('item_id');

        // Query the database to get the item price based on the item ID
        $item_price = $this->db->select('rate')->from('tblitems')->where('id', $item_id)->get()->row()->rate;

        // Return the item price as plain text
        echo ($item_price);
    }
    public function generate_bom_report($project_id){
        return $project_id;
    }
    public function get_item_details(){
        // Get the item ID from the AJAX request
        $item_id = $this->input->post('item_id');
        $customer_group_id = $this->input->post('customer_group_id');

        // $item_row = $this->db->select('*')
        // ->from('tblitems')
        // ->where('id', $item_id)
        // ->get()
        // ->row();
        if (!empty($customer_group_id)) {
            $item_row = $this->db->select('tblitems.*, IFNULL(tblgroup_items.group_price,0) as group_price')
            ->from('tblitems')
            ->join('tblgroup_items', 'tblgroup_items.item_id = tblitems.id AND tblgroup_items.group_id = ' . $customer_group_id, 'left')
            ->where('tblitems.id', $item_id)
            ->get()
            ->row();
        }
        else{
            $item_row = $this->db->select('tblitems.*')
            ->from('tblitems')
            ->where('tblitems.id', $item_id)
            ->get()
            ->row();    
        }


        //Item rates for specific customer_group
        // $item_rates = $this->db->select('*')
        // ->from('tblgroup_items')
        // ->where('item_id', $item_id)
        // ->where('group_id', $customer_group_id)
        // ->get()
        // ->row();
        // Fetching the rows where accessory_for contains $item_id
        if (!empty($customer_group_id)) {
            $items_rows = $this->db->select('tblitems.*, IFNULL(tblgroup_items.group_price,0) as group_price')
            ->from('tblitems')
            ->like('accessory_for', $item_id, 'both')
            ->join('tblgroup_items', 'tblgroup_items.item_id = tblitems.id AND tblgroup_items.group_id = ' . $customer_group_id, 'left')
            ->get()
            ->result();
        }
        else{
            $items_rows = $this->db->select('tblitems.*')
            ->from('tblitems')
            ->like('accessory_for', $item_id, 'both')
            ->get()
            ->result();
        }
        // var_dump($item_rates);die();

        // Merging the two results into a single array
        $items = array($item_row);
        $items = array_merge($items, $items_rows);//, $item_rates);


        // Return the item price as JSON
        echo json_encode(($items));
        
    }

}