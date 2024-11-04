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
                        <h3>Attach Categories to items
                            <a href="../../admin/projects/view/<?php echo $project_id?>" class="label label-primary">Project: <?php echo $projects[0]->name?></a> 
                            <a href="<?php echo admin_url('inventory/attach_categories_items/'.$project_id)?>" class="btn btn-primary" style="float: inline-end;">
                                <i class="fa-regular fa-plus tw-mr-1"></i> Attach Categories to items
                            </a>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!-- Add Export Button -->
                        <!-- <a href="<?php echo admin_url('inventory/export_to_csv/'.$project_id)?>" class="btn btn-success">Export to CSV</a> -->
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attach_categories as $attach_category) : ?>
                                    <tr>
                                        <td>#<?php echo sprintf("%04d",$attach_category->id); ?></td>
                                        <td><?php echo $attach_category->category_name; ?></td>
                                        <td><?php echo $attach_category->created_at; ?></td>
                                        <td class="text-nowrap">
                                            <!-- <a class="btn-sm btn btn-primary" data-toggle="modal" data-target="#categoryModal" onclick="editCategory(<?php echo $attach_category->id; ?>)" title="Edit Category"><i class="fa fa-pencil"></i></a> -->
                                            <a class="btn-sm btn btn-primary" href="<?php echo admin_url('inventory/view/' . $attach_category->id); ?>" title="View"><i class="fa fa-eye"></i></a>
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