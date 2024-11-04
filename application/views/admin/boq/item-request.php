<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="boqs">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($boqs) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/boq/boq_tabs'); ?>
                </div>
                <div class="panel_s">
                    <div class="panel-heading">
                        <h3>Item Requisition
                            <div class="label label-primary">Project: <?php echo $projects[0]->name?></div> 
                            <div class="label label-<?php echo $status_class;?>">BOQ Status: <?php echo $boqs[0]->boq_status?></div>
                        </h3>
                    </div>
                <?php echo form_open('admin/boq/request-items/'.$boqs[0]->boq_id);?>
                    <input type="hidden" name="boq_id" value="<?php echo $boqs[0]->boq_id; ?>">
                    <div class="panel-body">
                        <div class="row">
                            <strong class="col-lg-12"><?php echo $boqs[0]->boq_title;?></strong>
                            <span class="col-lg-12 text-info"><?php echo $boqs[0]->remarks; ?></span>
                            <div class="col-lg-4">
                                <label >Request Type</label>
                                <select class="form-control" name="req_type">
                                    <option>Regular</option>
                                    <option>Extra</option>
                                    <option>Sample</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body table-responsive ">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox"></th><th>Item</th>
                                    <th class="text-right">Approved Qty in BOQ</th>
                                    <th class="text-right">Issued Qty</th>
                                    <th class="text-right">Requested Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($boq_items as $boq_item): 
                                ?>
                                <tr>
                                    <td class="text-center"><input type="checkbox" value="<?php echo $boq_item->item_id; ?>"  name="item_id[]" /></td>
                                    <td><?php echo $boq_item->description; ?></td>
                                    <td class="text-right"><?php echo $boq_item->approved_qty; ?></td>
                                    <td class="text-right"><?php echo $boq_item->issued_qty; ?></td>
                                    <td class="text-right"><input type="number" style="width:100px" class="form-control text-center pull-right" value="<?php echo $boq_item->requested_qty; ?>" name="requested_qty[]" /></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <div class="form-group col-lg-8">
                            <input type="text" id="remarks" name="remarks" class="form-control"  required placeholder="Remarks">
                        </div>
                        <button type="submit" name="request_items" value="Approved" class="btn btn-primary"><i class="fa fa-arrow-right"></i> Raise Requisition</button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>