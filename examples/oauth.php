<?php

require '../vendor/autoload.php';

$redirectUri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

/*
$provider = new Igniweb\OAuth\Providers\Google([
    'clientId'     => '644622676486-urh79l905tblnoq3pndlbcr66stppsme.apps.googleusercontent.com',
    'clientSecret' => 'G96xtZ7nxXnI6i55pcJ_2YKx',
    'redirectUri'  => $redirectUri,
    'scopes'       => ['email'],
]);
*/

$provider = new Igniweb\OAuth\Providers\Github([
    'clientId'     => 'dd860cd3a216bedcae6f',
    'clientSecret' => 'e201dade2773e9911c2ab3b06a4c6bd932f7b7c5',
    'redirectUri'  => $redirectUri,
    'scopes'       => ['user', 'email'],
]);

if (isset($_GET['code']))
{   
    try
    {
        $user = $provider->user($_GET['code']);
    }
    catch (Exception $e)
    {
        die('No user');
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

            <!--
            <a href="<?php echo $provider->authorizationUrl(); ?>" id="signin_google">
                <div class="ui google plus button">
                    <i class="google plus icon"></i>
                    Google Plus
                </div>
            </a>
            -->

            <a href="<?php echo $provider->authorizationUrl(); ?>" id="signin_github">
                <div class="ui github button">
                    <i class="github icon"></i>
                    Github
                </div>
            </a>

        <?php else : ?>

            <pre><?php print_r($user); ?></pre>

        <?php endif; ?>
    </div>
</body>
</html>
