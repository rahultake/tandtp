<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SolutionCategories extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function index()
    {
        if ($this->input->post('sol_cat_title')) {
            $sol_cat_title = $this->input->post('sol_cat_title');
            if (!empty($sol_cat_title)) {
                $data = array(
                    'sol_cat_title' => $sol_cat_title
                );
                $this->db->insert('tblsolution_categories', $data);
                redirect('admin/solution-categories');
            } else {
                $this->session->set_flashdata('error', 'Solution Category Title cannot be empty.');
            }
        }
        $data['solution_categories'] = $this->db->get('tblsolution_categories')->result();
        $this->load->view('admin/solution-categories/list', $data);
    }
    public function edit($sol_cat_id)
    {
        $data['solution_categories'] = $this->db
                        ->where('sol_cat_id', $sol_cat_id)
                        ->get('tblsolution_categories')->result();
        $this->load->view('admin/solution-categories/edit', $data);
    }
    public function update($sol_cat_id)
    {
        if ($this->input->post('sol_cat_title')) {
            // $step_id = $this->input->post('step_id');
            // $step = $this->db->where('step_id', $step_id)->get('tblsolution_category_steps')->row();
            // $sol_cat_id = $step->sol_cat_id;
            // if (!$step) {
            //     redirect('admin/solution-categories/');
            // }
            $data = array(
                'sol_cat_title' => $this->input->post('sol_cat_title'),
            );
            $this->db->where('sol_cat_id', $sol_cat_id)->update('tblsolution_categories', $data);
            redirect('admin/solution-categories/');
        } else {
            redirect('admin/solution-categories');
        }
    }

    public function delete($sol_cat_id)
    {
        if (!$sol_cat_id) {
            redirect('admin/solution-categories');
        }
        $category_exists = $this->db->where('sol_cat_id', $sol_cat_id)
                                    ->get('tblsolution_categories')
                                    ->num_rows() > 0;
        if (!$category_exists) {
            redirect('admin/solution-categories');
        }
        $this->db->where('sol_cat_id', $sol_cat_id)
                 ->delete('tblsolution_categories');
        redirect('admin/solution-categories');
    }


    public function steps($sol_cat_id)
    {
        $data['solution_category'] = $this->db->where('sol_cat_id', $sol_cat_id)
                                            ->get('tblsolution_categories')
                                            ->row();
        $data['steps'] = $this->db->where('sol_cat_id', $sol_cat_id)
                                ->get('tblsolution_category_steps')
                                ->result();
        $this->load->view('admin/solution-categories/steps', $data);
    }

    public function add_step()
    {
        if ($this->input->post('sol_cat_id') && $this->input->post('step_title')) {
            // Get form data
            $sol_cat_id = $this->input->post('sol_cat_id');
            $step_title = $this->input->post('step_title');

            // Check if the solution category exists
            $category_exists = $this->db->where('sol_cat_id', $sol_cat_id)
                                        ->get('tblsolution_categories')
                                        ->num_rows() > 0;

            if ($category_exists) {
                // Insert the new step
                $data = array(
                    'sol_cat_id' => $sol_cat_id,
                    'step_title' => $step_title,
                    // Add other fields if needed
                );

                $this->db->insert('tblsolution_category_steps', $data);

                // Redirect back to the steps page after insertion
                redirect('admin/solution-categories/steps/' . $sol_cat_id);
            } else {
                // Handle the case where the solution category does not exist
                $data['error'] = 'Selected solution category does not exist.';
            }
        } else {
            // Handle the case where the form is not submitted with required data
            $data['error'] = 'Invalid form submission.';
        }
        $this->load->view('admin/solution-categories/steps', $data);
    }
    public function edit_step($step_id)
    {
        $data['step'] = $this->db->where('step_id', $step_id)->get('tblsolution_category_steps')->row();
        $category_id = $data['step']->sol_cat_id;
        $data['category'] = $this->db->where('sol_cat_id', $category_id)->get('tblsolution_categories')->row();
        $this->load->view('admin/solution-categories/edit_step', $data);
    }
    public function update_step()
    {
        if ($this->input->post('step_id')) {
            $step_id = $this->input->post('step_id');
            $step = $this->db->where('step_id', $step_id)->get('tblsolution_category_steps')->row();
            $sol_cat_id = $step->sol_cat_id;
            if (!$step) {
                redirect('admin/solution-categories/steps/'.$sol_cat_id);
            }
            $data = array(
                'step_title' => $this->input->post('step_title'),
            );
            $this->db->where('step_id', $step_id)->update('tblsolution_category_steps', $data);
            redirect('admin/solution-categories/steps/'.$sol_cat_id);
        } else {
            redirect('admin/solution-categories');
        }
    }

    public function delete_step($step_id)
    {
        if (!$step_id) {
            redirect('admin/solution-categories');
        }
        $this->load->database();
        $step_exists = $this->db->where('step_id', $step_id)
                                ->get('tblsolution_category_steps')
                                ->row();
        if (!$step_exists) {
            redirect('admin/solution-categories');
        }
        else{
            $sol_cat_id = $step_exists->sol_cat_id;
        }
        $this->db->where('step_id', $step_id)
                 ->delete('tblsolution_category_steps');
        redirect('admin/solution-categories/steps/' . $sol_cat_id);
    }
}
?>