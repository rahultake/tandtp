<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="boq-report">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($boqs) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/boq/boq_tabs'); ?>
                </div>
                <div class="panel_s">
                    <div class="panel-heading">
                        <h3>Bill of Material 
                            <a href="../../admin/projects/view/<?php echo $projects[0]->id?>" class="label label-primary">Project: <?php echo $projects[0]->name?></a> 
                        </h3>   
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Item Description</th>
                                    <th>Item Type</th>
                                    <th>Approved Qty</th>
                                    <th>Requested Qty</th>
                                    <th>Issued Qty</th>
                                    <th>Extra Qty Request</th>
                                    <th>Extra Qty Approved</th>
                                    <th>Extra Qty Issued</th>
                                    <th>Balance Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item) : ?>
                                    <tr>
                                        <td>#<?php echo sprintf("%04d",$item->item_id); ?></td>
                                        <td><?php echo $item->description; ?></td>
                                        <td><?php echo $item->item_type; ?></td>
                                        <td><?php echo $item->approved_qty; ?></td>
                                        <td><?php echo $item->requested_qty; ?></td>
                                        <td><?php echo $item->issued_qty; ?></td>
                                        <td><?php echo $item->extra_requested_qty; ?></td>
                                        <td><?php echo $item->extra_approved_qty; ?></td>
                                        <td><?php echo $item->extra_issued_qty; ?></td>
                                        <td><?php echo $item->balance_qty; ?></td>
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
