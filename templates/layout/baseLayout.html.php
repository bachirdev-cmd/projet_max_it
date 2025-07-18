<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAX IT Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #0a0a0a;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen">
    <!-- Header -->
  
    <?php require_once "../templates/layout/partial/sideBar.html.php"; ?>
    <!-- Main Content -->
    <main class="px-6 py-8 max-w-7xl mx-auto">

       <?php echo $content; ?>
       
    </main>
</body>
</html>