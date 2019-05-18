$(document).ready(function(){
  $('#target').on('input',function(e){
      $('#get_ip').html($('#target').val());
  });

  $('#port').on('input',function(e){
      $('#get_port').html($('#port').val());
  });

  $('#time').on('input',function(e){
      $('#get_time').html($('#time').val());
  });

  $('#version').on('change',function(e){
      $('#get_version').html($('#version').val());
  });

  function clearFields() {
      $('#target').val('');
      $('#port').val('');
      $('#time').val('');
      $('#version').val();
  }


  var btnFinish = $('<button></button>').text('Send attack')
      .addClass('btn btn-info').on('click', function(){
          var ip = $('#target').val();
          var port = $('#port').val();
          var time = $('#time').val();
          var version = $('#version').val();
          var vip = $('#vip').val();
          var mode = $('#mode').val();

          if (ip === "" || port === "" || time === "" || version === "" || vip === "" || mode === "") {
              return toastr['error']("There's a required input field empty.", "MCSpam");
          }

          $.ajax('inc/Requests/attackRequest', {
              data: {
                  joinbot: 1,
                  ip: ip,
                  port: port,
                  time: time,
                  version: version,
                  vip: vip,
                  mode: mode
              },
              method: "POST",
              success: function (getResp) {
                  if (getResp === "no_plan"){
                      return toastr['error']("You do not have a plan", "MCSpam");
                  }

                  if (getResp === "no_vip"){
                      return toastr['error']("You do not have VIP", "MCSpam");
                  }

                  if (getResp === "max_running") {
                      return toastr['error']("The maximum global attacks are running, please wait a few minutes", "MCspam");
                  }

                  if (getResp === "server_blacklist") {
                      return toastr['error']("The server you're trying to attack is blacklisted.", "MCSpam");
                  }

                  if(getResp === "attack_sent") {
                      return toastr['success']("The attack was sent!", "MCSpam");
                  }
                  if(getResp === "attack_failed"){
                      return toastr['error']("Something went wrong while sending the attack", "MCSpam");
                  }
                  if (getResp === "time_limit"){
                      return toastr['error']("You exceeded your time limit", "MCSpam");
                  }
                  if (getResp === "concurrent_limit") {
                      return toastr['error']("You exceeded your concurrents. wait for your attack to finish", "MCSpam")
                  }
                  return toastr['error']("Something went wrong while sending your attack", "MCSpam");
              }
          });
          $('#smart_wizard_arrows').smartWizard("reset");
          clearFields();
  });
  var btnCancel = $('<button></button>').text('Cancel')
      .addClass('btn btn-light')
      .on('click', function(){
          $('#smart_wizard_arrows').smartWizard("reset");
          clearFields();
  });

  $("#smart_wizard_arrows").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
      if($('button.sw-btn-next').hasClass('disabled')){
          $('.sw-btn-group-extra').show();
      }else{
          $('.sw-btn-group-extra').hide();
      }
  });

  $('#smart_wizard').smartWizard({
      selected: 0,
      theme: 'default',
      transitionEffect:'fade',
      showStepURLhash: true,
      toolbarSettings: {
          toolbarButtonPosition: 'end',
          toolbarExtraButtons: [btnFinish, btnCancel]
      }
  });

  // Smart Wizard Arrows
  $('#smart_wizard_arrows').smartWizard({
      selected: 0,
      theme: 'arrows',
      transitionEffect:'fade',
      toolbarSettings: {
          toolbarPosition: 'bottom',
          toolbarExtraButtons: [btnFinish, btnCancel]
      }
  });
  

   // Smart Wizard Circle
   $('#smart_wizard_circles').smartWizard({
      selected: 0,
      theme: 'circles',
      transitionEffect:'fade',
      toolbarSettings: {
          toolbarPosition: 'bottom',
          toolbarExtraButtons: [btnFinish, btnCancel]
      }
  });
});