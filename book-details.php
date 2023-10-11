<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h1 class="card-title">Book Details</h1>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <tr>
                    <th>Book Name:</th>
                    <td><?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?></td>
                </tr>
                <tr>
                    <th>Details:</th>
                    <td><?php echo isset($_GET['details']) ? $_GET['details'] : ''; ?></td>
                </tr>
                <tr>
                    <th>Author:</th>
                    <td><?php echo isset($_GET['author']) ? $_GET['author'] : ''; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <a class="btn btn-secondary mt-3" href="javascript:window.close();">Close</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
