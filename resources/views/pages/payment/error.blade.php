<!DOCTYPE html>
<html>
<head>
    <title>Checkout Error</title>
</head>
<body>
    <h1>Something went wrong</h1>
    <script>
        const error = {!! $error !!};
        console.error("PayMongo API Error:", error);
    </script>
</body>
</html>
