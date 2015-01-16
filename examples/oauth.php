<?php
require '../vendor/autoload.php';

session_start();

$provider = new Igniweb\OAuth\Providers\Google([
    'clientId'     => '644622676486-urh79l905tblnoq3pndlbcr66stppsme.apps.googleusercontent.com',
    'clientSecret' => 'G96xtZ7nxXnI6i55pcJ_2YKx',
    'redirectUri'  => 'http://sand.igniweb.net/oauth/examples/oauth.php',
    'scopes'       => 'email',
]);

if ( ! isset($_GET['code']))
{   // No auto-response
    $_SESSION['state'] = $provider->state;
    $authUrl = $provider->getAuthorizationUrl();
}
elseif (empty($_GET['state']) or ($_GET['state'] != $_SESSION['state']))
{   // CSRF protection
    unset($_SESSION['state']);
    die('Error 403');
}
else
{   // Auto-response, try to get user information
    $token = $provider->getAccessToken(['code' => $_GET['code']]);
    try
    {
        $user = $provider->getUser($token);
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

        <?php if (isset($authUrl)) : ?>

            <a href="<?php echo $authUrl; ?>" id="signin_google">
                <div class="ui google plus button">
                    <i class="google plus icon"></i>
                    Google Plus
                </div>
            </a>

        <?php else : ?>

            <pre><?php print_r($user); ?></pre>

        <?php endif; ?>
    </div>
</body>
</html>