<?php

require '../vendor/autoload.php';

$redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

$config = require __DIR__ . '/config.php';
$client = new GuzzleHttp\Client;

$scopes = [
    'facebook'  => ['public_profile ', 'email'],
    'github'    => ['user:email'],
    'google'    => ['profile', 'email'],
    'instagram' => ['basic'],
];

foreach ($config as $provider => $providerConfig)
{
    $classProvider = 'Igniweb\OAuth\Providers\\' . ucfirst($provider);
    $$provider = new $classProvider($client, [
        'clientId'     => $providerConfig['client_id'],
        'clientSecret' => $providerConfig['client_secret'],
        'redirectUrl'  => $redirectUrl,
        'scopes'       => $scopes[$provider],
    ]);
}

if ( ! empty($_GET['code']))
{
    $codeLen = strlen($_GET['code']);
    $guessedProvider = 'google'; // 4/wPlICgLXErQCGORuRxAg0iGl1tbcoxTWomjoicAVh7g.4vJWv1f6HxweoiIBeO6P2m_IhY7qlQI
    switch ($codeLen)
    {
        case 20: // d1f01d6c0114b4ad708f
            $guessedProvider = 'github';
            break;
        case 32: // 107154554124434f9073b2740f421591
            $guessedProvider = 'instagram';
            break;
    }
    if ($codeLen > 300)
    {   // AQBXfNA2kfSXWTHhM6NCBNSyqbBzz4NrQUkXuZU_rEJ0Jg666nsL8TI0KU-1hYAe4gqIBojjVoOufbj0Ut2kC1F5nvFEysrCiHEPU2I0vzSqsvx2BDVx_s-b2UlZsHtjk0l4-SA_HFt4_hitMGwy7QgcjavYi3oDK6fnf0-X-0YrKj30cJ8GJTAt9KsD6Z0MWNoa96jJfauY-n27SOOuiwu32VB9f-ayWxdl6oux3Vwt2MbdcrVXFVRGcFAx9LtLp8G8nxvG_iQUT91YLxMuY1iLx-RptEznJFbhiDC31TjiNiFj28HXtXzOjpfaVMZOUvooJk2eZ4lJuo65SyTYIlvH
        $guessedProvider = 'facebook';
    }
    
    $token = $$guessedProvider->accessToken($_GET['code']);
    $user = $$guessedProvider->user($token);
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>OAuth examples</title>
    <link rel="stylesheet" type="text/css" href="vendor/semantic-ui/dist/semantic.min.css">
    <style type="text/css">
        .container {
            margin: 2em auto 1em;
            width: 80%;
        }
        .signins {
            margin: 1em .5em;
            display: block;
        }
    </style>
    <script type="text/javascript" src="vendor/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="vendor/semantic-ui/dist/semantic.min.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="ui dividing header">OAuth examples</h2>

        <?php if ( ! isset($user)) : ?>

            <?php foreach ($config as $provider => $providerConfig) : $semanticClass = ($provider == 'google') ? 'google plus' : $provider; ?>

                <a href="<?php echo $$provider->authorizationUrl(); ?>" class="signins" id="signin_<?php echo $provider; ?>">
                    <div class="ui <?php echo $semanticClass; ?> button">
                        <i class="<?php echo $semanticClass; ?> icon"></i>
                        <?php echo ucfirst($provider); ?>
                    </div>
                </a>
                
            <?php endforeach; ?>

        <?php else : ?>

            <pre><?php print_r($user); ?></pre>

        <?php endif; ?>
    </div>
</body>
</html>
