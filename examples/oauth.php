<?php

require '../vendor/autoload.php';

$redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

$config = require __DIR__ . '/config.php';
$client = new GuzzleHttp\Client;

$google = new Igniweb\OAuth\Providers\Google($client, [
    'clientId'     => $config['google']['client_id'],
    'clientSecret' => $config['google']['client_secret'],
    'redirectUrl'  => $redirectUrl,
    'scopes'       => ['profile', 'email'],
]);

$github = new Igniweb\OAuth\Providers\Github($client, [
    'clientId'     => $config['github']['client_id'],
    'clientSecret' => $config['github']['client_secret'],
    'redirectUrl'  => $redirectUrl,
    'scopes'       => ['user:email'],
]);

$instagram = new Igniweb\OAuth\Providers\Instagram($client, [
    'clientId'     => $config['instagram']['client_id'],
    'clientSecret' => $config['instagram']['client_secret'],
    'redirectUrl'  => $redirectUrl,
    'scopes'       => ['basic'],
]);

if (isset($_GET['code']))
{
    try
    {
        // Github: d1f01d6c0114b4ad708f
        // Google: 4/wPlICgLXErQCGORuRxAg0iGl1tbcoxTWomjoicAVh7g.4vJWv1f6HxweoiIBeO6P2m_IhY7qlQI
        // Instagram: 107154554124434f9073b2740f421591
        $codeLen = strlen($_GET['code']);
        $provider = ($codeLen == 20) ? 'github' : (($codeLen == 32) ? 'instagram' : 'google');
        $user = $$provider->user($_GET['code']);
    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>OAuth examples</title>
    <link rel="stylesheet" type="text/css" href="vendor/semantic-ui/dist/semantic.css">
    <style type="text/css">
        .container {
            margin: 2em auto 1em;
            width: 80%;
        }
    </style>
    <script type="text/javascript" src="vendor/jquery/dist/jquery.js"></script>
    <script type="text/javascript" src="vendor/semantic-ui/dist/semantic.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="ui dividing header">OAuth examples</h2>

        <?php if ( ! isset($user)) : ?>

            <a href="<?php echo $google->authorizationUrl(); ?>" id="signin_google">
                <div class="ui google plus button">
                    <i class="google plus icon"></i>
                    Google
                </div>
            </a>

            <a href="<?php echo $github->authorizationUrl(); ?>" id="signin_github" style="display: block; margin-top: 1em;">
                <div class="ui github button">
                    <i class="github icon"></i>
                    Github
                </div>
            </a>

            <a href="<?php echo $instagram->authorizationUrl(); ?>" id="signin_instagram" style="display: block; margin-top: 1em;">
                <div class="ui instagram button">
                    <i class="instagram icon"></i>
                    Instagram
                </div>
            </a>

        <?php else : ?>

            <pre><?php print_r($user); ?></pre>

        <?php endif; ?>
    </div>
</body>
</html>
