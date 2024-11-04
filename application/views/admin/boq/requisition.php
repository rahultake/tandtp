<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="boqs">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($requisitions) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/boq/boq_tabs'); ?>
                </div>
                <div class="panel_s">
                    <?php echo form_open('admin/boq/update-requisition-status/'.$requisitions[0]->boq_id.'/'.$requisitions[0]->req_id); ?>
                    <div class="panel-heading">
                        <h3>Issue Items</h3>
                            <a class="label label-xlg label-primary arrowed-in arrowed-right" href="../../../projects/view/<?php echo $projects[0]->id?>" >Project: <?php echo $projects[0]->name?></a>
                            <div class="label label-xlg arrowed-in arrowed-right label-<?php echo $status_class;?>">Status: <?php echo $requisitions[0]->req_status?></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <strong class="col-lg-12">#<?php echo $requisitions[0]->req_id;?></strong>
                            <i class="col-lg-12 text-small"><?php echo $requisitions[0]->remarks; ?></i>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-right">Approved</th>
                                    <th class="text-right text-nowrap">Qty Issued</th>
                                    <th class="text-right text-nowrap">Qty Requested</th>
                                    <th class="text-right text-nowrap">Item Serial No</th>
                                    <th class="text-center text-nowrap">Bill</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($req_items as $req_item): 
                                $approved_qty = $req_item->approved_qty;
                                $issued_qty = $req_item->issued_qty;
                                ?>
                                <tr>
                                    <td><?php echo $req_item->description; ?><input class="hide" name="item_id[]" value="<?php echo $req_item->item_id?>" /></td>
                                    <td class="text-right"><?php echo $approved_qty; ?></td>
                                    <td class="text-right"><?php echo $issued_qty; ?></td>
                                    <td class="text-right"><input type="number" class="form-control text-center" name="requested_item_qty[]" style="width: 100px;" value="<?php echo $req_item->requested_qty; ?>" /></td>
                                    <td class="text-right"><input type="text" class="form-control text-center" name="issued_item_serial_no[]" style="width: 100px;" value="<?php echo $req_item->serial_no;?>" /></td>
                                    <td class="text-cnter"><input type="file" class="form-control" name="item_file_<?php echo $req_item->item_id?>"></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($requisitions[0]->req_status!="Approved" && $requisitions[0]->req_status!="Issued" && $requisitions[0]->req_status!="Received"){ ?>
                    <div class="panel-footer">
                            <div class="form-group col-lg-8" app-field-wrapper="location_title">
                                <input type="text" id="remarks" name="remarks" class="form-control"  required placeholder="Remarks">
                            </div>
                            <button type="submit" name="req_status" value="Issued" class="btn btn-primary"><i class="fa fa-upload fa-rotate-90"></i> Issue</button>
                            <button type="submit" name="req_status" value="Rejected" class="btn btn-danger"><i class="fa fa-ban"></i> Reject</button>
                    </div>
                    <?php }?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>