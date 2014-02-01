<? if(Users::$user['group_id']=='19'){
    header('Location: /admin');
    exit();
}
?>
<? if(!empty($_POST['submit'])):
    //E::debug();
    if(!Users::login($_POST['email'],$_POST['password'],$_POST['password'])) {
       header('HTTP/1.0 403 Forbidden');
    }
    ?>
    <?=t('Wrong username or password')?>
    <? exit(); ?>
<? else: ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Elementus</title>
    <!-- Bootstrap -->
    <link href="/admin/static/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/admin/static/css/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
    <link href="/admin/static/css/custom.css" rel="stylesheet" media="screen">

    <!-- JavaScript plugins (requires jQuery) -->
    <script type="text/javascript" src="/admin/static/js/jquery-1.9.0.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/admin/static/js/bootstrap.min.js"></script>
    <style type="text/css">
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 300px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
            -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }
        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }
        .form-signin input[type="text"],
        .form-signin input[type="password"] {
            font-size: 16px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
        }

    </style>
</head>
<body>
    <div class="container">
        <form class="form-signin" method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=login">
            <h2 class="form-signin-heading"><?=t('Sign in')?></h2>
            <input type="text" name="email" class="input-block-level" placeholder="Email address">
            <input type="password" name="password" class="input-block-level" placeholder="Password">
            <label class="checkbox">
                <input type="checkbox" name="remember" value="1" checked="checked"> <?=t('Remember me')?>
            </label>
            <input type="hidden" name="submit" value="submit">
            <button class="btn btn-large btn-primary" type="submit"><?=t('Sign in')?></button>
        </form>
        <script>
            $(function() {
                $('form[data-async]').submit(function(event) {
                    var form = $(this);
                    var target = $(form.attr('data-target'));
                    $.ajax({
                        type: form.attr('method'),
                        url: form.attr('action'),
                        data: form.serialize(),

                        success: function(data, status) {
                            window.location.href='/admin/';
                        },
                        error: function(data) {
                            $('.modal-title').html('<?=t('Error')?>');
                            $(target).html('<?=t('Wrong username or password')?>');
                            $('#window').modal('show');
                        }

                    });
                    event.preventDefault();
                });
            });
        </script>
    </div>
<? require_once("pages/footer.php"); ?>
<? endif; ?>