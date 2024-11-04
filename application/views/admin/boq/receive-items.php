<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //ini_set("display_errors",1);?>
<div id="wrapper" class="issues">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($issues) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/boq/boq_tabs'); ?>
                </div>
                <div class="panel_s">
                    <?php echo form_open('admin/boq/recieve/'.$boq_id.'/'.$issues[0]->req_id); ?>
                    <div class="panel-heading">
                        <h3>
                            <?php
                                if(isset($_GET['type'])){
                                    echo "Return";
                                }
                                else{
                                    echo "Recieve";
                                }
                            ?> Items</h3>
                            
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <strong class="col-lg-12">#<?php echo $issues[0]->issue_id;?></strong>
                            <i class="col-lg-12 text-small"><?php echo $issues[0]->remarks; ?></i>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-right text-nowrap">Qty Issued</th>
                                    <th class="text-right text-nowrap">Qty Recieved/ Return Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php                                 
                                foreach($issued_items as $issue): 
                                    ?>
                                    <tr>
                                        <td><?php echo $issue->description; ?><input class="hide" name="item_id[]" value="<?php echo $issue->item_id?>" /></td>
                                        <td class="text-right"><?php echo $issue->issued_qty; ?></td>
                                        <td class="text-right"><input type="number" class="form-control text-center pull-right" name="received_item_qty[]" style="width: 100px;" value="<?php echo $issue->issued_qty; ?>" /></td>
                                    </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($requisitions[0]->req_status!="Issued"){ ?>

                    <div class="panel-footer">
                            <div class="form-group col-lg-8" app-field-wrapper="location_title">
                                <input type="text" id="remarks" name="remarks" class="form-control"  required placeholder="Remarks">
                            </div>
                            <button type="submit" name="recieve" value="Received" class="btn btn-primary"><i class="fa fa-upload fa-rotate-90"></i> Receive</button>
                            <button type="submit" name="return" value="Returned" class="btn btn-primary"><i class="fa fa-upload fa-rotate-90"></i> Return</button>
                            <!-- <button type="submit" name="req_status" value="Rejected" class="btn btn-danger"><i class="fa fa-ban"></i> Reject</button> -->
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