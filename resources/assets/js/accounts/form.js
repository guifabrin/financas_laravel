$('#is_credit_card').on('change', function(){
  if (this.checked){
    $('#prefer_debit_account_id').closest('.form-group').slideDown();
    $('#debit_day').closest('.form-group').slideDown();
    $('#credit_close_day').closest('.form-group').slideDown(); 
  } else {
    $('#prefer_debit_account_id').closest('.form-group').slideUp();
    $('#debit_day').closest('.form-group').slideUp();
    $('#credit_close_day').closest('.form-group').slideUp(); 
  }
});

$(function(){
  $('#is_credit_card').change();  
});