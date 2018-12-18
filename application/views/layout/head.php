<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo empty($data['CurMenu']['Name'])?'后台管理':$data['CurMenu']['Name']; ?></title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/layui/2.4.3/css/layui.css">

    <script src="/js/jquery-2.2.4.min.js"></script>
    <script src="/layui/2.4.3/layui.js"></script>
    <script src="/js/flexie.min.js"></script>
    <script src="/js/func.js"></script>

    <style>
        .btn-upload {
            position: relative;
            display: inline-block;
        }
        .btn-upload input {
            position: absolute;
            right: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            -ms-filter: 'alpha(opacity=0)';
        }
    </style>
</head>

