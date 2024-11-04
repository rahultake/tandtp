<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="solution_category_steps">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($solution_category) ? 'col-md-9' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- Display solution category details -->
                        <?php if (!empty($solution_category)) { ?>
                            <h3>Solution Category Details:</h3>
                            <p>Solution Category ID: <?php echo $solution_category->sol_cat_id; ?></p>
                            <p>Solution Category Title: <?php echo $solution_category->sol_cat_title; ?></p>
                            <!-- Add more details as needed -->
                            <!-- Form to add new steps -->
                            <?php echo form_open('admin/solution-categories/add_step'); ?>
                                <input type="hidden" name="sol_cat_id" value="<?php echo $solution_category->sol_cat_id; ?>">

                                <div class="form-group" app-field-wrapper="step_title">
                                    <label for="step_title" class="control-label">
                                        <small class="req text-danger">* </small>Step Title
                                    </label>
                                    <input type="text" id="step_title" name="step_title" class="form-control" value="" placeholder="Step Title">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Step</button>
                            </form>
                        <?php } else { ?>
                            <p>No solution category details found.</p>
                        <?php } ?>
                        </div>
                        </div>  
                        <div class="panel_s">
                        <div class="panel-body">
                        <!-- Display the steps for the selected solution category -->
                        <?php if (!empty($steps)) { ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <th>#</th>
                                        <th>Step Title</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($steps as $step) { ?>
                                            <tr>
                                                <td><?php echo $step->step_id; ?></td>
                                                <td><?php echo $step->step_title; ?></td>
                                                <td>
                                                    <!-- Add links or buttons for actions (Edit, Delete, etc.) -->
                                                    <!-- Example: -->
                                                    <a class="btn btn-primary" href="<?php echo site_url('admin/solution-categories/edit_step/' . $step->step_id); ?>"><i class="fa fa-pencil"></i> Edit</a>
                                                    
                                                    <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this step?');" href="<?php echo site_url('admin/solution-categories/delete_step/' . $step->step_id); ?>"><i class="fa fa-trash"></i> Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <p>No steps found for the selected solution category.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

</body>
</html>
