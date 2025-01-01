<?php

if (!function_exists('notification')) {
    function notification($ntid, $heading, $body, $dismiss = '') {
        $closeMSG = __('messages.Close');
        $dismissMSG = __('messages.Dismiss');
        $dismissBtn = '';

        if ($dismiss) {
            $dismissBtn = '<a href="' . url()->current() . '?dismiss=' . $dismiss . '" class="btn btn-danger">'.$dismissMSG.'</a>';
        }

        return <<<MODAL
        <div class="modal fade" id="{$ntid}" data-bs-backdrop="true" data-bs-keyboard="false" tabindex="-1" aria-labelledby="{$ntid}-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="{$ntid}-label">$heading</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="bd-example">
                            $body
                        </div>
                    </div>
                    <div class="modal-footer">
                        $dismissBtn
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">$closeMSG</button>
                    </div>
                </div>
            </div>
        </div>
        MODAL;
    }
}

if (!function_exists('notificationCard')) {
    function notificationCard($ntid, $icon, $heading, $subheading) {
        return "<a data-bs-target=\"#{$ntid}\" data-bs-toggle=\"modal\" style=\"cursor:pointer!important;\" class=\"iq-sub-card\">
                <div class=\"d-flex align-items-center\">
                    <i class=\"{$icon} p-1 avatar-40 rounded-pill bg-soft-primary d-flex justify-content-center align-items-center\"></i>
                    <div class=\"ms-3 w-100\">
                        <h6 class=\"mb-0 \">{$heading}</h6>
                        <div class=\"d-flex justify-content-between align-items-center\">
                            <p class=\"mb-0\">{$subheading}</p>
                        </div>
                    </div>
                </div>
            </a>";
    }
}
