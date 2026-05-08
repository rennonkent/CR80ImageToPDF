<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <style>
    .fs-smaller {
      font-size: 12px;
    }

    .fs-small {
      font-size: 13px;
    }
  </style>

  <title>ID PDF Generator</title>
</head>
<body>
  
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center">ID PDF Generator</h1>
        <?php
          if (isset($_SESSION['response'])) {
            echo '<div class="alert alert-dark alert-dismissible fade show p-2" role="alert">
                    <span>' . $_SESSION['response'] . '</span>
                    <button type="button" class="btn-close btn-sm p-3 fs-small" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';

            unset($_SESSION['response']);
          }
        ?>
      </div>
      <div class="col-5">
        <form action="generate.php" method="POST">
          <div class="mb-2">
            <label for="idNumber" class="form-label fs-small">Name</label>
            <input type="text" class="form-control form-control-sm fs-small shadow-none" id="idNumber" name="idNumber">
          </div>
          <button type="submit" class="btn btn-sm btn-dark fs-smaller">Generate</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>