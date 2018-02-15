$(document).ready(function() {
    $('.userForm').each(function() {  // attach to all form elements on page
        $(this).validate({       // initialize plugin on each form
                rules: {
                    id: {
                        required: true,
                        pattern: /^[a-z0-9]{2,}$/
                    },
                    firstName: {
                        required: true,
                        minlength: 2,
                        lettersonly: true
                    },
                    lastName: {
                        required: true,
                        minlength: 2,
                        lettersonly: true
                    },
                    mail: {
                        required: true,
                        email: true
                    },
                    yearGroup: {
                        required: true,
                    }
                },
        });
    });
    $('.yearGroupForm').each(function() {  // attach to all form elements on page
        $(this).validate({       // initialize plugin on each form
                rules: {
                    id: {
                        required: true,
                        minlength: 2,
                    },
                    label: {
                        required: true,
                        minlength: 2,
                    }
                },
        });
    });
    $('.subGroupsForm').each(function() {  // attach to all form elements on page
        $(this).validate({       // initialize plugin on each form
                rules: {
                    id: {
                        required: true,
                        minlength: 2,
                        pattern: /^[a-zA-Z0-9]{2,}_[a-zA-Z0-9]{2,}$/,
                    },
                    label: {
                        required: true,
                        minlength: 2,
                    },
                    yearGroup: {
                        required: true,
                    }
                },
        });
    });
    $('.assignmentForm').each(function() {  // attach to all form elements on page
        $(this).validate({       // initialize plugin on each form
                rules: {
                    assignmentName: {
                        required: true,
                        pattern: /^[^\s]{2,}$/,
                    },
                    lesson: {
                        required: true,
                        minlength: 2,
                        pattern: /^[^\s]{2,}$/,
                    },
                    notebook: {
                        required: true,
                    },
                    yearGroup: {
                        required: true,
                    }
                },
        });
    });
});
