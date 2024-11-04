<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="project_locations">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 col-md-12">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/inventory/inventory_tabs'); ?>
                </div>
                <div class="panel_s">
                    <div class="panel-heading">
                        <h3>Categories 
                            <a href="../../admin/projects/view/<?php echo $project_id?>" class="label label-primary">Project: <?php echo $projects[0]->name?></a> 
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryModal" style="float: inline-end;">
                            <i class="fa-regular fa-plus tw-mr-1"></i> Create Category
                            </button>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($project_categories as $project_category) : ?>
                                    <tr>
                                        <td>#<?php echo sprintf("%04d",$project_category->id); ?></td>
                                        <td><?php echo $project_category->category_name; ?></td>
                                        <td><?php echo $project_category->category_description; ?></td>
                                        <td><?php echo $project_category->created_at; ?></td>
                                        <td class="text-nowrap">
                                            <a class="btn-sm btn btn-primary" data-toggle="modal" data-target="#categoryModal" onclick="editCategory(<?php echo $project_category->id; ?>)" title="Edit Category"><i class="fa fa-pencil"></i></a>
                                            <a class="btn-sm btn btn-danger" href="<?php echo admin_url('inventory/deletecategory/' . $project_category->id); ?>" title="Delete Category"><i class="fa fa-trash"></i></a>
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
<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <?php echo form_open(admin_url('inventory/categories/' . $project_id), ['id' => 'category_form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <span class="edit-title hide">Edit Category</span>
            <span class="add-title">Create Category</span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="category_id" id="category_id" value="">
                <input type="hidden" name="project_id" value="<?php echo $project_id;?>">
                <div class="form-group" app-field-wrapper="category_name">
                    <label for="category_name" class="control-label"> <small class="req text-danger">* </small>Name</label>
                    <input type="text" id="category_name" name="category_name" class="form-control" required>
                </div>                        
                <div class="form-group" app-field-wrapper="category_description"><label for="category_description" class="control-label">Description</label>
                    <textarea id="category_description" name="category_description" class="form-control" rows="4"></textarea>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
  </div>
  <?php echo form_close(); ?>
</div>
<?php init_tail(); ?>
<script>
    function editCategory(categoryId) {
        $.ajax({
            url: admin_url + 'inventory/get_category/' + categoryId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#category_id').val(response.id);
                $('#category_name').val(response.category_name);
                $('#category_description').val(response.category_description);
                $('.edit-title').removeClass('hide');
                $('.add-title').addClass('hide');
            }
        });
    }
</script>
</body>
</html>
