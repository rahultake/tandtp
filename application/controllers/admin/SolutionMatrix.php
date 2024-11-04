<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SolutionMatrix extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function matrix($project_id=0, $sol_cat_id=0)
    {
        if(!isset($project_id)){
            $project_id = $this->input->post('proj_id');
        }
        $sol_cat_id = $this->input->post('sol_cat_id');
        $data['selected_project'] = $project_id;
        $data['project_id'] = $project_id;
        $data['sol_cat_id'] = $sol_cat_id;
        $data['projects'] = $this->db->get('tblprojects')->result();
        $data['solution_categories'] = $this->db->get('tblsolution_categories')->result();
        $data['locations'] = $this->db->where('proj_id', $project_id)->where('sol_cat_id', $sol_cat_id)->get('tblproject_locations')->result();
        $data['steps'] = $this->db->where('sol_cat_id', $sol_cat_id)->get('tblsolution_category_steps')->result();
        $sql = "SELECT pl.*, sm.step_id AS matrix_step_id
        FROM tblproject_locations pl
        LEFT JOIN tblsolution_matrix sm ON pl.proj_id = sm.proj_id AND pl.loc_id = sm.loc_id AND sm.sol_cat_id = ?
        WHERE pl.proj_id = ? AND pl.sol_cat_id = ?";

    $query = $this->db->query($sql, array($sol_cat_id, $project_id, $sol_cat_id));

    $data['matrix'] = $query->result();

        $this->load->view('admin/solution-matrix/matrix', $data);
    }
    
    
    public function update_matrix()
    {
        // Get data from the AJAX request
        $proj_id = $this->input->post('proj_id');
        $loc_id = $this->input->post('loc_id');
        $step_id = $this->input->post('step_id');
        $sol_cat_id = $this->input->post('sol_cat_id');
        $isChecked = $this->input->post('isChecked');
        // Perform the update or delete based on the checkbox state
        if ($isChecked=='yes') {
            // Insert the record
            $data = array(
                'proj_id' => $proj_id,
                'sol_cat_id' => $sol_cat_id,
                'loc_id' => $loc_id,
                'step_id' => $step_id,
                'step_status' => 'Complete'
            );
            $this->db->insert('tblsolution_matrix', $data);
        } 
        else {
            // Delete the record
            $this->db->where('proj_id', $proj_id)
                    ->where('loc_id', $loc_id)
                    ->where('step_id', $step_id)
                    ->where('sol_cat_id', $sol_cat_id)
                    ->delete('tblsolution_matrix');
        }
        $response = array('status' => 'success', 'message' => 'Matrix record updated successfully',  'Checked'=>$isChecked);
        echo json_encode($response);
    }
}
?>