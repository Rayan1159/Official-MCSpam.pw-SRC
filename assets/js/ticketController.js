$(document).ready(function () {
   $(document).on('click', '#send', function () {
       var id = $(this).data('id');
       var message = $('#message').val();

       if (id && message) {
           if (!isNaN(id)){
               $.ajax('inc/Requests/ticketRequest', {
                   data: {
                       add_message: 1,
                       id: id,
                       message: message
                   },
                   method: "POST",
                   success:function (getResp) {
                       if(getResp === "closed_ticket"){
                           return toastr['error']("This ticket is closed, you can not send messages.", "MCSpam")
                       }
                       if (getResp === "message_sent"){
                           setTimeout(function () {
                              window.location = "view_ticket?id="+id
                           }, 2500);
                           return toastr['success']("Your message was sent", "MCSpam");
                       }
                       if(getResp === "not_sent") {
                           return toastr['error']("Message failed to send.", "MCSpam");
                       }
                   }
               });
           } else {
               return toastr['error']("Something went wrong while sending the message", "MCSpam");
           }
       } else {
           return toastr['error']("Message field was empty!", "MCspam");
       }
   });
    $(document).on('click', '#close', function () {
        var id = $(this).data('id');

        $.ajax('/inc/Requests/ticketRequest', {
            data: {
                close: 1,
                id: id
            },
            method: "POST",
            success: function (getResp) {
                if (getResp === "ticket_closed"){
                    setTimeout(function () {
                        window.location = "ticket";
                    }, 2500);
                    return toastr['success']("The ticket has been closed.", "MCSpam");
                }
                if(getResp === "not_closed") {
                    return toastr['error']("Failed to close the ticket", "MCSpam");
                }
            }
        })
    });
});