<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 col-md-12">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/inventory/inventory_tabs'); ?>
                </div>
                <div class="panel_s">
                    <div class="panel-heading">
                        <h3>Inventory Details 
                            <div class="label label-primary">Project: <?php echo $projects[0]->name?></div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            
                            <table>
                                <tr>
                                    <td>
                                        <strong class="col-lg-12">Category Name: <?php echo $attach[0]->category_name;?></strong>
                                        <span class="col-lg-12 text-small">Category Description: <?php echo $attach[0]->category_description; ?></span>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                        <div class="row">
                            
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                    <table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Item Name</th>
            <th class="text-right">Units</th>
        </tr>
    </thead>
    <tbody>
        <?php
        ini_set("display_errors",1);
        foreach($attach_items as $attach_item):            
        ?>
            <tr>
                <td><span class="text-dark"><?php echo $attach_item->description; ?></span><small class="clearfix text-primary"><?php echo nl2br(htmlspecialchars_decode($attach_item->long_description)); ?></small></td>                
                <td class="text-right"><?php echo $attach_item->unit; ?></td>
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