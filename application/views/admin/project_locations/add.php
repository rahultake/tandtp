<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="solution_category_steps">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($locations) ? 'col-md-9' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3>Add Project Location</h3>
                        <?php echo form_open('admin/project-locations/add/'.$project_id); ?>
                        
                            <label for="location_title">Location Title:</label>
                            <input class="form-control" type="text" name="location_title" required>
                            <br>
                            <label for="sol_cat_id">Solution Category:</label>
                            <select class="form-control" name="sol_cat_id" required>
                                <?php foreach ($solution_categories as $category): ?>
                                    <option value="<?php echo $category->sol_cat_id; ?>"><?php echo $category->sol_cat_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <br>    
                            <button type="submit" class="btn btn-primary">Add Location</button>
                            <?php echo form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("list.php");?>
<?php init_tail(); ?>
</body>
</html>
