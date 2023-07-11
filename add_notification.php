<?php include('session.php'); ?>
<?php $selectedMenu = "notification"; ?>
<?php include('templates/common/menubar.php'); ?>

<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">

    (function($,W,D) {
        var JQUERY4U = {};
        JQUERY4U.UTIL = {
            setupFormValidation: function() {
                //form validation rules
                $("#form-validation").validate({
                    rules: {
                        title    : "title",
                        message  : "required"
                    },

                    messages: {
                        title    : "Please fill out this field!",
                        message  : "Please fill out this field!"

                    },
                    errorElement : 'div',
                    submitHandler: function(form) {
                        form.submit();
                    }

                });
            }
        }

        //when the dom has loaded setup form validation rules
        $(D).ready(function($) {
            JQUERY4U.UTIL.setupFormValidation();
        });

    })(jQuery, window, document);

</script>
<?php include('templates/add_notification_form.php'); ?>
<?php include('templates/common/footer.php'); ?>