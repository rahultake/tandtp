<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="project_locations">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($boqs) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/boq/boq_tabs'); ?>
                </div>
                <div class="panel_s">
                    <div class="panel-heading">
                        <h3>Bills of Quantity 
                            <a href="../../admin/projects/view/<?php echo $projects[0]->id?>" class="label label-primary">Project: <?php echo $projects[0]->name?></a> 
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!-- Add Export Button -->
                        <button id="exportButton" class="btn btn-success">Export to CSV</button>
                        <table class="table table-striped table-hover" id="boqTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>BOQ Title</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($boqs as $boq) : ?>
                                    <tr>
                                        <td>#<?php echo sprintf("%04d",$boq->boq_id); ?></td>
                                        <td><?php echo $boq->boq_title; ?></td>
                                        <td><span class="label label-<?php echo ($boq->boq_status=="Rejected") ? "danger" : (($boq->boq_status=="Approved") ? "success" : "primary");?>"><?php echo ($boq->boq_status=="Approved") ? "<i class='fa fa-check-circle'></i>": "";?> <?php echo $boq->boq_status; ?></span></td>
                                        <td><?php echo $boq->created_at; ?></td>
                                        <td class="text-nowrap">
                                            <a class="btn-sm btn btn-primary" href="<?php echo admin_url('boq/view/' . $boq->boq_id); ?>" title="View BOQ"><i class="fa fa-eye"></i></a>
                                            <!-- <a class="btn-sm btn btn-info" href="<?php echo admin_url('boq/add/' . $project_id."/".$boq->boq_id); ?>" title="Copy BOQ" ><i class="far fa-copy"></i> Supply</a> -->
                                            <!-- <a class="btn-sm btn btn-info <?php echo ($boq->boq_status=="Approved")?"":"disabled";?>" href="<?php echo admin_url('boq/item-request/' .$boq->boq_id); ?>"><i class="fa fa-arrow-right"></i> Request Items</a>
                                            <a class="btn-sm btn btn-warning <?php echo ($boq->boq_status=="Approved")?"":"disabled";?>" href="<?php echo admin_url('boq/requisitions/' .$boq->boq_id); ?>"><i class="fa fa-list"></i> Requisitions</a> -->
                                            <!-- <a class="btn-sm btn btn-success <?php echo ($boq->boq_status=="Approved")?"":"disabled";?>" href="<?php echo admin_url('boq/report/' .$boq->boq_id); ?>"><i class="fa fa-line-chart"></i> Report</a> -->
                                            <!-- <a class="btn btn-primary" href="<?php echo admin_url('project-locations/edit/' . $project_id . '/' . $location->loc_id); ?>"><i class="fa fa-pencil"></i> Edit</a> -->
                                            <!-- <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this location?');" href="<?php echo admin_url('project-locations/delete/' . $project_id . '/' . $location->loc_id); ?>"><i class="fa fa-trash"></i> Delete</a> -->
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
