<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Start Report</title>
</head>

<body>

<h3 align="center">Start Reporting Task RealUSSDMon</h3>

<form action="dostartreport.php" method="post" enctype="multipart/form-data" name="frm_notif">

<table class="table">
  <tr>
    <td>Password</td>
    <td><input class="form-control" name="password" type="password" size="50" /></td>
  </tr>
  <tr>
    <td>Start Date</td>
    <td><input class="form-control" name="date_start" type="date" /></td>
  </tr>
  <tr>
    <td>End Date</td>
    <td><input class="form-control" name="date_end" type="date" /></td>
  </tr>
  <tr>
    <td colspan="2"><input name="submit" value="  Start  " type="submit" />&nbsp;<input name="reset" value="Reset" type="reset" /></td>
    </tr>
</table>

</form>

</body>
</html>
