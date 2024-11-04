<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="horizontal-scrollable-tabs tw-bg-white tw-shadow-sm tw-rounded-lg tw-px-3 tw-min-h-0">
    <div class="scroller arrow-left !tw-py-[18px] tw-mt-px tw-border-0"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right !tw-py-[18px] tw-mt-px tw-border-0"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs tw-mb-0 project-tabs nav-tabs-horizontal tw-border-b-0" role="tablist">
            <li class="project_tab_project_overview tw-py-2"><a href="<?php echo admin_url('sub-contractor/boq/'.$sub_contractor_id);?>" class="tw-py-2"> 
                <i class="fa fa-list"></i> 
                <span class="pull-right mleft5">List BOQs </span></a>
            </li>
            <li class="project_tab_project_overview tw-py-2"><a href="<?php echo admin_url('sub-contractor/');?>" class="tw-py-2"> 
                <i class="fa fa-solid fa-chart-gantt menu-icon"></i> 
                <span class="pull-right mleft5">Go to Sub Contractor </span></a>
            </li>
            <li class="project_tab_project_overview tw-py-2"><a href="<?php echo admin_url('sub-contractor/import/'.$sub_contractor_id)?>" class="tw-py-2"> 
                <i class="fa-solid fa-upload menu-icon"></i> 
                <span class="pull-right mleft5">Import Erection BOQ </span></a>
            </li>
            
        </ul>
    </div>
</div>