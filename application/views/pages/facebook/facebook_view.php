<html>
<head>
    <title>Facebook</title>
</head>
<body>
<h1>Social media stuff</h1>

<?php if (@$user_profile): ?>
    <pre>
            <?php echo print_r($user_profile, TRUE) ?>
        </pre>
    <a href="/facebookSetup/logoutOnFacebook">Logout</a>
<?php else: ?>
    <h2>Welcome, please login below</h2>
    <a href="<?= $login_url ?>">Login</a>
<?php endif; ?>

</body>

</html>  