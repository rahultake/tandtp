<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="solution_category_steps">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($category) ? 'col-md-9' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 id="editStepModalLabel">Edit Step</h3>
                        <h4>for <?php echo $category->sol_cat_title; ?></h4>
                        <?php echo form_open('admin/solution-categories/update_step/'); ?>
                        <form method="post" action="<?php echo site_url('admin/solution-categories/update_step/' . $step->step_id); ?>">
                            <div class="form-group">
                                <input type="hidden" name="step_id" value="<?php echo $step->step_id;?>">
                                <label for="step_title">Step Title</label>
                                <input type="text" class="form-control" id="step_title" name="step_title" value="<?php echo $step->step_title; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
