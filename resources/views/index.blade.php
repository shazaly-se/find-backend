<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Chat app</title>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="app">
    <div class="container">
<div class="mb-3">
  <input type="text" class="form-control" id="username" placeholder="enter name">
</div>

<form id="message_form">

<div class="mb-3">
  <input type="text" class="form-control" id="message_input" placeholder="enter message">

  <button type="submit" id="message_send" class="btn btn-primary mt-5">Send</button>
</div>
</form>

<div class="row mt-20">
    <div class="col-md-12">
    <div id="messages"></div>
    </div>
</div>
</div>
</div>
<script src="./js/app.js"></script>
</body>
</html>