<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Inventory extends AdminController{
    public function index($project_id=null){
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('proj_id', $project_id);
        $data['stores'] = $this->db->get()->result();
        $this->db->where('id', $project_id);        
        $data['projects'] = $this->db->get('tblprojects')->result();
        $data['project_id'] = $project_id;

        $this->load->view('admin/inventory/list', $data);
    }
    public function stores($project_id = null)
    {
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('proj_id', $project_id);
        $data['stores'] = $this->db->get()->result();
        $this->db->where('id', $project_id);        
        $data['projects'] = $this->db->get('tblprojects')->result();
        $data['project_id'] = $project_id;

        if ($this->input->post('store_name')) {
            // Prepare store data
            $store_name = $this->input->post('store_name');
            $store_address = $this->input->post('store_address');
            $now = date("Y-m-d H:i:s");

            $store_data = array(
                'store_name'   => $store_name,
                'store_address'=> $store_address,
                'proj_id'      => $project_id,
                'created_at'   => $now,
            );

            // Check if we're editing or adding a new store
            $store_id = $this->input->post('store_id');
            if ($store_id) {
                // Update existing store
                $this->db->where('id', $store_id);
                $this->db->update('tblstores', $store_data);
            } else {
                // Insert new store
                $this->db->insert('tblstores', $store_data);
            }

            // Redirect back to the stores page
            redirect(admin_url('inventory/stores/' . $project_id));
        }

        $this->load->view('admin/inventory/list', $data);
    }
    // Method to fetch store details for editing
    public function get_store($store_id)
    {
        $store = $this->db->where('id', $store_id)->get('tblstores')->row();
        echo json_encode($store);
    }
    public function delete($store_id)
    {
        // Check if the store exists
        $this->db->where('id', $store_id);
        $store = $this->db->get('tblstores')->row();

        if ($store) {
            // Delete the store
            $this->db->where('id', $store_id);
            $this->db->delete('tblstores');

            // Optionally, set a success message
            set_alert('success', 'Store deleted successfully.');
        } else {
            // If store not found, set an error message
            set_alert('danger', 'Store not found.');
        }

        // Redirect back to the stores list
        redirect(admin_url('inventory/stores/' . $store->proj_id));
    }
    public function categories($project_id=null){
        $this->db->select('*');
        $this->db->from('tblproject_categories');
        $data['project_categories'] = $this->db->get()->result();
        $this->db->where('id', $project_id);        
        $data['projects'] = $this->db->get('tblprojects')->result();
        $data['project_id'] = $project_id;

        if ($this->input->post('category_name')) {
            // Prepare category data
            $category_name = $this->input->post('category_name');
            $category_description = $this->input->post('category_description');
            $now = date("Y-m-d H:i:s");

            $category_data = array(
                'category_name'   => $category_name,
                'category_description'=> $category_description,
                'proj_id'      => $project_id,
                'created_at'   => $now,
            );

            // Check if we're editing or adding a new category
            $category_id = $this->input->post('category_id');
            if ($category_id) {
                // Update existing category
                $this->db->where('id', $category_id);
                $this->db->update('tblproject_categories', $category_data);
            } else {
                // Insert new category
                $this->db->insert('tblproject_categories', $category_data);
            }

            // Redirect back to the category page
            redirect(admin_url('inventory/categories/' . $project_id));
        }

        $this->load->view('admin/inventory/categorieslist', $data);
    }
    // Method to fetch category details for editing
    public function get_category($category_id)
    {
        $category = $this->db->where('id', $category_id)->get('tblproject_categories')->row();
        echo json_encode($category);
    }
    public function deletecategory($category_id)
    {
        // Check if the category exists
        $this->db->where('id', $category_id);
        $category = $this->db->get('tblproject_categories')->row();

        if ($category) {
            // Delete the category
            $this->db->where('id', $category_id);
            $this->db->delete('tblproject_categories');

            // Optionally, set a success message
            set_alert('success', 'Category deleted successfully.');
        } else {
            // If category not found, set an error message
            set_alert('danger', 'Category not found.');
        }

        // Redirect back to the category list
        redirect(admin_url('inventory/categories/' . $category->proj_id));
    }
    public function attach_categories($project_id=null){
        $this->db->select('tblattach_categories_master.*,tblproject_categories.category_name');
        $this->db->from('tblattach_categories_master');
        $this->db->join('tblproject_categories', 'tblproject_categories.id = tblattach_categories_master.category_id');
        $data['attach_categories'] = $this->db->get()->result();
        $this->db->where('id', $project_id);        
        $data['projects'] = $this->db->get('tblprojects')->result();
        $data['project_id'] = $project_id;

        $this->load->view('admin/inventory/attachlist', $data);
    }
    public function attach_categories_items($project_id=null){
        $this->db->select('*');
        $this->db->from('tblproject_categories');
        $this->db->where('proj_id', $project_id);
        $data['project_categories'] = $this->db->get()->result();
        $this->db->select('tblitems.id,tblitems.description');
        $this->db->from('tblboq_master');
        $this->db->join('tblboq_details', 'tblboq_master.boq_id = tblboq_details.boq_id');
        $this->db->join('tblitems', 'tblitems.id = tblboq_details.item_id');
        $this->db->where('tblboq_master.proj_id', $project_id);
        $this->db->where('tblboq_master.boq_title', 'Supply');
        $data['project_items'] = $this->db->get()->result();
        $data['project_id'] = $project_id;

        // Handle form submission
        if ($this->input->post()) {
            $category_id = $this->input->post('category_id');
            $item_ids = $this->input->post('item_id'); // This is an array of selected item IDs
            $now = date("Y-m-d H:i:s");
            $attach_data = array(
                'category_id' => $category_id,
                'proj_id' => $project_id,
                'created_at' => $now,                
            );
            $this->db->insert('tblattach_categories_master', $attach_data);
            $attach_id = $this->db->insert_id();
            // Check if category_id and item_ids are set
            if ($category_id && !empty($item_ids)) {
                foreach ($item_ids as $item_id) {
                    $p_data = array(
                        'proj_id' => $project_id,
                        'category_id' => $category_id,
                        'attach_id' => $attach_id,
                        'item_id'     => $item_id,
                        'created_at'   => $now,
                    );

                    // Insert into tblattach_categories
                    $this->db->insert('tblattach_categories', $p_data);
                }

                // Optionally, you can redirect after insertion
                redirect(admin_url('inventory/attach_categories/' . $project_id));
            }
        }

        $this->load->view('admin/inventory/attachcategoryitems', $data);
    }
    public function view($attach_id){
        // Fetch BOQ master data
        $this->db->select('*');
        $this->db->from('tblattach_categories_master');
        $this->db->join('tblproject_categories','tblproject_categories.id = tblattach_categories_master.category_id','left');
        $this->db->where('tblattach_categories_master.id', $attach_id);
        $data['attach'] = $this->db->get()->result();
        $project_id = $data['attach'][0]->proj_id;
        $data['project_id']=$project_id;
        
        $this->db->select('tblattach_categories.*, tblitems.description, tblitems.long_description, tblitems.unit');
        $this->db->from('tblattach_categories');
        $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');   
        $this->db->where('tblattach_categories.attach_id', $attach_id);
        $data['attach_items'] = $this->db->get()->result();
        $this->db->select('*'); // Select all columns from tblprojects and tblclients
        $this->db->from('tblprojects');
        $this->db->where('id', $project_id); // Filter by project_id
        $data['projects'] = $this->db->get()->result();
      
        // Load the view with data
        $this->load->view('admin/inventory/view', $data);
    }
    public function export_to_csv($project_id = null)
    {
        require_once APPPATH . '../vendor/autoload.php';
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y');
        
        // Fetch stores from tblstores
        $this->db->select('id, store_name');
        $this->db->from('tblstores');
        $this->db->where('proj_id', $project_id);
        $stores = $this->db->get()->result_array(); // Data fetched from database

        // Fetch entry quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $entry_qty = $this->db->get()->result_array();

        // Map quantities by store and item
        $store_item_qty_map = [];
        foreach ($entry_qty as $entry) {
            $store_item_qty_map[$entry['store_id']][$entry['item_id']] = $entry['item_qty'];
        }
        $store_item_qty_map = $store_item_qty_map;

        // Fetch exit quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $exit_qty = $this->db->get()->result_array();

        // Map exit quantities by store and item
        $store_item_exit_qty_map = [];
        foreach ($exit_qty as $exit) {
            $store_item_exit_qty_map[$exit['store_id']][$exit['item_id']] = $exit['item_qty'];
        }
        $store_item_exit_qty_map = $store_item_exit_qty_map;

        // Fetch return quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "return"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $return_qty = $this->db->get()->result_array();

        // Map return quantities by store and item
        $store_item_return_qty_map = [];
        foreach ($return_qty as $return) {
            $store_item_return_qty_map[$return['store_id']][$return['item_id']] = $return['item_qty'];
        }
        $store_item_return_qty_map = $store_item_return_qty_map;

        // Create a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set base headers (first few columns)
        $headers = ['Sl No', 'Description of Materials', 'Unit'];

        // Dynamically add store-related columns for each store
        foreach ($stores as $store) {
            $storeColumns = [
                "{$store['store_name']} - Closing Stock (30.09.24)", 
                "{$store['store_name']} - Receipt (30/09/24)", 
                "{$store['store_name']} - Issue (30/09/24)", 
                "{$store['store_name']} - Returned Material", 
                "{$store['store_name']} - STOCK AS ON (30/09/24)", 
                "{$store['store_name']} - Actual Physical Stock"
            ];
            // Append store columns to headers
            $headers = array_merge($headers, $storeColumns);
        }

        // Add a final column for "Total Stock"
        $totalStockColumns = [
            'Total - Closing Stock (30.09.24)', 
            'Total - Receipt (30/09/24)', 
            'Total - Issue (30/09/24)', 
            'Total - Returned Material', 
            'Total - STOCK AS ON (30/09/24)', 
            'Total - Actual Physical Stock'
        ];
        $headers = array_merge($headers, $totalStockColumns);

        // Add the headers to the first row
        $sheet->fromArray($headers, NULL, 'A1');

        // Define styles for categories (yellow background)
        $categoryStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'] // Yellow color
            ]
        ];

        // Fetch categories from tblproject_categories
        $this->db->select('id, category_name');
        $this->db->from('tblproject_categories');
        $this->db->where('proj_id', $project_id);
        $categories = $this->db->get()->result_array();

        $row = 2; // Start from the second row for data
        $slNo = 1; // Serial number for items

        foreach ($categories as $category) {
            // Add category name with yellow background
            $sheet->setCellValue('A' . $row, $category['category_name']);
            $sheet->getStyle('A' . $row . ':Z' . $row)->applyFromArray($categoryStyle);
            $row++;

            // Fetch items related to the category from tblattach_categories_master
            $this->db->select('tblitems.id as item_id,description, unit');
            $this->db->from('tblattach_categories');
            $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');
            $this->db->where('tblattach_categories.category_id', $category['id']);
            $this->db->where('tblattach_categories.proj_id', $project_id);
            $items = $this->db->get()->result_array();

            // Add items under each category
            foreach ($items as $item) {
                $itemData = [
                    $slNo++, // Sl No
                    $item['description'], // Description of Materials
                    $item['unit'], // Unit
                ];
                $total_closing_stock = 0;
                $total_receipt = 0;
                $total_issue = 0;
                $total_returned = 0;
                $total_stock_as_on = 0;
                $total_actual_physical_stock = 0;
                // Add placeholder values for each store (e.g., zero values)
                foreach ($stores as $store) {
                    $item_qty = isset($store_item_qty_map[$store['id']][$item['item_id']]) ? $store_item_qty_map[$store['id']][$item['item_id']] : 0;
                    $exititem_qty = isset($store_item_exit_qty_map[$store['id']][$item['item_id']]) ? $store_item_exit_qty_map[$store['id']][$item['item_id']] : 0;
                    $returnitem_qty = isset($store_item_return_qty_map[$store['id']][$item['item_id']]) ? $store_item_return_qty_map[$store['id']][$item['item_id']] : 0;
                    
                    // Calculate the Closing Stock, Receipt, Issue, etc.
                    $closing_stock = ($item_qty - $exititem_qty) + $returnitem_qty;
                    
                    $itemData = array_merge($itemData, [$closing_stock, $item_qty, $exititem_qty, $returnitem_qty, $closing_stock, $item_qty]); // Closing Stock, Receipt, Issue, etc.
                    // Add to total accumulators
                    $total_closing_stock += $closing_stock;
                    $total_receipt += $item_qty;
                    $total_issue += $exititem_qty;
                    $total_returned += $returnitem_qty;
                    $total_stock_as_on += $closing_stock;
                    $total_actual_physical_stock += $item_qty;
                }

                $itemData = array_merge($itemData, [
                    $total_closing_stock,
                    $total_receipt,
                    $total_issue,
                    $total_returned,
                    $total_stock_as_on,
                    $total_actual_physical_stock
                ]);

                // Add item data to the sheet
                $sheet->fromArray($itemData, NULL, 'A' . $row);

                // Ensure zero values are displayed as numbers
                $sheet->getStyle('A' . $row . ':Z' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

                $row++;
            }
        }

        // Set the output file
        $writer = new Xlsx($spreadsheet);
        $dateObj = DateTime::createFromFormat('!m', $filter_month);
        $monthName = $dateObj->format('F');
        $filename = "inventory_{$monthName}_stock_report.xlsx";

        // Set headers for the browser to download the file as Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Write the file to the output
        $writer->save('php://output');
        exit();
    }
    public function export_against_store($store_id)
    {
        require_once APPPATH . '../vendor/autoload.php';
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y');
        
        // Fetch stores from tblstores
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('id', $store_id);
        $stores = $this->db->get()->result_array(); // Data fetched from database
        $project_id = $stores[0]['proj_id'];

        // Fetch entry quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $entry_qty = $this->db->get()->result_array();

        // Map quantities by store and item
        $store_item_qty_map = [];
        foreach ($entry_qty as $entry) {
            $store_item_qty_map[$entry['store_id']][$entry['item_id']] = $entry['item_qty'];
        }
        $store_item_qty_map = $store_item_qty_map;

        // Fetch exit quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $exit_qty = $this->db->get()->result_array();

        // Map exit quantities by store and item
        $store_item_exit_qty_map = [];
        foreach ($exit_qty as $exit) {
            $store_item_exit_qty_map[$exit['store_id']][$exit['item_id']] = $exit['item_qty'];
        }
        $store_item_exit_qty_map = $store_item_exit_qty_map;

        // Fetch return quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "return"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $return_qty = $this->db->get()->result_array();

        // Map return quantities by store and item
        $store_item_return_qty_map = [];
        foreach ($return_qty as $return) {
            $store_item_return_qty_map[$return['store_id']][$return['item_id']] = $return['item_qty'];
        }
        $store_item_return_qty_map = $store_item_return_qty_map;
        // Create a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set base headers (first few columns)
        $headers = ['Sl No', 'Description of Materials', 'Unit'];

        // Dynamically add store-related columns for each store
        foreach ($stores as $store) {
            $storeColumns = [
                "{$store['store_name']} - Closing Stock (30.09.24)", 
                "{$store['store_name']} - Receipt (30/09/24)", 
                "{$store['store_name']} - Issue (30/09/24)", 
                "{$store['store_name']} - Returned Material", 
                "{$store['store_name']} - STOCK AS ON (30/09/24)", 
                "{$store['store_name']} - Actual Physical Stock"
            ];
            // Append store columns to headers
            $headers = array_merge($headers, $storeColumns);
        }

        // Add the headers to the first row
        $sheet->fromArray($headers, NULL, 'A1');

        // Define styles for categories (yellow background)
        $categoryStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'] // Yellow color
            ]
        ];

        // Fetch categories from tblproject_categories
        $this->db->select('id, category_name');
        $this->db->from('tblproject_categories');
        $this->db->where('proj_id', $project_id);
        $categories = $this->db->get()->result_array();

        $row = 2; // Start from the second row for data
        $slNo = 1; // Serial number for items

        foreach ($categories as $category) {
            // Add category name with yellow background
            $sheet->setCellValue('A' . $row, $category['category_name']);
            $sheet->getStyle('A' . $row . ':Z' . $row)->applyFromArray($categoryStyle);
            $row++;

            // Fetch items related to the category from tblattach_categories_master
            $this->db->select('tblitems.id as item_id,description, unit');
            $this->db->from('tblattach_categories');
            $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');
            $this->db->where('tblattach_categories.category_id', $category['id']);
            $this->db->where('tblattach_categories.proj_id', $project_id);
            $items = $this->db->get()->result_array();

            // Add items under each category
            foreach ($items as $item) {
                $itemData = [
                    $slNo++, // Sl No
                    $item['description'], // Description of Materials
                    $item['unit'], // Unit
                ];

                // Add placeholder values for each store (e.g., zero values)
                foreach ($stores as $store) {
                    $item_qty = isset($store_item_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                    $exititem_qty = isset($store_item_exit_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_exit_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                    $returnitem_qty = isset($store_item_return_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_return_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                    
                    $itemData = array_merge($itemData, [(($item_qty - $exititem_qty) + $returnitem_qty), $item_qty, $exititem_qty, $returnitem_qty, (($item_qty - $exititem_qty) + $returnitem_qty), $item_qty]); // Closing Stock, Receipt, Issue, etc.
                }

                // Add total stock (can calculate based on real data if needed)
                $itemData[] = 0; // Total Stock

                // Add item data to the sheet
                $sheet->fromArray($itemData, NULL, 'A' . $row);

                // Ensure zero values are displayed as numbers
                $sheet->getStyle('A' . $row . ':Z' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

                $row++;
            }
        }

        // Set the output file
        $writer = new Xlsx($spreadsheet);
        $dateObj = DateTime::createFromFormat('!m', $filter_month);
        $monthName = $dateObj->format('F');
        $filename = "inventory_{$monthName}_stock_report.xlsx";

        // Set headers for the browser to download the file as Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Write the file to the output
        $writer->save('php://output');
        exit();
    }
    public function export_receipt_against_store($store_id)
    {
        require_once APPPATH . '../vendor/autoload.php';
        $filter_month = $this->input->post('receipt_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y');
        
        // Fetch stores from tblstores
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('id', $store_id);
        $stores = $this->db->get()->result_array(); // Data fetched from database
        $project_id = $stores[0]['proj_id'];

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $day_qty = $this->db->get()->result_array();

        // Create a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set base headers (first few columns)
        $headers = ['Sl No', 'Description of Materials', 'Unit'];

        // Dynamically add store-related columns for each store
        foreach ($day_qty as $day_qtys) {
            $storeColumns = [
                "{$day_qtys['destination']} - {$day_qtys['movement_day']}"
            ];
            // Append store columns to headers
            $headers = array_merge($headers, $storeColumns);
        }
        $headers = array_merge($headers, ['Total Receipt']);
        // Add the headers to the first row
        $sheet->fromArray($headers, NULL, 'A1');

        // Define styles for categories (yellow background)
        $categoryStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'] // Yellow color
            ]
        ];

        // Fetch categories from tblproject_categories
        $this->db->select('id, category_name');
        $this->db->from('tblproject_categories');
        $this->db->where('proj_id', $project_id);
        $categories = $this->db->get()->result_array();

        $row = 2; // Start from the second row for data
        $slNo = 1; // Serial number for items

        foreach ($categories as $category) {
            // Add category name with yellow background
            $sheet->setCellValue('A' . $row, $category['category_name']);
            $sheet->getStyle('A' . $row . ':Z' . $row)->applyFromArray($categoryStyle);
            $row++;

            // Fetch items related to the category from tblattach_categories_master
            $this->db->select('tblitems.id as item_id,description, unit');
            $this->db->from('tblattach_categories');
            $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');
            $this->db->where('tblattach_categories.category_id', $category['id']);
            $this->db->where('tblattach_categories.proj_id', $project_id);
            $items = $this->db->get()->result_array();

            // Add items under each category
            foreach ($items as $item) {
                $itemData = [
                    $slNo++, // Sl No
                    $item['description'], // Description of Materials
                    $item['unit'], // Unit
                ];
                $total_day_qtys = 0;
                // Add placeholder values for each store (e.g., zero values)
                foreach ($day_qty as $day_qtys) {
                    if ($day_qtys['item_id'] == $item['item_id']) {
                        $total_day_qtys += $day_qtys['item_qty'];
                        $itemData = array_merge($itemData, [$day_qtys['item_qty']]);
                    } else {
                        $total_day_qtys = 0;
                        $itemData = array_merge($itemData, [0]);
                    }
                }
                $itemData = array_merge($itemData, [$total_day_qtys]);
                // Add total stock (can calculate based on real data if needed)
                $itemData[] = 0; // Total Stock

                // Add item data to the sheet
                $sheet->fromArray($itemData, NULL, 'A' . $row);

                // Ensure zero values are displayed as numbers
                $sheet->getStyle('A' . $row . ':Z' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

                $row++;
            }
        }

        // Set the output file
        $writer = new Xlsx($spreadsheet);
        $dateObj = DateTime::createFromFormat('!m', $filter_month);
        $monthName = $dateObj->format('F');
        $filename = "Daily_Materials_{$monthName}_Receipt_Format.xlsx";
        
        // Set headers for the browser to download the file as Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Write the file to the output
        $writer->save('php://output');
        exit();
    }
    public function export_issue_against_store($store_id)
    {
        require_once APPPATH . '../vendor/autoload.php';
        $filter_month = $this->input->post('issue_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y');
        
        // Fetch stores from tblstores
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('id', $store_id);
        $stores = $this->db->get()->result_array(); // Data fetched from database
        $project_id = $stores[0]['proj_id'];

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $day_qty = $this->db->get()->result_array();

        // Create a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set base headers (first few columns)
        $headers = ['Sl No', 'Description of Materials', 'Unit'];

        // Dynamically add store-related columns for each store
        foreach ($day_qty as $day_qtys) {
            $storeColumns = [
                "{$day_qtys['destination']} - {$day_qtys['movement_day']}"
            ];
            // Append store columns to headers
            $headers = array_merge($headers, $storeColumns);
        }
        $headers = array_merge($headers, ['Total Issue']);
        // Add the headers to the first row
        $sheet->fromArray($headers, NULL, 'A1');

        // Define styles for categories (yellow background)
        $categoryStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'] // Yellow color
            ]
        ];

        // Fetch categories from tblproject_categories
        $this->db->select('id, category_name');
        $this->db->from('tblproject_categories');
        $this->db->where('proj_id', $project_id);
        $categories = $this->db->get()->result_array();

        $row = 2; // Start from the second row for data
        $slNo = 1; // Serial number for items

        foreach ($categories as $category) {
            // Add category name with yellow background
            $sheet->setCellValue('A' . $row, $category['category_name']);
            $sheet->getStyle('A' . $row . ':Z' . $row)->applyFromArray($categoryStyle);
            $row++;

            // Fetch items related to the category from tblattach_categories_master
            $this->db->select('tblitems.id as item_id,description, unit');
            $this->db->from('tblattach_categories');
            $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');
            $this->db->where('tblattach_categories.category_id', $category['id']);
            $this->db->where('tblattach_categories.proj_id', $project_id);
            $items = $this->db->get()->result_array();

            // Add items under each category
            foreach ($items as $item) {
                $itemData = [
                    $slNo++, // Sl No
                    $item['description'], // Description of Materials
                    $item['unit'], // Unit
                ];
                $total_day_qtys = 0;
                // Add placeholder values for each store (e.g., zero values)
                foreach ($day_qty as $day_qtys) {
                    if ($day_qtys['item_id'] == $item['item_id']) {
                        $total_day_qtys += $day_qtys['item_qty'];
                        $itemData = array_merge($itemData, [$day_qtys['item_qty']]);
                    } else {
                        $total_day_qtys = 0;
                        $itemData = array_merge($itemData, [0]);
                    }
                }
                $itemData = array_merge($itemData, [$total_day_qtys]);
                // Add total stock (can calculate based on real data if needed)
                $itemData[] = 0; // Total Stock

                // Add item data to the sheet
                $sheet->fromArray($itemData, NULL, 'A' . $row);

                // Ensure zero values are displayed as numbers
                $sheet->getStyle('A' . $row . ':Z' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

                $row++;
            }
        }

        // Set the output file
        $writer = new Xlsx($spreadsheet);
        $dateObj = DateTime::createFromFormat('!m', $filter_month);
        $monthName = $dateObj->format('F');
        $filename = "Daily_Materials_{$monthName}_Issue_Format.xlsx";
        
        // Set headers for the browser to download the file as Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Write the file to the output
        $writer->save('php://output');
        exit();
    }
    public function export_receipt_all_store($project_id)
    {
        require_once APPPATH . '../vendor/autoload.php';
        $filter_month = $this->input->post('receipt_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y');
        
        // Fetch stores from tblstores
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('proj_id', $project_id);
        $stores = $this->db->get()->result_array();

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $day_qty = $this->db->get()->result_array();

        // Create a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set base headers (first few columns)
        $headers = ['Sl No', 'Description of Materials', 'Unit'];

        // Dynamically add store-related columns for each store
        foreach ($day_qty as $day_qtys) {
            $storeColumns = [
                "{$day_qtys['destination']} - {$day_qtys['movement_day']}"
            ];
            // Append store columns to headers
            $headers = array_merge($headers, $storeColumns);
        }
        $headers = array_merge($headers, ['Total Receipt']);
        // Add the headers to the first row
        $sheet->fromArray($headers, NULL, 'A1');

        // Define styles for categories (yellow background)
        $categoryStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'] // Yellow color
            ]
        ];

        // Fetch categories from tblproject_categories
        $this->db->select('id, category_name');
        $this->db->from('tblproject_categories');
        $this->db->where('proj_id', $project_id);
        $categories = $this->db->get()->result_array();

        $row = 2; // Start from the second row for data
        $slNo = 1; // Serial number for items

        foreach ($categories as $category) {
            // Add category name with yellow background
            $sheet->setCellValue('A' . $row, $category['category_name']);
            $sheet->getStyle('A' . $row . ':Z' . $row)->applyFromArray($categoryStyle);
            $row++;

            // Fetch items related to the category from tblattach_categories_master
            $this->db->select('tblitems.id as item_id,description, unit');
            $this->db->from('tblattach_categories');
            $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');
            $this->db->where('tblattach_categories.category_id', $category['id']);
            $this->db->where('tblattach_categories.proj_id', $project_id);
            $items = $this->db->get()->result_array();

            // Add items under each category
            foreach ($items as $item) {
                $itemData = [
                    $slNo++, // Sl No
                    $item['description'], // Description of Materials
                    $item['unit'], // Unit
                ];
                $total_day_qtys = 0;
                // Add placeholder values for each store (e.g., zero values)
                foreach ($day_qty as $day_qtys) {
                    if ($day_qtys['item_id'] == $item['item_id']) {
                        $total_day_qtys += $day_qtys['item_qty'];
                        $itemData = array_merge($itemData, [$day_qtys['item_qty']]);
                    } else {
                        $total_day_qtys = 0;
                        $itemData = array_merge($itemData, [0]);
                    }
                }
                $itemData = array_merge($itemData, [$total_day_qtys]);
                // Add total stock (can calculate based on real data if needed)
                $itemData[] = 0; // Total Stock

                // Add item data to the sheet
                $sheet->fromArray($itemData, NULL, 'A' . $row);

                // Ensure zero values are displayed as numbers
                $sheet->getStyle('A' . $row . ':Z' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

                $row++;
            }
        }

        // Set the output file
        $writer = new Xlsx($spreadsheet);
        $dateObj = DateTime::createFromFormat('!m', $filter_month);
        $monthName = $dateObj->format('F');
        $filename = "Daily_Materials_{$monthName}_Receipt_Format.xlsx";
        
        // Set headers for the browser to download the file as Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Write the file to the output
        $writer->save('php://output');
        exit();
    }
    public function export_issue_all_store($project_id)
    {
        require_once APPPATH . '../vendor/autoload.php';
        $filter_month = $this->input->post('issue_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y');
        
        // Fetch stores from tblstores
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('proj_id', $project_id);
        $stores = $this->db->get()->result_array();

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $day_qty = $this->db->get()->result_array();

        // Create a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set base headers (first few columns)
        $headers = ['Sl No', 'Description of Materials', 'Unit'];

        // Dynamically add store-related columns for each store
        foreach ($day_qty as $day_qtys) {
            $storeColumns = [
                "{$day_qtys['destination']} - {$day_qtys['movement_day']}"
            ];
            // Append store columns to headers
            $headers = array_merge($headers, $storeColumns);
        }
        $headers = array_merge($headers, ['Total Receipt']);
        // Add the headers to the first row
        $sheet->fromArray($headers, NULL, 'A1');

        // Define styles for categories (yellow background)
        $categoryStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'] // Yellow color
            ]
        ];

        // Fetch categories from tblproject_categories
        $this->db->select('id, category_name');
        $this->db->from('tblproject_categories');
        $this->db->where('proj_id', $project_id);
        $categories = $this->db->get()->result_array();

        $row = 2; // Start from the second row for data
        $slNo = 1; // Serial number for items

        foreach ($categories as $category) {
            // Add category name with yellow background
            $sheet->setCellValue('A' . $row, $category['category_name']);
            $sheet->getStyle('A' . $row . ':Z' . $row)->applyFromArray($categoryStyle);
            $row++;

            // Fetch items related to the category from tblattach_categories_master
            $this->db->select('tblitems.id as item_id,description, unit');
            $this->db->from('tblattach_categories');
            $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');
            $this->db->where('tblattach_categories.category_id', $category['id']);
            $this->db->where('tblattach_categories.proj_id', $project_id);
            $items = $this->db->get()->result_array();

            // Add items under each category
            foreach ($items as $item) {
                $itemData = [
                    $slNo++, // Sl No
                    $item['description'], // Description of Materials
                    $item['unit'], // Unit
                ];
                $total_day_qtys = 0;
                // Add placeholder values for each store (e.g., zero values)
                foreach ($day_qty as $day_qtys) {
                    if ($day_qtys['item_id'] == $item['item_id']) {
                        $total_day_qtys += $day_qtys['item_qty'];
                        $itemData = array_merge($itemData, [$day_qtys['item_qty']]);
                    } else {
                        $total_day_qtys = 0;
                        $itemData = array_merge($itemData, [0]);
                    }
                }
                $itemData = array_merge($itemData, [$total_day_qtys]);
                // Add total stock (can calculate based on real data if needed)
                $itemData[] = 0; // Total Stock

                // Add item data to the sheet
                $sheet->fromArray($itemData, NULL, 'A' . $row);

                // Ensure zero values are displayed as numbers
                $sheet->getStyle('A' . $row . ':Z' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

                $row++;
            }
        }

        // Set the output file
        $writer = new Xlsx($spreadsheet);
        $dateObj = DateTime::createFromFormat('!m', $filter_month);
        $monthName = $dateObj->format('F');
        $filename = "Daily_Materials_{$monthName}_Issue_Format.xlsx";
        
        // Set headers for the browser to download the file as Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Write the file to the output
        $writer->save('php://output');
        exit();
    }
    public function stock_entry($store_id=null){
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('id', $store_id);
        $stores = $this->db->get()->result_array();
        $project_id = $stores[0]['proj_id'];
        $data['project_id'] = $project_id;
        $data['store_id'] = $store_id;

        $this->db->select('tblitems.id,tblitems.description');
        $this->db->from('tblboq_master');
        $this->db->join('tblboq_details', 'tblboq_master.boq_id = tblboq_details.boq_id');
        $this->db->join('tblitems', 'tblitems.id = tblboq_details.item_id');
        $this->db->where('tblboq_master.proj_id', $project_id);
        $this->db->where('tblboq_master.boq_title', 'Supply');
        $data['items'] = $this->db->get()->result();

        if ($this->input->post()) {
            // If form is submitted, insert BOQ master data
            $challan_no = $this->input->post('challan_no');
            $vehicle_no = $this->input->post('vehicle_no');
            $destination = $this->input->post('destination');
            $remarks = $this->input->post('remarks');
            $movement_date = date("Y-m-d");
            $created_at = date("Y-m-d H:i:s");

            // Insert stock details (items and quantities)
            $stock_item_ids = $this->input->post('stock_item_id');
            $stock_item_qtys = $this->input->post('stock_item_qty');

            if (!empty($stock_item_ids)) {
                foreach ($stock_item_ids as $key => $stock_item_id) {
                    $item_qty = $stock_item_qtys[$key];
                    $detail_data = array(
                        'challan_no' => $challan_no,
                        'vehicle_no' => $vehicle_no,
                        'store_id' => $store_id,
                        'item_id' => $stock_item_id,
                        'item_qty' => $item_qty,
                        'destination' => $destination,
                        'remarks' => $remarks,
                        'movement_type' => 'entry',
                        'movement_date' => $movement_date,
                        'created_at' => $created_at,
                    );
                    try{
                        $this->db->insert('tblstock_movement', $detail_data);
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                    }
                }
            }

            redirect(admin_url('inventory/' . $project_id));
            
        }
        $this->load->view('admin/inventory/stock_entry',$data);
    }
    public function list_stock_entries($store_id = null)
    {
        // Fetch stock entries based on store ID
        $this->db->select('*');
        $this->db->from('tblstock_movement');
        $this->db->where('store_id', $store_id);
        $this->db->where('movement_type', 'entry');
        $query = $this->db->get();

        // Check if data is available
        if ($query->num_rows() > 0) {
            $response = array(
                'status' => true,
                'message' => 'Stock entries fetched successfully',
                'data' => $query->result()
            );
        } else {
            $response = array(
                'status' => false,
                'message' => 'No stock entries found',
                'data' => []
            );
        }

        // Return response as JSON
        echo json_encode($response);
    }
    public function add_stock_entry()
    {
        // Fetch input data (you may need to modify this depending on your request format)
        $challan_no = $this->input->post('challan_no');
        $vehicle_no = $this->input->post('vehicle_no');
        $store_id = $this->input->post('store_id');
        $destination = $this->input->post('destination');
        $remarks = $this->input->post('remarks');
        $movement_date = date("Y-m-d");
        $created_at = date("Y-m-d H:i:s");

        $stock_item_ids = $this->input->post('stock_item_id');
        $stock_item_qtys = $this->input->post('stock_item_qty');

        // Check for required fields
        if (empty($challan_no) || empty($store_id) || empty($stock_item_ids)) {
            $response = array(
                'status' => false,
                'message' => 'Required fields are missing'
            );
            echo json_encode($response);
            return;
        }

        // Insert stock entries into tblstock_movement
        foreach ($stock_item_ids as $key => $stock_item_id) {
            $item_qty = $stock_item_qtys[$key];
            $detail_data = array(
                'challan_no' => $challan_no,
                'vehicle_no' => $vehicle_no,
                'store_id' => $store_id,
                'item_id' => $stock_item_id,
                'item_qty' => $item_qty,
                'destination' => $destination,
                'remarks' => $remarks,
                'movement_type' => 'entry',
                'movement_date' => $movement_date,
                'created_at' => $created_at,
            );

            try {
                $this->db->insert('tblstock_movement', $detail_data);
            } catch (Exception $e) {
                $response = array(
                    'status' => false,
                    'message' => 'Error occurred while adding stock entry: ' . $e->getMessage()
                );
                echo json_encode($response);
                return;
            }
        }

        $response = array(
            'status' => true,
            'message' => 'Stock entry added successfully'
        );
        echo json_encode($response);
    }

    public function stock_exit($store_id=null){
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('id', $store_id);
        $stores = $this->db->get()->result_array();
        $project_id = $stores[0]['proj_id'];
        $data['project_id'] = $project_id;
        $data['store_id'] = $store_id;

        $this->db->select('tblitems.id,tblitems.description');
        $this->db->from('tblboq_master');
        $this->db->join('tblboq_details', 'tblboq_master.boq_id = tblboq_details.boq_id');
        $this->db->join('tblitems', 'tblitems.id = tblboq_details.item_id');
        $this->db->where('tblboq_master.proj_id', $project_id);
        $this->db->where('tblboq_master.boq_title', 'Supply');
        $data['items'] = $this->db->get()->result();

        $this->db->select('*,tblsub_contractor.id as sub_id');
        $this->db->from('tblsub_contractor');
        $this->db->join('tblproject_sub_contractor', 'tblproject_sub_contractor.sub_contractor_id = tblsub_contractor.id');
        $this->db->where('tblproject_sub_contractor.proj_id', $project_id);
        $data['sub_contractors'] = $this->db->get()->result();

        if ($this->input->post()) {
            // If form is submitted, insert BOQ master data
            $challan_no = $this->input->post('challan_no');
            $vehicle_no = $this->input->post('vehicle_no');
            $destination = $this->input->post('destination');
            $remarks = $this->input->post('remarks');
            $sub_contractor_id = $this->input->post('sub_contractor_id');
            $movement_date = date("Y-m-d");
            $created_at = date("Y-m-d H:i:s");

            // Insert stock details (items and quantities)
            $stock_item_ids = $this->input->post('stock_item_id');
            $stock_item_qtys = $this->input->post('stock_item_qty');

            if (!empty($stock_item_ids)) {
                foreach ($stock_item_ids as $key => $stock_item_id) {
                    $item_qty = $stock_item_qtys[$key];
                    $detail_data = array(
                        'challan_no' => $challan_no,
                        'vehicle_no' => $vehicle_no,
                        'store_id' => $store_id,
                        'item_id' => $stock_item_id,
                        'item_qty' => $item_qty,
                        'destination' => $destination,
                        'remarks' => $remarks,
                        'sub_contractor_id' => $sub_contractor_id,
                        'movement_type' => 'exit',
                        'movement_date' => $movement_date,
                        'created_at' => $created_at,
                    );
                    try{
                        $this->db->insert('tblstock_movement', $detail_data);
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                    }
                }
            }

            redirect(admin_url('inventory/' . $project_id));
            
        }
        $this->load->view('admin/inventory/stock_exit',$data);
    }
    public function get_items_by_sub_contractor() {
        $sub_contractor_id = $this->input->post('sub_contractor_id');
        $project_id = $this->input->post('project_id');
        $this->db->select('tblitems.id, tblitems.description');
        $this->db->from('tblsub_contractorboq_master');
        $this->db->join('tblsub_contractorboq_details', 'tblsub_contractorboq_details.boq_id = tblsub_contractorboq_master.id');
        $this->db->join('tblitems', 'tblitems.id = tblsub_contractorboq_details.item_id');
        $this->db->join('tblproject_sub_contractor', 'tblproject_sub_contractor.sub_contractor_id = tblsub_contractorboq_master.sub_contractor_id');
        $this->db->where('tblproject_sub_contractor.proj_id', $project_id);
        $this->db->where('tblsub_contractorboq_master.sub_contractor_id', $sub_contractor_id);
        $items = $this->db->get()->result();
        echo json_encode($items);
    }
    public function get_item_details() {
        $item_id = $this->input->post('item_id');
    
        $this->db->select('tblitems.id, tblitems.description, tblitems.rate, tblitems.unit, tblsub_contractorboq_details.loa_qty');
        $this->db->select("(tblsub_contractorboq_details.loa_qty - 
            (SELECT COALESCE(SUM(tblstock_movement.item_qty), 0) 
            FROM tblstock_movement 
            WHERE tblstock_movement.item_id = tblitems.id 
            AND tblstock_movement.movement_type = 'entry'
            )) AS remaining_loa_qty", FALSE);
        $this->db->from('tblitems');
        $this->db->join('tblsub_contractorboq_details', 'tblsub_contractorboq_details.item_id = tblitems.id', 'left');
        $this->db->where('tblitems.id', $item_id);
        $query = $this->db->get();
        $result = $query->result();
        echo json_encode($result);
    }
    public function return_stock($store_id=null){
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('id', $store_id);
        $stores = $this->db->get()->result_array();
        $project_id = $stores[0]['proj_id'];
        $data['project_id'] = $project_id;
        $data['store_id'] = $store_id;

        $this->db->select('tblitems.id,tblitems.description');
        $this->db->from('tblboq_master');
        $this->db->join('tblboq_details', 'tblboq_master.boq_id = tblboq_details.boq_id');
        $this->db->join('tblitems', 'tblitems.id = tblboq_details.item_id');
        $this->db->where('tblboq_master.proj_id', $project_id);
        $this->db->where('tblboq_master.boq_title', 'Supply');
        $data['items'] = $this->db->get()->result();

        if ($this->input->post()) {
            // If form is submitted, insert BOQ master data
            $challan_no = $this->input->post('challan_no');
            $vehicle_no = $this->input->post('vehicle_no');
            $destination = $this->input->post('destination');
            $remarks = $this->input->post('remarks');
            $movement_date = date("Y-m-d");
            $created_at = date("Y-m-d H:i:s");

            // Insert stock details (items and quantities)
            $stock_item_ids = $this->input->post('stock_item_id');
            $stock_item_qtys = $this->input->post('stock_item_qty');

            if (!empty($stock_item_ids)) {
                foreach ($stock_item_ids as $key => $stock_item_id) {
                    $item_qty = $stock_item_qtys[$key];
                    $detail_data = array(
                        'challan_no' => $challan_no,
                        'vehicle_no' => $vehicle_no,
                        'store_id' => $store_id,
                        'item_id' => $stock_item_id,
                        'item_qty' => $item_qty,
                        'destination' => $destination,
                        'remarks' => $remarks,
                        'movement_type' => 'return',
                        'movement_date' => $movement_date,
                        'created_at' => $created_at,
                    );
                    try{
                        $this->db->insert('tblstock_movement', $detail_data);
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                    }
                }
            }

            redirect(admin_url('inventory/' . $project_id));
            
        }
        $this->load->view('admin/inventory/return_stock',$data);
    }
    public function list_stock_exits($store_id = null)
    {
        // Fetch stock exits based on store ID
        $this->db->select('*');
        $this->db->from('tblstock_movement');
        $this->db->where('store_id', $store_id);
        $this->db->where('movement_type', 'exit');
        $query = $this->db->get();

        // Check if data is available
        if ($query->num_rows() > 0) {
            $response = array(
                'status' => true,
                'message' => 'Stock exits fetched successfully',
                'data' => $query->result()
            );
        } else {
            $response = array(
                'status' => false,
                'message' => 'No stock exits found',
                'data' => []
            );
        }

        // Return response as JSON
        echo json_encode($response);
    }
    public function add_stock_exit()
    {
        // Fetch input data (you may need to modify this depending on your request format)
        $challan_no = $this->input->post('challan_no');
        $vehicle_no = $this->input->post('vehicle_no');
        $store_id = $this->input->post('store_id');
        $destination = $this->input->post('destination');
        $remarks = $this->input->post('remarks');
        $movement_date = date("Y-m-d");
        $created_at = date("Y-m-d H:i:s");

        $stock_item_ids = $this->input->post('stock_item_id');
        $stock_item_qtys = $this->input->post('stock_item_qty');

        // Check for required fields
        if (empty($challan_no) || empty($store_id) || empty($stock_item_ids)) {
            $response = array(
                'status' => false,
                'message' => 'Required fields are missing'
            );
            echo json_encode($response);
            return;
        }

        // Insert stock entries into tblstock_movement
        foreach ($stock_item_ids as $key => $stock_item_id) {
            $item_qty = $stock_item_qtys[$key];
            $detail_data = array(
                'challan_no' => $challan_no,
                'vehicle_no' => $vehicle_no,
                'store_id' => $store_id,
                'item_id' => $stock_item_id,
                'item_qty' => $item_qty,
                'destination' => $destination,
                'remarks' => $remarks,
                'movement_type' => 'exit',
                'movement_date' => $movement_date,
                'created_at' => $created_at,
            );

            try {
                $this->db->insert('tblstock_movement', $detail_data);
            } catch (Exception $e) {
                $response = array(
                    'status' => false,
                    'message' => 'Error occurred while adding stock exit: ' . $e->getMessage()
                );
                echo json_encode($response);
                return;
            }
        }

        $response = array(
            'status' => true,
            'message' => 'Stock exit added successfully'
        );
        echo json_encode($response);
    }
    public function stock_view($store_id = null)
    {
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y');
        // Fetch the store data based on store ID
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('id', $store_id);
        $data['stores'] = $this->db->get()->result_array();

        // Fetch entry quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $data['entry_qty'] = $this->db->get()->result_array();

        // Map quantities by store and item
        $store_item_qty_map = [];
        foreach ($data['entry_qty'] as $entry) {
            $store_item_qty_map[$entry['store_id']][$entry['item_id']] = $entry['item_qty'];
        }
        $data['store_item_qty_map'] = $store_item_qty_map;

        // Fetch exit quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $data['exit_qty'] = $this->db->get()->result_array();

        // Map exit quantities by store and item
        $store_item_exit_qty_map = [];
        foreach ($data['exit_qty'] as $exit) {
            $store_item_exit_qty_map[$exit['store_id']][$exit['item_id']] = $exit['item_qty'];
        }
        $data['store_item_exit_qty_map'] = $store_item_exit_qty_map;

        // Fetch return quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "return"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $data['return_qty'] = $this->db->get()->result_array();

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $data['day_qty'] = $this->db->get()->result_array();

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $data['day_issue_qty'] = $this->db->get()->result_array();

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $data['day_issue_qty'] = $this->db->get()->result_array();

        // Map return quantities by store and item
        $store_item_return_qty_map = [];
        foreach ($data['return_qty'] as $return) {
            $store_item_return_qty_map[$return['store_id']][$return['item_id']] = $return['item_qty'];
        }
        $data['store_item_return_qty_map'] = $store_item_return_qty_map;

        if (!empty($data['stores'])) {
            $project_id = $data['stores'][0]['proj_id'];
            $data['project_id'] = $project_id;

            // Fetch categories for the project
            $this->db->select('id, category_name');
            $this->db->from('tblproject_categories');
            $this->db->where('proj_id', $project_id);
            $categories = $this->db->get()->result_array();
            $data['categories'] = [];

            // Fetch items for each category
            foreach ($categories as $category) {
                $this->db->select('tblitems.description, tblitems.unit, tblattach_categories.*');
                $this->db->from('tblattach_categories');
                $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');
                $this->db->where('tblattach_categories.category_id', $category['id']);
                $this->db->where('tblattach_categories.proj_id', $project_id);
                $items = $this->db->get()->result_array();

                // Add category and its items to the data array
                $data['categories'][] = [
                    'category_name' => $category['category_name'],
                    'items' => $items
                ];
            }
        }

        // Load the view and pass data to it
        $this->load->view('admin/inventory/stock_view', $data);
    }
    public function get_stock_data()
    {
        $store_id = $this->input->post('store_id');
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y'); // Get the selected year or current year

        // Fetch entry quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);
        $entry_qty = $this->db->get()->result_array();
        
        // Fetch exit quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $exit_qty = $this->db->get()->result_array();
        
        // Fetch return quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "return"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $return_qty = $this->db->get()->result_array();
        
        // Format the result in a way that maps item_id to each quantity type (entry, exit, return)
        $data = [];
        foreach ($entry_qty as $entry) {
            $item_id = $entry['item_id'];
            $data[$item_id]['entry_qty'] = $entry['item_qty'];
        }

        foreach ($exit_qty as $exit) {
            $item_id = $exit['item_id'];
            $data[$item_id]['exit_qty'] = $exit['item_qty'];
        }

        foreach ($return_qty as $return) {
            $item_id = $return['item_id'];
            $data[$item_id]['return_qty'] = $return['item_qty'];
        }

        // Return JSON response
        echo json_encode(['status' => 'success', 'data' => $data]);
    }
    public function get_stock_receipt_data()
    {
        $store_id = $this->input->post('store_id');
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y'); // Get the selected year or current year

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);
        $day_qty = $this->db->get()->result_array();
        
        // Format the result in a way that maps item_id to each quantity type (entry, exit, return)
        $data = [];
        $total_day_qtys = 0;
        foreach ($day_qty as $day_qtys) {
            $item_id = $day_qtys['item_id'];
            $data[$item_id]['day_qty'] = $day_qtys['item_qty'];
            $data[$item_id]['total_qty'] += $day_qtys['item_qty'];
        }

        // Return JSON response
        echo json_encode(['status' => 'success', 'data' => $data]);
    }
    public function get_stock_issue_data()
    {
        $store_id = $this->input->post('store_id');
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y'); // Get the selected year or current year

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.id', $store_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);
        $day_qty = $this->db->get()->result_array();
        
        // Format the result in a way that maps item_id to each quantity type (entry, exit, return)
        $data = [];
        $total_day_qtys = 0;
        foreach ($day_qty as $day_qtys) {
            $item_id = $day_qtys['item_id'];
            $data[$item_id]['day_qty'] = $day_qtys['item_qty'];
            $data[$item_id]['total_qty'] += $day_qtys['item_qty'];
        }

        // Return JSON response
        echo json_encode(['status' => 'success', 'data' => $data]);
    }
    public function get_all_receipt_data()
    {
        $project_id = $this->input->post('project_id');
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y'); // Get the selected year or current year

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);
        $day_qty = $this->db->get()->result_array();
        
        // Format the result in a way that maps item_id to each quantity type (entry, exit, return)
        $data = [];
        $total_day_qtys = 0;
        foreach ($day_qty as $day_qtys) {
            $item_id = $day_qtys['item_id'];
            $store_id = $day_qtys['store_id'];
            $data[$store_id][$item_id]['day_qty'] = $day_qtys['item_qty'];
            $data[$store_id][$item_id]['total_qty'] += $day_qtys['item_qty'];
        }

        // Return JSON response
        echo json_encode(['status' => 'success', 'data' => $data]);
    }
    public function get_all_issue_data()
    {
        $project_id = $this->input->post('project_id');
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y'); // Get the selected year or current year

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);
        $day_qty = $this->db->get()->result_array();
        
        // Format the result in a way that maps item_id to each quantity type (entry, exit, return)
        $data = [];
        $total_day_qtys = 0;
        foreach ($day_qty as $day_qtys) {
            $item_id = $day_qtys['item_id'];
            $store_id = $day_qtys['store_id'];
            $data[$store_id][$item_id]['day_qty'] = $day_qtys['item_qty'];
            $data[$store_id][$item_id]['total_qty'] += $day_qtys['item_qty'];
        }

        // Return JSON response
        echo json_encode(['status' => 'success', 'data' => $data]);
    }
    public function get_overall_stock_data()
    {
        $project_id = $this->input->post('project_id');
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y'); // Get the selected year or current year

        // Fetch entry quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);
        $entry_qty = $this->db->get()->result_array();
        
        // Fetch exit quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $exit_qty = $this->db->get()->result_array();
        
        // Fetch return quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "return"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $return_qty = $this->db->get()->result_array();
        
        // Format the result in a way that maps item_id to each quantity type (entry, exit, return)
        $data = [];
        foreach ($entry_qty as $entry) {
            $item_id = $entry['item_id'];
            $store_id = $entry['store_id'];
            $data[$store_id][$item_id]['entry_qty'] = $entry['item_qty'];
        }

        foreach ($exit_qty as $exit) {
            $item_id = $exit['item_id'];
            $store_id = $exit['store_id'];
            $data[$store_id][$item_id]['exit_qty'] = $exit['item_qty'];
        }

        foreach ($return_qty as $return) {
            $item_id = $return['item_id'];
            $store_id = $return['store_id'];
            $data[$store_id][$item_id]['return_qty'] = $return['item_qty'];
        }

        // Return JSON response
        echo json_encode(['status' => 'success', 'data' => $data]);
    }
    public function overall_stock_view($project_id = null)
    {
        $filter_month = $this->input->post('filter_month') ?: date('m'); // Get the selected month or current month by default
        $filter_year = $this->input->post('filter_year') ?: date('Y');
        // Fetch the store data based on project ID
        $this->db->select('*');
        $this->db->from('tblstores');
        $this->db->where('proj_id', $project_id);
        $data['stores'] = $this->db->get()->result_array();

        // Fetch entry quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $data['entry_qty'] = $this->db->get()->result_array();

        // Map quantities by store and item
        $store_item_qty_map = [];
        foreach ($data['entry_qty'] as $entry) {
            $store_item_qty_map[$entry['store_id']][$entry['item_id']] = $entry['item_qty'];
        }
        $data['store_item_qty_map'] = $store_item_qty_map;

        // Fetch exit quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $data['exit_qty'] = $this->db->get()->result_array();

        // Map exit quantities by store and item
        $store_item_exit_qty_map = [];
        foreach ($data['exit_qty'] as $exit) {
            $store_item_exit_qty_map[$exit['store_id']][$exit['item_id']] = $exit['item_qty'];
        }
        $data['store_item_exit_qty_map'] = $store_item_exit_qty_map;

        // Fetch return quantities item-wise and store-wise
        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "return"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month);
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id']);  // Group by store_id and item_id
        $data['return_qty'] = $this->db->get()->result_array();

        // Map return quantities by store and item
        $store_item_return_qty_map = [];
        foreach ($data['return_qty'] as $return) {
            $store_item_return_qty_map[$return['store_id']][$return['item_id']] = $return['item_qty'];
        }
        $data['store_item_return_qty_map'] = $store_item_return_qty_map;

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "entry"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $data['day_qty'] = $this->db->get()->result_array();

        $this->db->select('tblstores.id AS store_id, tblstock_movement.item_id, tblstock_movement.destination, DATE_FORMAT(tblstock_movement.movement_date, "%d.%m.%y") AS movement_day, COALESCE(SUM(tblstock_movement.item_qty), 0) AS item_qty');
        $this->db->from('tblstores');
        $this->db->join('tblstock_movement', 'tblstores.id = tblstock_movement.store_id AND tblstock_movement.movement_type = "exit"', 'left');
        $this->db->where('tblstores.proj_id', $project_id);
        $this->db->where('MONTH(tblstock_movement.movement_date)', $filter_month); // Filter by the selected month
        $this->db->where('YEAR(tblstock_movement.movement_date)', $filter_year);   // Filter by the selected year
        $this->db->group_by(['tblstores.id', 'tblstock_movement.item_id', 'DAY(tblstock_movement.movement_date)']);  // Group by store_id, item_id, and day

        $data['day_issue_qty'] = $this->db->get()->result_array();

        // Fetch categories and items for the project
        if (!empty($data['stores'])) {
            $data['project_id'] = $project_id;

            // Fetch categories for the project
            $this->db->select('id, category_name');
            $this->db->from('tblproject_categories');
            $this->db->where('proj_id', $project_id);
            $categories = $this->db->get()->result_array();
            $data['categories'] = [];

            // Fetch items for each category
            foreach ($categories as $category) {
                $this->db->select('tblitems.id, tblitems.description, tblitems.unit, tblattach_categories.*');
                $this->db->from('tblattach_categories');
                $this->db->join('tblitems', 'tblattach_categories.item_id = tblitems.id');
                $this->db->where('tblattach_categories.category_id', $category['id']);
                $this->db->where('tblattach_categories.proj_id', $project_id);
                $items = $this->db->get()->result_array();

                // Add category and its items to the data array
                $data['categories'][] = [
                    'category_name' => $category['category_name'],
                    'items' => $items
                ];
            }
        }

        // Load the view and pass data to it
        $this->load->view('admin/inventory/overall_stock_view', $data);
    }
}