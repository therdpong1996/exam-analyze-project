<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Start your development with a Design System for Bootstrap 4.">
    <title><?php echo $meta['title']; ?></title>

    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $_G['furl']; ?>assets/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $_G['furl']; ?>assets/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $_G['furl']; ?>assets/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $_G['furl']; ?>assets/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $_G['furl']; ?>assets/icon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo $_G['furl']; ?>manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo $_G['furl']; ?>assets/icon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link href="<?php echo $_G['furl']; ?>assets/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="<?php echo $_G['furl']; ?>assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="<?php echo $_G['furl']; ?>assets/css/argon.css?v=1.0.1" rel="stylesheet">

    <!-- Firestore -->
    <script src="https://www.gstatic.com/firebasejs/5.5.5/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.5.5/firebase-firestore.js"></script>
    <script>
        firebase.initializeApp({
            apiKey: "AIzaSyB9qKRcxJkjhJAcuKLErhCF15o0ZZkEfNQ",
            authDomain: "cat-project-rmutl.firebaseapp.com",
            projectId: "cat-project-rmutl",
        })
        var db = firebase.firestore();
        db.settings({
            timestampsInSnapshots: true
        });
        db.enablePersistence()
        db.collection("articles").get().then((querySnapshot) => {
            querySnapshot.forEach((doc) => {
                console.log(`${doc.id} => ${doc.data()}`);
            });
        });

        var weburl = '<?php echo $_G['furl']; ?>';
    </script>
</head>

<body>