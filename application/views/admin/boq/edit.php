<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="project_locations">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($location) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/boq/boq_tabs'); ?>
                </div>
                <div class="panel_s">
                <?php echo form_open('admin/project-locations/edit/'.$project->id."/".$location->loc_id); ?>
                    <div class="panel-body">
                        <h3>Edit Project Location</h3>
                        for Project <strong><?php echo $project->name;?></strong>
                        <div class="form-group" app-field-wrapper="location_title">
                            <label for="location_title" class="control-label">
                                <small class="req text-danger">* </small>Location Title
                            </label>
                            <input type="text" id="location_title" name="location_title" class="form-control" value="<?php echo $location->location_title; ?>" placeholder="Location Title">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Location</button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>