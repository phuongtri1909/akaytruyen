<script src="{{ asset('assets/admin/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/core/app-menu.js') }}"></script>
<script src="{{ asset('assets/admin/js/core/app.js') }}"></script>

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('assets/admin/vendors/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/datepickerV2/daterangepicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/jquery/jquery.query-object.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>

@stack('scripts-page-vendor')

<script src="{{ mix('assets/admin/js/core/scripts.js') }}"></script>
<script src="{{ asset('assets/admin/js/ckeditor-helper.js') }}"></script>
<script defer>
    (function(window, document, $) {
        'use strict';
        // Basic Initialization
        let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    })(window, document, jQuery);

    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
