<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
  		<link rel="stylesheet" href="css/style_number_separator.css">
        <title>Print</title>
    </head>
    <body class="centered">
        <h1>Regex Add Comma</h1>
		<fieldset>
		  <label for="Currency">Enter Amount</label>
		  <input  id="Currency"type="text" class="currency" />
		</fieldset>

		<code>return this.replace(/(.)(?=(.{3})+$)/g,"$1,")</code>
    </body>
<script type="text/javascript">
    $(document).ready(function(){
    //apply on typing and focus
    $('input.currency').on('blur',function(){
       $(this).manageCommas();
    });
    //then sanatize on leave
  // if sanitizing needed on form submission time, 
  //then comment beloc function here and call in in form submit function.
    $('input.currency').on('focus',function(){
        $(this).santizeCommas();
    });
});

String.prototype.addComma = function() {
  return this.replace(/(.)(?=(.{3})+$)/g,"$1,").replace(',.', '.');
}
//Jquery global extension method
$.fn.manageCommas = function () {
    return this.each(function () {
        $(this).val($(this).val().replace(/(,|)/g,'').addComma());
    });
}

$.fn.santizeCommas = function() {
  return $(this).val($(this).val().replace(/(,| )/g,''));
}
</script>
</html>