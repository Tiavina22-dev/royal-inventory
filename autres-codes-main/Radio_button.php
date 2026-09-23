<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery Detect Change in Input Field</title>
</head>

<body>
<form id="myform" name="myform" action="" method="post">
<input type="radio" name="group1" value="Milk" checked="checked" />
Milk
<input type="text" name="text1" value="milk1" />
<input type="text" name="text2" value="milk2" />
<br/>
<input type="radio" name="group1" value="Cheese" />
Cheese
<input type="text" name="text3" value="cheese1" disabled="disabled" />
<input type="text" name="text4" value="cheese2" disabled="disabled" />
</form>
</body>
<script type="text/javascript">
	var form = document.forms['myform'];
	form.group1[0].onfocus = function () { form.text1.disabled = form.text2.disabled = false;
		form.text3.disabled = form.text4.disabled = true;
	}
	form.group1[1].onfocus = function () { form.text1.disabled = form.text2.disabled = true;
		form.text3.disabled = form.text4.disabled = false; }
</script>
</html>