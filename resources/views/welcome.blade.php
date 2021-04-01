<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Furaha SMS</title>

    </head>
    <body>
       App : LIVE!
       DATABASE : OKAY
       <?php echo env('DATABASE_URL'); ?>
       <?php echo (env('APP_NAME') == null ? 'NULL': 'NOT NULL') ?>
       <?php echo (env('APP_NAMES') == null ? 'NULL': 'NOT NULL') ?>
    </body>
</html>
