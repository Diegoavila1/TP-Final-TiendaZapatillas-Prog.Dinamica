<?php
include '../estructura/cabecera.php';

$session = new Session();
?>

<form id="login" method="post">
    <div class="form-group">
        <label for="mail">Mail:</label>
        <input type="mail" class="form-control" id="mail" name="mail" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<script>
$(document).ready(function(){
    $('#login').submit(function(e){
        e.preventDefault();
        var datos = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: './action.php',
            data: datos,
            success: function(data){
                if(data == '1'){
                    window.location.href = '../home/index.php';
                } else {
                    window.location.href = './index.php?error=1';
                }
            }
        });
    });
});
</script>