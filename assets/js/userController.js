$(document).ready(function () {
    function loadCaptcha() {
        $.ajax("inc/Data/captchaData", {
            method: 'POST',
            success:function (getResp) {
                if (getResp) {
                    $('#captcha').html(getResp);
                }
            }
        });
    }
    loadCaptcha();
    getTickets();
    getPlan();
    deadAttacks();

    function validEmail(email) {
        var string = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return string.test(email);
    }

    function deadAttacks() {
        $.ajax('inc/Data/runningAttacks', {
            method: "POST",
            success:function (getResp) {
                if(getResp) {
                    return console.log(getResp);
                }
            }
        });
    }

    function getPlan() {
        $.ajax('inc/Requests/accountRequest', {
            method: "POST",
            data: {
                check_plan: 1
            },
            success:function (getResp) {
                return console.log(getResp);
            }
        });
    }

    function getTickets() {
        $.ajax('inc/Data/ticketTableData',{
            method: "POST",
            success:function (getResp) {
                if (getResp) {
                   $('#tickets').html(getResp);
                }
            }
        });
    }

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $(document).on('click', '#login', function () {
        $('#login').attr('disabled', true);
        var username = $('#username').val();
        var password = $('#password').val();
        $.ajax({
            url: 'inc/Requests/accountRequest',
            data: {
                username: username,
                password: password,
                login: 1
            },
            method: 'POST',
            success: function (getResp) {
                setTimeout(function () {
                    $('#login').attr('disabled', false);
                }, 2500);
                if (getResp === "session_started") {
                    toastr["success"]("You've successfully logged in!", "MCSpam");
                    setInterval(function () {
                        window.location = "index.php";
                    }, 2500);
                    return true;
                } else if (getResp === "no_input") {
                    return toastr["error"]("Input fields were empty", "MCSpam");
                } else if (getResp === "account_disabled") {
                    return toastr['error']("This account has been disabled!", "MCSpam");
                }
                return toastr["error"]("Something went wrong while logging in, check your inputs!", "MCSpam");
            }
        })
    });

    $(document).on('click', '#register', function () {
        $('#register').attr('disabled', true);
        var username = $('#username').val();
        var password = $('#password').val();
        var repeat = $('#repeat-password').val();
        var email = $('#email').val();
        var captcha = $('#captcha').val();
        var answer = $('#captcha_answer').val();

        if (!validEmail(email)) {
            return toastr['error']("Something seems wrong with your email.", "MCSpam");
        }

        if (password === repeat) {
            $.ajax({
                url: 'inc/Requests/accountRequest',
                data: {
                    username: username,
                    password: password,
                    email: email,
                    captcha: answer,
                    register: 1
                },
                method: "POST",
                success: function (getResp) {
                    setTimeout(function () {
                        $('#register').attr('disabled', false);
                    }, 2500);
                    if (getResp === "account_created") {
                        toastr["success"]("Your account has been created, you may now log in", "MCSpam");
                        setInterval(function () {
                            window.location = "login.php";
                        }, 2500);
                        return true;
                    } else if (getResp === "no_input") {
                        return toastr["error"]("Input fields were empty", "MCSpam");
                    } else if (getResp === "invalid_captcha") {
                        return toastr['error']("Invalid captcha answer", "MCSpam");
                    }
                    return toastr["error"]("Something went wrong while registering, username may be taken!", "MCSpam")
                }
            })
        } else {
            setTimeout(function () {
                $('#register').attr('disabled', false);
            }, 2500);
            return toastr["error"]("Passwords do not match!", "MCspam");
        }
        loadCaptcha();
    });
    $(document).on('click', '#redeem', function () {
        var code = $('#code').val();
        if (code === "") {
            return toastr['error']('No code specified to redeem', "MCSpam");
        }

        $.ajax('inc/Requests/licenseRequest', {
            method: "POST",
            data: {
                code: code
            },
            success: function (getResp) {
                if (getResp === "code_redeemed") {
                    setTimeout(function () {
                        window.location = "index"
                    }, 2500);
                    return toastr['success']("Your plan was activated.", "MCSpam");
                }
                if (getResp === "invalid_license") {
                    return toastr['error']("Invalid license coded specified", "MCSpam");
                }
                return toastr['error']("Something went wrong while redeeming license", "MCSpam");
            }
        });
    });

    $(document).on('click', '#logout', function () {
        toastr["info"]("You're being logged out.", "MCSpam");
        setTimeout(function () {
            $.ajax({
                url: 'inc/Requests/accountRequest',
                data: {
                    logout: 1
                },
                method: "POST",
                success: function (getResp) {
                    if (getResp === "logged_out") {
                        window.location = "login";
                    } else {
                        return toastr["error"]("Something went wrong while logging out. is your session instantiated?", "MCSpam")
                    }
                }
            })
        }, 3000);
    });

    $(document).on('click', '#submit', function () {
        var subject = $('#subject').val();
        var priority = $('#priority').val();
        var department = $('#department').val();
        var message = $('#message').val();
        $('#submit').attr('disabled', true);

        if (subject === "" || priority === "" || department === "" || message === "Describe your problem here.") {
            setTimeout(function () {
                $('#submit').attr('disabled', false);
            }, 5000);
            return toastr['error']("Required input fields empty", "MCSpam");
        }

        $.ajax('inc/Requests/ticketRequest', {
            data: {
                add: 1,
                subject: subject,
                priority: priority,
                department: department,
                message: message
            },
            method: "POST",
            success:function (getResp) {
                if (getResp === "ticket_submitted"){
                    getTickets();
                    return toastr['success']("The ticket has been submitted");
                }
                if (getResp === "ticket_failed"){
                    return toastr['error']("Failed to create ticket. \n Exceeded max open ticket limit", "MCSpam");
                }
                return toastr['error']("Somethng went wrong while submitting ticket. Check network logs", "MCSpam")
            }
        });
        setTimeout(function () {
            $('#submit').attr('disabled', false);
        }, 5000);
    });

    $(document).on('click', '#read', function () {
       var id = $(this).data('id');
       if(id) {
           window.location = "view_ticket?id="+id
       }
    });

    $(document).on('click', '#start', function () {
       var host = $('#host').val();
       var type = $('#type').val();

       if (host && type) {
           $.ajax('inc/Requests/resolveRequest', {
              data: {
                  SRV: 1,
                  host: host
              },
              method: "POST",
              success:function (getResp) {
                  if (getResp === "not_resolved") {
                      return toastr['error']("Failed to retrieve SRV record.");
                  } else {
                      $('#output').html(getResp);
                      return toastr['success']("Successfully retrieved SRV record.", "MCSpam");
                  }
              }
           });
       } else {
           return toastr['error']("Required inputs are empty", "MCspam")
       }
    });

    var table = $('#table').DataTable({
        lengthChange: true,
        "bProcessing": true,
        "sAjaxSource": "inc/Data/loginTableData",
        "aoColumns": [
            {mData: 'username'},
            {mData: 'ip'}
        ],
    });
});
