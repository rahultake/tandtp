<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
    init_head(); 
?>
<div id="wrapper" class="solution_category">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($solution_categories) ? 'col-md-9' : 'col-md-8 col-md-offset-2'; ?>">
                <?php echo form_open('admin/solution-categories'); ?>
                <div class="panel_s">
                    <div class="panel-body">
                                <div class="form-group" app-field-wrapper="sol_cat_title"><label for="sol_cat_title" class="control-label">
                                    <small class="req text-danger">* </small>Solution Category Title</label>
                                    <input type="text" id="sol_cat_title" name="sol_cat_title" class="form-control" autofocus="1" value="" placeholder="Solution Category Title">
                                </div>
                            </div>
                            <div class="panel-footer text-right tw-space-x-1" id="profile-save-section">     
                                <button class="btn btn-primary only-save customer-form-submiter">
                                    <i class="fa fa-plus"></i> <?php echo _l('submit'); ?>
                                </button>
                            </div>
                            <?php echo form_close(); ?>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <th>#</th>
                                    <th>Catgory</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <?php if (!empty($solution_categories)) { ?>
                                        <?php foreach ($solution_categories as $category) { ?>
                                            <tr>
                                                <td><?php echo $category->sol_cat_id; ?></td>
                                                <td><?php echo $category->sol_cat_title; ?></td>
                                                <td class="text-cetner">
                                                    <a href="<?php echo base_url('admin/solution-categories/edit/' . $category->sol_cat_id); ?>" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-pencil"></i> <?php echo _l('edit'); ?>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $category->sol_cat_id; ?>)">
                                                    <i class="fa fa-trash"></i> <?php echo _l('delete'); ?>
                                                    </button>
                                                    <a href="<?php echo base_url('admin/solution-categories/steps/' . $category->sol_cat_id); ?>" class="btn btn-info btn-sm">
                                                    <i class="fa fa-arrow-right"></i> <?php echo _l('Steps'); ?>
                                                    </a>


                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3">No solution categories found.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php init_tail(); ?>
<script>
    function confirmDelete(sol_cat_id) {
        if (confirm('<?php echo _l('are you sure you want to delete this category?'); ?>')) {
            window.location.href = '<?php echo base_url('admin/solution-categories/delete/'); ?>' + sol_cat_id;
        }
    }
</script>
</body>
</html>