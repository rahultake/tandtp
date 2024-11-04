<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>

<!DOCTYPE html>
<html lang="<?php echo $locale; ?>" dir="<?php echo ($isRTL == 'true') ? 'rtl' : 'ltr' ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?php echo isset($title) ? $title : get_option('companyname'); ?></title>

    <?php echo app_compile_css(); ?>
    <style>
.label {
    border-radius: 0;
    text-shadow: none;
    font-weight: 400;
    color: #fff;
    display: inline-block;
    background-color: #abbac3;
    min-width:100px
}
.label[class*="col-"][class*="arrow"] {
    min-height: 0;
}

.label.label-transparent,
.label-transparent,
.badge.badge-transparent,
.badge-transparent {
    background-color: transparent;
}
.label-grey,
.label.label-grey,
.badge.badge-grey,
.badge-grey {
    background-color: #a0a0a0;
}
.label-info,
.label.label-info,
.badge.badge-info,
.badge-info {
    background-color: #3a87ad;
}
.label-primary,
.label.label-primary,
.badge.badge-primary,
.badge-primary {
    background-color: #428bca;
}
.label-success,
.label.label-success,
.badge.badge-success,
.badge-success {
    background-color: #82af6f;
}
.label-danger,
.label.label-danger,
.badge.badge-danger,
.badge-danger {
    background-color: #d15b47;
}
.label-important,
.label.label-important,
.badge.badge-important,
.badge-important {
    background-color: #d15b47;
}
.label-inverse,
.label.label-inverse,
.badge.badge-inverse,
.badge-inverse {
    background-color: #333;
}
.label-warning,
.label.label-warning,
.badge.badge-warning,
.badge-warning {
    background-color: #f89406;
}
.label-pink,
.label.label-pink,
.badge.badge-pink,
.badge-pink {
    background-color: #d6487e;
}
.label-purple,
.label.label-purple,
.badge.badge-purple,
.badge-purple {
    background-color: #9585bf;
}
.label-yellow,
.label.label-yellow,
.badge.badge-yellow,
.badge-yellow {
    background-color: #fee188;
}
.label-light,
.label.label-light,
.badge.badge-light,
.badge-light {
    background-color: #e7e7e7;
}
.badge-yellow,
.label-yellow {
    color: #963;
    border-color: #fee188;
}
.badge-light,
.label-light {
    color: #888;
}    .label.arrowed,
.label.arrowed-in {
    position: relative;
    z-index: 1;
}
.label.arrowed:before,
.label.arrowed-in:before {
    display: inline-block;
    content: "";
    position: absolute;
    top: 0;
    z-index: -1;
    border: 1px solid transparent;
    border-right-color: #abbac3;
    -moz-border-right-colors: #abbac3;
}
.label.arrowed-in:before {
    border-color: #abbac3;
    border-left-color: transparent;
    -moz-border-left-colors: none;
}
.label.arrowed-right,
.label.arrowed-in-right {
    position: relative;
    z-index: 1;
}
.label.arrowed-right:after,
.label.arrowed-in-right:after {
    display: inline-block;
    content: "";
    position: absolute;
    top: 0;
    z-index: -1;
    border: 1px solid transparent;
    border-left-color: #abbac3;
    -moz-border-left-colors: #abbac3;
}
.label.arrowed-in-right:after {
    border-color: #abbac3;
    border-right-color: transparent;
    -moz-border-right-colors: none;
}
.label-info.arrowed:before {
    border-right-color: #3a87ad;
    -moz-border-right-colors: #3a87ad;
}
.label-info.arrowed-in:before {
    border-color: #3a87ad #3a87ad #3a87ad transparent;
    -moz-border-right-colors: #3a87ad;
}
.label-info.arrowed-right:after {
    border-left-color: #3a87ad;
    -moz-border-left-colors: #3a87ad;
}
.label-info.arrowed-in-right:after {
    border-color: #3a87ad transparent #3a87ad #3a87ad;
    -moz-border-left-colors: #3a87ad;
}
.label-primary.arrowed:before {
    border-right-color: #428bca;
    -moz-border-right-colors: #428bca;
}
.label-primary.arrowed-in:before {
    border-color: #428bca #428bca #428bca transparent;
    -moz-border-right-colors: #428bca;
}
.label-primary.arrowed-right:after {
    border-left-color: #428bca;
    -moz-border-left-colors: #428bca;
}
.label-primary.arrowed-in-right:after {
    border-color: #428bca transparent #428bca #428bca;
    -moz-border-left-colors: #428bca;
}
.label-success.arrowed:before {
    border-right-color: #82af6f;
    -moz-border-right-colors: #82af6f;
}
.label-success.arrowed-in:before {
    border-color: #82af6f #82af6f #82af6f transparent;
    -moz-border-right-colors: #82af6f;
}
.label-success.arrowed-right:after {
    border-left-color: #82af6f;
    -moz-border-left-colors: #82af6f;
}
.label-success.arrowed-in-right:after {
    border-color: #82af6f transparent #82af6f #82af6f;
    -moz-border-left-colors: #82af6f;
}
.label-warning.arrowed:before {
    border-right-color: #f89406;
    -moz-border-right-colors: #f89406;
}
.label-warning.arrowed-in:before {
    border-color: #f89406 #f89406 #f89406 transparent;
    -moz-border-right-colors: #f89406;
}
.label-warning.arrowed-right:after {
    border-left-color: #f89406;
    -moz-border-left-colors: #f89406;
}
.label-warning.arrowed-in-right:after {
    border-color: #f89406 transparent #f89406 #f89406;
    -moz-border-left-colors: #f89406;
}
.label-important.arrowed:before {
    border-right-color: #d15b47;
    -moz-border-right-colors: #d15b47;
}
.label-important.arrowed-in:before {
    border-color: #d15b47 #d15b47 #d15b47 transparent;
    -moz-border-right-colors: #d15b47;
}
.label-important.arrowed-right:after {
    border-left-color: #d15b47;
    -moz-border-left-colors: #d15b47;
}
.label-important.arrowed-in-right:after {
    border-color: #d15b47 transparent #d15b47 #d15b47;
    -moz-border-left-colors: #d15b47;
}
.label-danger.arrowed:before {
    border-right-color: #d15b47;
    -moz-border-right-colors: #d15b47;
}
.label-danger.arrowed-in:before {
    border-color: #d15b47 #d15b47 #d15b47 transparent;
    -moz-border-right-colors: #d15b47;
}
.label-danger.arrowed-right:after {
    border-left-color: #d15b47;
    -moz-border-left-colors: #d15b47;
}
.label-danger.arrowed-in-right:after {
    border-color: #d15b47 transparent #d15b47 #d15b47;
    -moz-border-left-colors: #d15b47;
}
.label-inverse.arrowed:before {
    border-right-color: #333;
    -moz-border-right-colors: #333;
}
.label-inverse.arrowed-in:before {
    border-color: #333 #333 #333 transparent;
    -moz-border-right-colors: #333;
}
.label-inverse.arrowed-right:after {
    border-left-color: #333;
    -moz-border-left-colors: #333;
}
.label-inverse.arrowed-in-right:after {
    border-color: #333 transparent #333 #333;
    -moz-border-left-colors: #333;
}
.label-pink.arrowed:before {
    border-right-color: #d6487e;
    -moz-border-right-colors: #d6487e;
}
.label-pink.arrowed-in:before {
    border-color: #d6487e #d6487e #d6487e transparent;
    -moz-border-right-colors: #d6487e;
}
.label-pink.arrowed-right:after {
    border-left-color: #d6487e;
    -moz-border-left-colors: #d6487e;
}
.label-pink.arrowed-in-right:after {
    border-color: #d6487e transparent #d6487e #d6487e;
    -moz-border-left-colors: #d6487e;
}
.label-purple.arrowed:before {
    border-right-color: #9585bf;
    -moz-border-right-colors: #9585bf;
}
.label-purple.arrowed-in:before {
    border-color: #9585bf #9585bf #9585bf transparent;
    -moz-border-right-colors: #9585bf;
}
.label-purple.arrowed-right:after {
    border-left-color: #9585bf;
    -moz-border-left-colors: #9585bf;
}
.label-purple.arrowed-in-right:after {
    border-color: #9585bf transparent #9585bf #9585bf;
    -moz-border-left-colors: #9585bf;
}
.label-yellow.arrowed:before {
    border-right-color: #fee188;
    -moz-border-right-colors: #fee188;
}
.label-yellow.arrowed-in:before {
    border-color: #fee188 #fee188 #fee188 transparent;
    -moz-border-right-colors: #fee188;
}
.label-yellow.arrowed-right:after {
    border-left-color: #fee188;
    -moz-border-left-colors: #fee188;
}
.label-yellow.arrowed-in-right:after {
    border-color: #fee188 transparent #fee188 #fee188;
    -moz-border-left-colors: #fee188;
}
.label-light.arrowed:before {
    border-right-color: #e7e7e7;
    -moz-border-right-colors: #e7e7e7;
}
.label-light.arrowed-in:before {
    border-color: #e7e7e7 #e7e7e7 #e7e7e7 transparent;
    -moz-border-right-colors: #e7e7e7;
}
.label-light.arrowed-right:after {
    border-left-color: #e7e7e7;
    -moz-border-left-colors: #e7e7e7;
}
.label-light.arrowed-in-right:after {
    border-color: #e7e7e7 transparent #e7e7e7 #e7e7e7;
    -moz-border-left-colors: #e7e7e7;
}
.label-grey.arrowed:before {
    border-right-color: #a0a0a0;
    -moz-border-right-colors: #a0a0a0;
}
.label-grey.arrowed-in:before {
    border-color: #a0a0a0 #a0a0a0 #a0a0a0 transparent;
    -moz-border-right-colors: #a0a0a0;
}
.label-grey.arrowed-right:after {
    border-left-color: #a0a0a0;
    -moz-border-left-colors: #a0a0a0;
}
.label-grey.arrowed-in-right:after {
    border-color: #a0a0a0 transparent #a0a0a0 #a0a0a0;
    -moz-border-left-colors: #a0a0a0;
}
.label.arrowed {
    margin-left: 5px;
}
.label.arrowed:before {
    left: -10px;
    border-width: 10px 5px;
}
.label.arrowed-in {
    margin-left: 5px;
}
.label.arrowed-in:before {
    left: -5px;
    border-width: 10px 5px;
}
.label.arrowed-right {
    margin-right: 5px;
}
.label.arrowed-right:after {
    right: -10px;
    border-width: 10px 5px;
}
.label.arrowed-in-right {
    margin-right: 5px;
}
.label.arrowed-in-right:after {
    right: -5px;
    border-width: 10px 5px;
}
.label-lg.arrowed {
    margin-left: 6px;
}
.label-lg.arrowed:before {
    left: -12px;
    border-width: 12px 6px;
}
.label-lg.arrowed-in {
    margin-left: 6px;
}
.label-lg.arrowed-in:before {
    left: -6px;
    border-width: 12px 6px;
}
.label-lg.arrowed-right {
    margin-right: 6px;
}
.label-lg.arrowed-right:after {
    right: -12px;
    border-width: 12px 6px;
}
.label-lg.arrowed-in-right {
    margin-right: 6px;
}
.label-lg.arrowed-in-right:after {
    right: -6px;
    border-width: 12px 6px;
}
.label-xlg {
    padding: 0.3em 0.7em 0.4em;
    font-size: 14px;
    line-height: 1.3;
    height: 28px;
}
.label-xlg.arrowed {
    margin-left: 7px;
}
.label-xlg.arrowed:before {
    left: -14px;
    border-width: 14px 7px;
}
.label-xlg.arrowed-in {
    margin-left: 7px;
}
.label-xlg.arrowed-in:before {
    left: -7px;
    border-width: 13.5px 7px;
}
.label-xlg.arrowed-right {
    margin-right: 7px;
}
.label-xlg.arrowed-right:after {
    right: -14px;
    border-width: 13px 7px;
}
.label-xlg.arrowed-in-right {
    margin-right: 7px;
}
.label-xlg.arrowed-in-right:after {
    right: -7px;
    border-width: 14px 7px;
}
.label-sm {
    padding: 0.2em 0.4em 0.3em;
    font-size: 11px;
    line-height: 1;
    height: 18px;
}
.label-sm.arrowed {
    margin-left: 4px;
}
.label-sm.arrowed:before {
    left: -8px;
    border-width: 9px 4px;
}
.label-sm.arrowed-in {
    margin-left: 4px;
}
.label-sm.arrowed-in:before {
    left: -4px;
    border-width: 9px 4px;
}
.label-sm.arrowed-right {
    margin-right: 4px;
}
.label-sm.arrowed-right:after {
    right: -8px;
    border-width: 9px 4px;
}
.label-sm.arrowed-in-right {
    margin-right: 4px;
}
.label-sm.arrowed-in-right:after {
    right: -4px;
    border-width: 9px 4px;
}
.label > span,
.label > .ace-icon {
    line-height: 1;
    vertical-align: bottom;
}
.label.label-white {
    color: #879da9;
    border: 1px solid #abbac3;
    background-color: #f2f5f6;
    border-right-width: 1px;
    border-left-width: 2px;
}
.label-white.label-success {
    color: #7b9e6c;
    border-color: #9fbf92;
    background-color: #edf3ea;
}
.label-white.label-warning {
    color: #d9993e;
    border-color: #e4ae62;
    background-color: #fef6eb;
}
.label-white.label-primary {
    color: #6688a6;
    border-color: #8aafce;
    background-color: #eaf2f8;
}
.label-white.label-danger {
    color: #bd7f75;
    border-color: #d28679;
    background-color: #fcf4f2;
}
.label-white.label-info {
    color: #4e7a8f;
    border-color: #7aa1b4;
    background-color: #eaf3f7;
}
.label-white.label-inverse {
    color: #404040;
    border-color: #737373;
    background-color: #ededed;
}
.label-white.label-pink {
    color: #af6f87;
    border-color: #d299ae;
    background-color: #fbeff4;
}
.label-white.label-purple {
    color: #7d6fa2;
    border-color: #b7b1c6;
    background-color: #efedf5;
}
.label-white.label-yellow {
    color: #cfa114;
    border-color: #ecd181;
    background-color: #fdf7e4;
}
.label-white.label-grey {
    color: #878787;
    border-color: #cecece;
    background-color: #ededed;
}
@media screen and (-webkit-min-device-pixel-ratio: 1.08) and (-webkit-max-device-pixel-ratio: 1.15), screen and (min--moz-device-pixel-ratio: 1.08) and (max--moz-device-pixel-ratio: 1.15) {
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed:before {
        border-width: 10.5px 6px;
        left: -11px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-right:after {
        border-width: 10.5px 6px;
        right: -11px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-in:before {
        border-width: 10.5px 5px 10px;
        left: -6px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-in-right:after {
        border-width: 10.5px 5px 10px;
        right: -6px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.3) and (-webkit-max-device-pixel-ratio: 1.4), screen and (min--moz-device-pixel-ratio: 1.3) and (max--moz-device-pixel-ratio: 1.4) {
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed:before {
        border-width: 10px 6px 10px;
        left: -12px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-right:after {
        border-width: 10px 6px 10px;
        right: -12px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-in:before {
        border-width: 10px 5px 10px;
        left: -6px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-in-right:after {
        border-width: 10px 5px 10px;
        right: -6px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.5) and (-webkit-max-device-pixel-ratio: 1.6), screen and (min--moz-device-pixel-ratio: 1.5) and (max--moz-device-pixel-ratio: 1.6) {
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed:before {
        border-width: 10px 6px;
        left: -12px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-right:after {
        border-width: 10px 6px;
        right: -12px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.7) and (-webkit-max-device-pixel-ratio: 1.8), screen and (min--moz-device-pixel-ratio: 1.7) and (max--moz-device-pixel-ratio: 1.8) {
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed:before {
        border-width: 10px 6px;
        left: -11.5px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-right:after {
        border-width: 10px 6px;
        right: -11.5px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-in:before {
        border-width: 10px 5px;
        left: -6px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-in-right:after {
        border-width: 10px 5px;
        right: -6px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 0.8) and (-webkit-max-device-pixel-ratio: 0.9), screen and (min--moz-device-pixel-ratio: 0.8) and (max--moz-device-pixel-ratio: 0.9) {
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed:before {
        border-width: 11px 6px;
        left: -11.5px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-right:after {
        border-width: 11px 6px;
        right: -11.5px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-in:before {
        border-width: 11px 5px;
        left: -6px;
    }
    .label:not(.label-lg):not(.label-xlg):not(.label-sm).arrowed-in-right:after {
        border-width: 11px 5px;
        right: -6px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.08) and (-webkit-max-device-pixel-ratio: 1.15), screen and (min--moz-device-pixel-ratio: 1.08) and (max--moz-device-pixel-ratio: 1.15) {
    .label-lg.arrowed:before {
        left: -11px;
    }
    .label-lg.arrowed-right:after {
        right: -11px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.7) and (-webkit-max-device-pixel-ratio: 1.8), screen and (min--moz-device-pixel-ratio: 1.7) and (max--moz-device-pixel-ratio: 1.8) {
    .label-lg.arrowed:before {
        left: -11.5px;
    }
    .label-lg.arrowed-right:after {
        right: -11.5px;
    }
    .label-lg.arrowed-in:before {
        border-width: 12.5px 6px 12px;
        left: -6px;
    }
    .label-lg.arrowed-in-right:after {
        border-width: 12.5px 6px 12px;
        right: -6px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.08) and (-webkit-max-device-pixel-ratio: 1.15), screen and (min--moz-device-pixel-ratio: 1.08) and (max--moz-device-pixel-ratio: 1.15) {
    .label-xlg.arrowed:before {
        left: -13px;
    }
    .label-xlg.arrowed-right:after {
        right: -13px;
    }
    .label-xlg.arrowed-in:before {
        border-width: 14px 7px 14.5px;
    }
    .label-xlg.arrowed-in-right:after {
        border-width: 14px 7px 14.5px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.2) and (-webkit-max-device-pixel-ratio: 1.3), screen and (min--moz-device-pixel-ratio: 1.2) and (max--moz-device-pixel-ratio: 1.3) {
    .label-xlg.arrowed:before {
        border-width: 14.5px 7px;
        left: -13.5px;
    }
    .label-xlg.arrowed-right:after {
        border-width: 14.5px 7px;
        right: -13.5px;
    }
    .label-xlg.arrowed-in:before {
        border-width: 14.5px 7px 14.5px;
    }
    .label-xlg.arrowed-in-right:after {
        border-width: 14.5px 7px 14.5px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.3) and (-webkit-max-device-pixel-ratio: 1.4),
    screen and (-webkit-min-device-pixel-ratio: 1.5) and (-webkit-max-device-pixel-ratio: 1.6),
    screen and (min--moz-device-pixel-ratio: 1.3) and (max--moz-device-pixel-ratio: 1.4),
    screen and (min--moz-device-pixel-ratio: 1.5) and (max--moz-device-pixel-ratio: 1.6) {
    .label-xlg.arrowed:before {
        border-width: 14.5px 7.5px;
        left: -14.5px;
    }
    .label-xlg.arrowed-right:after {
        border-width: 14.5px 7.5px;
        right: -14.5px;
    }
    .label-xlg.arrowed-in:before {
        border-width: 14.5px 7px;
    }
    .label-xlg.arrowed-in-right:after {
        border-width: 14.5px 7px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.08) and (-webkit-max-device-pixel-ratio: 1.15), screen and (min--moz-device-pixel-ratio: 1.08) and (max--moz-device-pixel-ratio: 1.15) {
    .label-sm.arrowed:before {
        border-width: 9px 5px;
        left: -9px;
    }
    .label-sm.arrowed-right:after {
        border-width: 9px 5px;
        right: -9px;
    }
    .label-sm.arrowed-in:before {
        border-width: 10px 4px;
    }
    .label-sm.arrowed-in-right:after {
        border-width: 10px 4px;
    }
}
@media screen and (-webkit-min-device-pixel-ratio: 1.2) and (-webkit-max-device-pixel-ratio: 1.3), screen and (min--moz-device-pixel-ratio: 1.2) and (max--moz-device-pixel-ratio: 1.3) {
    .label-sm.arrowed:before {
        border-width: 9.5px 5px;
        left: -10px;
    }
    .label-sm.arrowed-right:after {
        border-width: 9.5px 5px;
        right: -10px;
    }
    .label-sm.arrowed-in:before {
        border-width: 9.5px 4px;
    }
    .label-sm.arrowed-in-right:after {
        border-width: 9.5px 4px;
    }
}
</style>
    <?php render_admin_js_variables(); ?>

    <script>
    var totalUnreadNotifications = <?php echo $current_user->total_unread_notifications; ?>,
        proposalsTemplates = <?php echo json_encode(get_proposal_templates()); ?>,
        contractsTemplates = <?php echo json_encode(get_contract_templates()); ?>,
        billingAndShippingFields = ['billing_street', 'billing_city', 'billing_state', 'billing_zip', 'billing_country',
            'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'
        ],
        isRTL = '<?php echo $isRTL; ?>',
        taskid, taskTrackingStatsData, taskAttachmentDropzone, taskCommentAttachmentDropzone, newsFeedDropzone,
        expensePreviewDropzone, taskTrackingChart, cfh_popover_templates = {},
        _table_api;
    </script>
    <?php app_admin_head(); ?>
</head>

<body <?php echo admin_body_class(isset($bodyclass) ? $bodyclass : ''); ?>>
    <?php hooks()->do_action('after_body_start'); ?>