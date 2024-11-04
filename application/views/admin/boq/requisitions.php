<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="project_locations">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($requisitions) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
            <div class="project-menu-panel tw-my-5">
                <?php $this->load->view('admin/boq/boq_tabs'); ?>
            </div>            
            <div class="panel_s">

                    <div class="panel-heading">
                        <h3>Item Requisitions <div class="label label-primary">Project: <?php echo $projects[0]->name?></div>
                    </h3>
                    </div>
                    <div class="panel-body">
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Requested At</th>
            <th class="text-center">Status</th>
            <th class="text-center">Type</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requisitions as $req) : ?>
            <tr>
                <td>#<?php echo sprintf("%04d",$req->req_id); ?></td>
                <td><?php echo $req->requested_at; ?></td>
                <td class="text-center"><span class="label label-<?php echo ($req->req_status=="Rejected") ? "danger" : (($req->req_status=="Issued") ? "success    " : "primary");?>"><?php echo $req->req_status; ?></span></td>
                <td class="text-center"><?php echo $req->req_type; ?></td>
                <td><?php echo $req->remarks; ?></td>
                <td class="text-nowrap">
                    <a class="btn btn-primary" href="<?php echo admin_url('boq/requisition/'.$req->boq_id .'/'. $req->req_id); ?>"><i class="fa fa-search"></i> View</a>
                    <a class="btn btn-primary" <?php echo ($req->req_status=="Rejected") ? "disabled" : (($req->req_status=="Issued") ? "info" : "disabled");?> href="<?php echo admin_url('boq/recieve-items/'.$req->boq_id .'/'. $req->req_id); ?>"><i class="fa fa-download fa-rotate-270"></i> Receive </a>
                    <a class="btn btn-primary" <?php echo ($req->req_status=="Rejected") ? "disabled" : (($req->req_status=="Received") ? "info" : "disabled");?> href="<?php echo admin_url('boq/recieve-items/'.$req->boq_id .'/'. $req->req_id); ?>?type=return"><i class="fa fa-redo"></i> Return </a>
                    <a class="btn btn-danger" href="<?php echo admin_url('boq/requisition/'.$req->boq_id .'/'. $req->req_id); ?>"><i class="fa fa-file-pdf"></i></a>
                </td>
            </tr>
        <?php endforeach;?>
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
