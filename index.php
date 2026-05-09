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
      <div class="col-lg-12">
        <h1 class="text-center border-bottom border-danger">ID PDF Generator</h1>
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
      <form action="app/classes/convert_img.php" method="POST">
        <div class="col-lg-5">
          <div class="mb-2">
            <label for="id_number" class="form-label fs-small">ID Number:</label>
            <input type="text" class="form-control form-control-sm fs-small shadow-none" id="id_number" name="id_number">
          </div>
        </div>

        <!-- <div class="col-lg-5">
          <div class="mb-2">
            <label for="fname" class="form-label fs-small">First Name:</label>
            <input type="text" class="form-control form-control-sm fs-small shadow-none" id="fname" name="fname">
          </div>
        </div>

        <div class="col-lg-5">
          <div class="mb-2">
            <label for="lname" class="form-label fs-small">Last Name:</label>
            <input type="text" class="form-control form-control-sm fs-small shadow-none" id="lname" name="lname">
          </div>
        </div> -->

        <div class="col-lg-5">
          <div class="mb-2">
            <label for="position" class="form-label fs-small">Position:</label>
            <input type="text" class="form-control form-control-sm fs-small shadow-none" id="position" name="position">
          </div>
        </div>

        <div class="col-lg-5">
          <div class="mb-2">
            <label for="notify_name" class="form-label fs-small">Notify Name:</label>
            <input type="text" class="form-control form-control-sm fs-small shadow-none" id="notify_name" name="notify_name">
          </div>
        </div>

        <div class="col-lg-5">
          <div class="mb-2">
            <label for="notify_contact" class="form-label fs-small">Notify Contact:</label>
            <input type="text" class="form-control form-control-sm fs-small shadow-none" id="notify_contact" name="notify_contact">
          </div>
        </div>

        <button type="submit" class="btn btn-sm btn-dark fs-smaller">Generate</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>