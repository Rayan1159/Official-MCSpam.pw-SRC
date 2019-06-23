$(document).ready(async () => {
    let loadCaptcha = async () => {
        let ajax = await $.ajax("inc/Data/captchaData", {
            method: 'POST',
            success: (getResp) => {
                if (getResp) {
                    $('#captcha').html(getResp);
                }
            }
        });
        return ajax;
    }
    loadCaptcha();
    getTickets();
    getPlan();
    deadAttacks();

    let validEmail = (email) => {
        var string = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return string.test(email);
    }

    let deadAttacks = async () => {
        let ajax = await $.ajax('inc/Data/runningAttacks', {
            method: "POST",
            success: (getResp) => {
                if(getResp) {
                    return console.log(getResp);
                }
            }
        });
        return ajax;
    }

    let getPlan = async () => {
        let ajax = await $.ajax('inc/Requests/accountRequest', {
            method: "POST",
            data: {
                check_plan: 1
            },
            success: (getResp) => {
                return console.log(getResp);
            }
        });
        return ajax;
    }

    let getTickets = () => {
        let ajax = await $.ajax('inc/Data/ticketTableData',{
            method: "POST",
            success:(getResp) => {
                if (getResp) {
                   return $('#tickets').html(getResp);
                }
            }
        });
        return ajax;
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

    $(document).on('click', '#login', async () => {
        $('#login').attr('disabled', true);
        let username = $('#username').val();
        let password = $('#password').val();
        let ajax = await $.ajax('inc/Requests/accountRequest', {
            data: {
                username: username,
                password: password,
                login: 1
            },
            method: 'POST',
            success: async (getResp) => {
                setTimeout(() => {
                    $('#login').attr('disabled', false);
                }, 2500);
                if (getResp === "session_started") {
                    toastr["success"]("You've successfully logged in!", "MCSpam");
                    return setInterval(() => {
                        window.location = "index.php";
                    }, 2500);
                } 
                if (getResp === "no_input") {
                    return toastr["error"]("Input fields were empty", "MCSpam");
                }
                if (getResp === "account_disabled") {
                    
                }
                return toastr["error"]("Something went wrong while logging in, check your inputs!", "MCSpam");
            }
        })
        return ajax;
    });

    $(document).on('click', '#register', async () => {
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
           loadCaptcha();
           let ajax = await $.ajax('inc/Requests/accountRequest', {
                data: {
                    username: username,
                    password: password,
                    email: email,
                    captcha: answer,
                    register: 1
                },
                method: "POST",
                success: (getResp) => {
                    setTimeout(() => {
                        $('#register').attr('disabled', false);
                    }, 2500);
                    if (getResp === "account_created") {
                        toastr["success"]("Your account has been created, you may now log in", "MCSpam");
                        return setInterval(() => {
                            window.location = "login.php";
                        }, 2500);
                    } 
                    if (getResp === "no_input") {
                        return toastr["error"]("Input fields were empty", "MCSpam");
                    }
                    if (getResp === "invalid_captcha") {
                        return toastr['error']("Invalid captcha answer", "MCspam");
                    }
                    return toastr["error"]("Something went wrong while registering, username may be taken!", "MCSpam")
                }
            })
            return ajax;
        }
        setTimeout(() => {
            $('#register').attr('disabled', false);
        }, 2500);
        return toastr["error"]("Passwords do not match!", "MCspam");
    });
    $(document).on('click', '#redeem', async () => {
        var code = $('#code').val();
        if (!code) {
            return toastr['error']('No code specified to redeem', "MCSpam");
        }

        let ajax = await $.ajax('inc/Requests/licenseRequest', {
            method: "POST",
            data: {
                code: code
            },
            success: (getResp) => {
                if (getResp === "code_redeemed") {
                    setTimeout(() => {
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
        return ajax;
    });

    $(document).on('click', '#logout', async () => {
        toastr["info"]("You're being logged out.", "MCSpam");
        setTimeout(() => {
            let ajax = await $.ajax({
                url: 'inc/Requests/accountRequest',
                data: {
                    logout: 1
                },
                method: "POST",
                success: (getResp) => {
                    if (getResp === "logged_out") {
                        return window.location = "login";
                    }
                    return toastr["error"]("Something went wrong while logging out. is your session instantiated?", "MCSpam")
                }
            })
            return ajax;
        }, 3000);
    });

    $(document).on('click', '#submit', () => {
        var subject = $('#subject').val();
        var priority = $('#priority').val();
        var department = $('#department').val();
        var message = $('#message').val();
        $('#submit').attr('disabled', true);

        if (subject === "" || priority === "" || department === "" || message === "Describe your problem here.") {
            setTimeout(() => {
                $('#submit').attr('disabled', false);
            }, 5000);
            return toastr['error']("Required input fields empty", "MCSpam");
        }

        let ajax = await $.ajax('inc/Requests/ticketRequest', {
            data: {
                add: 1,
                subject: subject,
                priority: priority,
                department: department,
                message: message
            },
            method: "POST",
            success: (getResp) => {
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
        setTimeout(() => {
            $('#submit').attr('disabled', false);
        }, 5000);
        return ajax;
    });

    $(document).on('click', '#read', () => {
       if($(this).data('id')) {
           return window.location = "view_ticket?id="+$(this).data('id');
       }
       return false;
    });

    $(document).on('click', '#start', async () => {
       var host = $('#host').val();
       var type = $('#type').val();

       if (host && type) {
          let ajax = await $.ajax('inc/Requests/resolveRequest', {
              data: {
                  SRV: 1,
                  host: host
              },
              method: "POST",
              success: (getResp) => {
                  if (getResp === "not_resolved") {
                      return toastr['error']("Failed to retrieve SRV record.");
                  } else {
                      $('#output').html(getResp);
                      return toastr['success']("Successfully retrieved SRV record.", "MCSpam");
                  }
              }
           });
           return ajax;
        }
        return toastr['error']("Required inputs are empty", "MCspam")
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
