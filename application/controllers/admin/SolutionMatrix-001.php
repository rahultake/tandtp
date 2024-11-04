<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SolutionMatrix extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function matrix($project_id)
    {
        // Load necessary data (locations, steps, matrix records)
        $data['project_id'] = $project_id;
        $data['locations'] = $this->db->where('proj_id', $project_id)->get('tblproject_locations')->result();
        $data['steps'] = $this->db->get('tblsolution_category_steps')->result();
        $data['matrix'] = $this->db->where('proj_id', $project_id)->get('tblsolution_matrix')->result();

        $this->load->view('admin/solution-matrix/matrix', $data);
    }
    
    
    public function insert_record()
    {
        // Implement logic to insert records into tblsolution_matrix
        // ...
    }

    public function delete_record()
    {
        // Implement logic to delete records from tblsolution_matrix
        // ...
    }

}
?>