$(document).ready(function () {
  const input_value = $("#password");

  //disable input from typing

  $("#password").keypress(function () {
    return false;
  });

  $('#password').attr('readonly','readonly');

  //add password
  $(".calc").click(function () {
    let value = $(this).val();
    field(value);
  });
  function field(value) {
    if(input_value.val().length < 6){
      input_value.val(input_value.val() + value);
    }
  }
  $("#clear").click(function () {
    input_value.val("");
  });
  // $("#enter").click(function () {
  //   alert("Your password " + input_value.val() + " added");
  // });
});
