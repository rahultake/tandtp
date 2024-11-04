<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Subcontractor extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('currencies_model');
        $this->load->helper('date');
    }

    public function index()
    {
        $this->db->select('*');
        $this->db->from('tblsub_contractor');
        $data['sub_contractors'] = $this->db->get()->result();

        if ($this->input->post('sub_contractor_name')) {
            // Prepare store data
            $sub_contractor_name = $this->input->post('sub_contractor_name');
            $sub_contractor_description = $this->input->post('sub_contractor_description');
            $now = date("Y-m-d H:i:s");

            $sub_contractor_data = array(
                'sub_contractor_name'   => $sub_contractor_name,
                'sub_contractor_description'=> $sub_contractor_description,
                'created_at'   => $now,
            );

            // Check if we're editing or adding a new sub contractor
            $sub_contractor_id = $this->input->post('sub_contractor_id');
            if ($sub_contractor_id) {
                // Update existing store
                $this->db->where('id', $sub_contractor_id);
                $this->db->update('tblsub_contractor', $sub_contractor_data);
            } else {
                // Insert new store
                $this->db->insert('tblsub_contractor', $sub_contractor_data);
            }

            // Redirect back to the stores page
            redirect(admin_url('sub-contractor/'));
        }
        $data['title']    = _l('Sub Contractor');
        $this->load->view('admin/sub_contractor/manage', $data);
    }
    // Method to fetch store details for editing
    public function get_sub_contractor($sub_contractor_id)
    {
        $sub_contractor = $this->db->where('id', $sub_contractor_id)->get('tblsub_contractor')->row();
        echo json_encode($sub_contractor);
    }
    public function boq($sub_contractor_id){
        $this->db->select('*');
        $this->db->from('tblsub_contractorboq_master');
        $data['boqs'] = $this->db->get()->result();
        $data['sub_contractor_id'] = $sub_contractor_id;
        
        $this->load->view('admin/sub_contractor/boq', $data);
    }
    public function view($boq_id){
        // Fetch BOQ master data
        $this->db->select('*');
        $this->db->from('tblsub_contractorboq_master');
        $this->db->join('tblsub_contractor','tblsub_contractor.id = tblsub_contractorboq_master.sub_contractor_id','left');
        $this->db->where('tblsub_contractorboq_master.id', $boq_id);
        $data['boqs'] = $this->db->get()->result();
        $sub_contractor_id = $data['boqs'][0]->sub_contractor_id;
        $data['sub_contractor_id']=$sub_contractor_id;
        // Fetch BOQ details along with item information
        $this->db->select('tblsub_contractorboq_details.*, tblitems.description, tblitems.unit, tblitems.long_description');            
        $this->db->from('tblsub_contractorboq_details');
        $this->db->join('tblitems', 'tblsub_contractorboq_details.item_id = tblitems.id');
        $this->db->where('tblsub_contractorboq_details.boq_id', $boq_id);
        $data['boq_items'] = $this->db->get()->result();
        $this->db->select('p.*'); // Select all columns from tblprojects and tblclients
        $this->db->from('tblsub_contractor p');
        $this->db->where('p.id', $sub_contractor_id); // Filter by project_id
        $data['sub_contractor'] = $this->db->get()->result();

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
        $this->load->view('admin/sub_contractor/view', $data);
    }
    public function edit_boq($sub_contractor_id, $boq_id=null){//EDIT BOQ
        $data['sub_contractor_id']=$sub_contractor_id;

        if ($this->input->post('boq_title')) {
            // If form is submitted, insert BOQ master data
            $boq_title = $this->input->post('boq_title');
            $remarks = $this->input->post('remarks');
            $now = date("Y-m-d H:i:s");
            $boq_data = array(
                'boq_title' => $boq_title,
                'remarks' => $remarks,
                'sub_contractor_id' => $sub_contractor_id,
                'created_at' => $now,
                
            );
            $this->db->where('id', $boq_id);
            $this->db->update('tblsub_contractorboq_master', $boq_data);

            // Insert BOQ details (items and quantities)
            $boq_item_ids = $this->input->post('boq_item_id');
            $boq_item_qtys = $this->input->post('boq_item_qty');
            $boq_item_prices = $this->input->post('boq_item_price');

            if (!empty($boq_item_ids)) {
                foreach ($boq_item_ids as $key => $boq_item_id) {
                    $item_qty = $boq_item_qtys[$key];
                    $item_price = $boq_item_prices[$key];
                    $detail_data = array(
                        'item_id' => $boq_item_id,
                        'sub_contractor_qty' => $item_qty,
                        'sub_contractor_price' => $item_price,
                        'boq_id' => $boq_id,
                    );
                    try{
                        $this->db->where('item_id', $boq_item_id);
                        $this->db->update('tblsub_contractorboq_details', $detail_data);
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                    }
                }
            }

            redirect(admin_url('sub-contractor/boq/'.$sub_contractor_id));
            
        }
        if(isset($boq_id)){
            $this->db->where('id', $boq_id);
            $data['boqs'] = $this->db->get('tblsub_contractorboq_master')->result();
            $this->db->select('tblsub_contractorboq_details.*, tblitems.description');
            $this->db->from('tblsub_contractorboq_details');
            $this->db->join('tblitems', 'tblsub_contractorboq_details.item_id = tblitems.id');
            $this->db->where('tblsub_contractorboq_details.boq_id', $boq_id);
            $data['boq_items'] = $this->db->get()->result();

        }
        // Load project data
        $this->db->where('id', $sub_contractor_id);
        $data['sub_contractor'] = $this->db->get('tblsub_contractor')->result();

        // Load item data
        $data['items'] = $this->db->get('tblitems')->result();

        // Load the view to add a new BOQ
        $this->load->view('admin/sub_contractor/edit_boq', $data);
    }
    public function delete_boq($sub_contractor_id, $boq_id=null) {
        // Start a transaction to ensure both deletions are successful
        $this->db->trans_start();
    
        // Delete from the BOQ details table where boq_id matches
        $this->db->where('boq_id', $boq_id);
        $this->db->delete('tblsub_contractorboq_details');

        $this->db->select('item_id');
        $this->db->where('boq_id', $boq_id);
        $boq_details = $this->db->get('tblsub_contractorboq_details')->result();

        // If there are associated items, delete them from the tblitems table
        if (!empty($boq_details)) {
            foreach ($boq_details as $detail) {
                $this->db->where('id', $detail->item_id);
                $this->db->delete('tblitems');
            }
        }
    
        // Delete from the BOQ master table where id matches
        $this->db->where('id', $boq_id);
        $this->db->delete('tblsub_contractorboq_master');
    
        // Complete the transaction
        $this->db->trans_complete();
    
        // Check if the transaction was successful
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Error occurred while deleting BOQ');
            return false;
        } else {
            redirect(admin_url('sub-contractor/boq/' . $sub_contractor_id));
        }
    }
    public function import($sub_contractor_id)
    {
        $data['sub_contractor_id']=$sub_contractor_id;
        if (!has_permission('items', '', 'create')) {
            access_denied('Items Import');
        }

        $this->load->library('import/import_items_sub_contractor', [], 'import');

        // Manually map the relevant fields from the CSV
        $databaseFields = [
            'description', 
            'unit',        
            'qty_per_KM',    
            'LOA_BOQ_Sl_No',        
            'Total_LOA_Qty',     
            'Sub_Cont_Qty',        
            'Sub_Cont_Unit_Rate',        
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
                    'sub_contractor_id' => $sub_contractor_id,
                    'boq_title' => 'Erection', // Can be dynamic based on input
                    'boq_status' => 'created',
                    'created_at' => date('Y-m-d H:i:s'),
                    'remarks' => 'Automatically created after item import of erection',
                ];
                $this->db->insert(db_prefix() . 'sub_contractorboq_master', $boq_data);
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
        $this->load->view('admin/sub_contractor/import', $data);
    }
    public function projects($project_id=null)
    {
        $this->db->select('*,tblsub_contractor.id as sub_id');
        $this->db->from('tblsub_contractor');
        $this->db->join('tblproject_sub_contractor', 'tblproject_sub_contractor.sub_contractor_id = tblsub_contractor.id');
        $this->db->where('tblproject_sub_contractor.proj_id', $project_id);
        $data['sub_contractors'] = $this->db->get()->result();
        $data['project_id'] = $project_id;

        $data['title']    = _l('Sub Contractors against project');
        $this->load->view('admin/sub_contractor/projects', $data);
    }
    public function track_material_issue($project_id,$sub_contractor_id){
        // Fetch BOQ master data
        $this->db->select('*');
        $this->db->from('tblsub_contractorboq_master');
        $this->db->join('tblsub_contractor','tblsub_contractor.id = tblsub_contractorboq_master.sub_contractor_id','left');
        $this->db->where('tblsub_contractorboq_master.sub_contractor_id', $sub_contractor_id);
        $data['track'] = $this->db->get()->result();
        $data['project_id']=$project_id;
        $data['sub_contractor_id']=$sub_contractor_id;
        // Fetch BOQ details along with item information
        $this->db->select('tblstock_movement.item_id, tblitems.rate, tblitems.unit, tblitems.long_description, tblitems.description, tblstock_movement.movement_type, tblstock_movement.item_qty, tblstock_movement.movement_date');
        $this->db->from('tblstock_movement');
        $this->db->join('tblitems', 'tblitems.id = tblstock_movement.item_id');
        $this->db->join('tblsub_contractorboq_master', 'tblsub_contractorboq_master.sub_contractor_id = tblstock_movement.sub_contractor_id');
        $this->db->where('tblsub_contractorboq_master.sub_contractor_id', $sub_contractor_id);
        $this->db->order_by('tblstock_movement.movement_date', 'ASC'); // Order by movement_date
        $data['track_items'] = $this->db->get()->result();
        $this->db->select('p.*'); // Select all columns from tblprojects and tblclients
        $this->db->from('tblsub_contractor p');
        $this->db->where('p.id', $sub_contractor_id); // Filter by project_id
        $data['sub_contractor'] = $this->db->get()->result();
        // Load the view with data
        $this->load->view('admin/sub_contractor/track', $data);
    }
}
