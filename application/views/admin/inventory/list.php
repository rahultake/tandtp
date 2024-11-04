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
                        <h3>Stores 
                            <a href="../../admin/projects/view/<?php echo $project_id?>" class="label label-primary">Project: <?php echo $projects[0]->name?></a> 
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#storeModal" style="float: inline-end;">
                            <i class="fa-regular fa-plus tw-mr-1"></i> Create Store
                            </button>
                            <a href="<?php echo admin_url('inventory/overall_stock_view/' . $project_id); ?>" class="btn btn-info" style="float: inline-end;margin-right: 4px;">
                            <i class="fa-regular fa-eye tw-mr-1"></i> View Overall Stock
                            </a>
                            <!-- <a href="<?php echo admin_url('inventory/export_to_csv/' . $project_id); ?>" class="btn btn-success" style="float: inline-end;margin-right: 4px;">
                            <i class="fas fa-file-export tw-mr-1"></i> Export Overall Stock
                            </a> -->
                        </h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stores as $store) : ?>
                                    <tr>
                                        <td>#<?php echo sprintf("%04d",$store->id); ?></td>
                                        <td><?php echo $store->store_name; ?></td>
                                        <td><?php echo $store->store_address; ?></td>
                                        <td><?php echo $store->created_at; ?></td>
                                        <td class="text-nowrap">
                                            <a class="btn-sm btn btn-primary" data-toggle="modal" data-target="#storeModal" onclick="editStore(<?php echo $store->id; ?>)" title="Edit Store"><i class="fa fa-pencil"></i></a>
                                            <a class="btn-sm btn btn-danger" href="<?php echo admin_url('inventory/delete/' . $store->id); ?>" title="Delete Store"><i class="fa fa-trash"></i></a>
                                            <!-- <a class="btn-sm btn btn-success" href="<?php echo admin_url('inventory/export_against_store/' . $store->id); ?>" title="Export Store">Export</a> -->
                                            <a class="btn-sm btn btn-primary" href="<?php echo admin_url('inventory/stock_entry/' . $store->id); ?>" title="Stock Entry">Stock Entry</a>
                                            <a class="btn-sm btn btn-primary" href="<?php echo admin_url('inventory/stock_exit/' . $store->id); ?>" title="Stock Exit">Stock Exit</a>
                                            <a class="btn-sm btn btn-primary" href="<?php echo admin_url('inventory/return_stock/' . $store->id); ?>" title="Return Stock">Return Stock</a>
                                            <a class="btn-sm btn btn-info" href="<?php echo admin_url('inventory/stock_view/' . $store->id); ?>" title="View">View</a>
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
<div class="modal fade" id="storeModal" tabindex="-1" role="dialog" aria-labelledby="storeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <?php echo form_open(admin_url('inventory/stores/' . $project_id), ['id' => 'store_form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <span class="edit-title hide">Edit Store</span>
            <span class="add-title">Create Store</span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="store_id" id="store_id" value="">
                <input type="hidden" name="project_id" value="<?php echo $project_id;?>">
                <div class="form-group" app-field-wrapper="store_name">
                    <label for="store_name" class="control-label"> <small class="req text-danger">* </small>Name</label>
                    <input type="text" id="store_name" name="store_name" class="form-control" required>
                </div>                        
                <div class="form-group" app-field-wrapper="store_address"><label for="store_address" class="control-label">Address</label>
                    <textarea id="store_address" name="store_address" class="form-control" rows="4"></textarea>
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
    function editStore(storeId) {
        $.ajax({
            url: admin_url + 'inventory/get_store/' + storeId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#store_id').val(response.id);
                $('#store_name').val(response.store_name);
                $('#store_address').val(response.store_address);
                $('.edit-title').removeClass('hide');
                $('.add-title').addClass('hide');
            }
        });
    }
</script>
</body>
</html>
