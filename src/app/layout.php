<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($metadata['description']); ?>">
    <title><?php echo htmlspecialchars($metadata['title']); ?></title>
    <link rel="shortcut icon" href="<?php echo $baseUrl; ?>favicon.ico" type="image/x-icon">
    <script>
        const baseUrl = '<?php echo $baseUrl; ?>';
        const pathname = '<?php echo $pathname; ?>';
    </script>
    <link href="<?php echo $baseUrl; ?>css/styles.css" rel="stylesheet"> 
    <link href="<?php echo $baseUrl; ?>css/index.css" rel="stylesheet">
    <script src="<?php echo $baseUrl; ?>js/index.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="<?php echo $baseUrl; ?>js/htmx.min.js"></script>
</head>

<body>
    <!-- Additional HTML content can go here. -->
    <?php echo $content; ?>
    <!-- Additional HTML content can go here. -->
</body>

</html>