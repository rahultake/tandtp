<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ProjectLocations extends AdminController
{
    public function index($project_id)
    {
        $data['project_id'] = $project_id;
        // Fetch project locations based on $project_id from the tblproject_locations table
        $data['locations'] = $this->db->where('proj_id', $project_id)->get('tblproject_locations')->result();

        // Load the view to list project locations
        $this->load->view('admin/project_locations/list', $data);
    }

    public function add($project_id)
    {
        // Handle form submission to add a new project location
        if ($this->input->post('location_title')) {
            $location_title = $this->input->post('location_title');

            // Insert the new project location
            $data = array(
                'location_title' => $location_title,
                'proj_id' => $project_id,
            );
            $this->db->insert('tblproject_locations', $data);

            // Redirect back to the project locations page
            redirect(admin_url('project-locations/' . $project_id));
        }

        // Load the view to add a new project location
        $this->load->view('admin/project_locations/add', $data);
    }

    public function edit($project_id, $location_id)
    {
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

    public function delete($project_id, $location_id)
    {
        // Implement logic to delete the project location based on $location_id
        $this->db->where('loc_id', $location_id)->delete('tblproject_locations');

        // Redirect back to the project locations page after deletion
        redirect(admin_url('project-locations/' . $project_id));
    }
}