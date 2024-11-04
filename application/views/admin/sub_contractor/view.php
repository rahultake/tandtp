<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="boqs">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($boqs) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/sub_contractor/boq_tabs'); ?>
                </div>
                <div class="panel_s">
                <?php echo form_open('admin/boq/update-status/'.$boqs[0]->id); ?>
                    <div class="panel-heading">
                        <h3>BOQ Details 
                            <div class="label label-primary">Sub Contractor: <?php echo $sub_contractor[0]->sub_contractor_name?></div> <div class="label label-<?php echo $status_class;?>">BOQ Status: <?php echo $boqs[0]->boq_status?></div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            
                            <table>
                                <tr>
                                    <td>
                                    <strong class="col-lg-12"><?php echo $sub_contractor[0]->sub_contractor_name?></strong>

                                        <strong class="col-lg-12"><?php echo $sub_contractor[0]->sub_contractor_description?></strong>                                        
                                    </td>
                                    <td>
                                        <strong class="col-lg-12">BOQ Title: <?php echo $boqs[0]->boq_title;?></strong>
                                        <span class="col-lg-12 text-small"><?php echo $boqs[0]->remarks; ?></span>
                                        <span class="col-lg-12 text-small">BOQ Status: <?php echo $boqs[0]->boq_status?></span>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                        <div class="row">
                            
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                    <table class="table table-striped table-hover" id="boqTable">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Product Description</th>
                                <!-- <th>Location</th> -->
                                <th class="text-right">Qty</th>
                                <th class="text-right">Units</th>
                                <th class="text-right">Rate</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            ini_set("display_errors",1);
                            $total_qty = 0;
                            $total_amount = 0;
                            $totaltaxes_amount = 0;
                            $i=1;
                            foreach($boq_items as $boq_item):
                                $total_qty += $boq_item->sub_contractor_qty;
                                $total_amount += $boq_item->sub_contractor_price * $boq_item->sub_contractor_qty;                                
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><span class="text-dark"><?php echo $boq_item->description; ?></span><small class="clearfix text-primary"><?php echo nl2br(htmlspecialchars_decode($boq_item->long_description)); ?></small></td>                            
                                    <td class="text-right"><?php echo $boq_item->sub_contractor_qty; ?></td>
                                    <td class="text-right"><?php echo $boq_item->unit; ?></td>
                                    <td class="text-right"><?php echo number_format($boq_item->sub_contractor_price, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($boq_item->sub_contractor_price * $boq_item->sub_contractor_qty, 2); ?></td>
                                </tr>
                            <?php $i++; endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th></th>
                                <th class="text-right"><?php echo $total_qty; ?></th>
                                <th></th>
                                <th></th>
                                <th class="text-right"><?php echo number_format($total_amount, 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="panel-footer">
                    <button id="exportButton" class="btn-sm btn btn-success">Export to CSV</button>
                    <a class="btn-sm btn btn-warning" href="<?php echo admin_url('sub-contractor/edit_boq/' . $sub_contractor_id."/".$boqs[0]->id); ?>" title="Edit BOQ" ><i class="fas fa-pencil"></i> Edit</a>
                    <a class="btn-sm btn btn-danger" href="<?php echo admin_url('sub-contractor/delete_boq/' . $sub_contractor_id."/".$boqs[0]->id); ?>" title="Delete BOQ" ><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<!-- Add JavaScript for Exporting Table Data to CSV -->
<script>
document.getElementById('exportButton').addEventListener('click', function() {
    var table = document.getElementById("boqTable");
    var rows = table.querySelectorAll("tr");
    var csv = [];

    // Loop over table rows and build CSV
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length; j++) {
            // Clean up text and remove unwanted characters
            var text = cols[j].innerText.replace(/,/g, ''); // remove commas from text to avoid CSV issues
            row.push('"' + text + '"');
        }

        csv.push(row.join(",")); // Join columns into a CSV row
    }

    // Create a Blob from CSV data
    var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });

    // Create a download link
    var downloadLink = document.createElement("a");
    downloadLink.download = "boq_data.csv";
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";

    // Append the link to the document and trigger the download
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
});
</script>
</body>
</html>