<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="project_locations">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($locations) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
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
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Location</button>
                        </form>
                    </div>
                </div>    
            <div class="panel_s">
                    <div class="panel-body">

<h3>Project Locations</h3>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Location ID</th>
            <th>Location Title</th>
            <th>Solution Category</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($locations as $location) : ?>
            <tr>
                <td>#<?php echo sprintf("%04d",$location->loc_id); ?></td>
                <td><?php echo $location->location_title; ?></td>
                <td><?php echo $location->sol_cat_title; ?></td>
                <td>
                    <a class="btn btn-primary" href="<?php echo admin_url('project-locations/edit/' . $project_id . '/' . $location->loc_id); ?>"><i class="fa fa-pencil"></i> Edit</a>
                    <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this location?');" href="<?php echo admin_url('project-locations/delete/' . $project_id . '/' . $location->loc_id); ?>"><i class="fa fa-trash"></i> Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
<?php init_tail(); ?>
        </body>
        </html>
