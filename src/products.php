<?php
session_start();
include "config.php";
include "classes/DB.php";

$stmt = user\DB::getInstance()->prepare("SELECT * FROM Products INNER JOIN Product_category 
WHERE Products.category_ID=Product_category.category_id");
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

if (isset($_POST["submit"])) {
    $id1 = $_POST["del"];
    $stmt2 = user\DB::getInstance()->prepare("DELETE FROM Products WHERE product_id=$id1");
    $stmt2->execute();
}

if (isset($_POST["add"])) {
    $pname=$_POST["pname"];
    $pcat=$_POST["prodCat"];
    $psale=$_POST["sale"];
    $plist=$_POST["list"];
    $img=$_POST["img"];
    $stmt1 = user\DB::getInstance()->prepare("SELECT * FROM Product_category ");
    $stmt1->execute();
    $result = $stmt1->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($stmt1->fetchAll() as $k => $v) {
        if ($v["category_name"]== $pcat) {
            $prodCatID=$v["category_id"];
        }
    }
    try {
        $stmt2 = user\DB::getInstance()->prepare("INSERT INTO 
        Products (`product_name`, `category_ID`, `sales_price`, `list_price`, `image`)
        VALUES('$pname', $prodCatID ,'$psale','$plist','$img')");
        $stmt2->execute();
        header("location: products.php");
    } catch (Exception $e) {
        echo '<script>alert("Duplicate Products cannot be added! Please Try again!")</script>';
    }
}
if (isset($_POST["search"])) {
    $id=$_POST["value"];
    $stmt = user\DB::getInstance()->prepare("SELECT * FROM Products INNER JOIN Product_category 
    WHERE Products.category_ID=Product_category.category_id AND (product_id LIKE '$id%'
    OR product_name LIKE '$id%')");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.88.1">
  <title>Dashboard Template · Bootstrap v5.1</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/dashboard/">



  <!-- Bootstrap core CSS -->
  <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">


  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>


  <!-- Custom styles for this template -->
  <link href="./assets/css/dashboard.css" rel="stylesheet">
</head>

<body>

  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Company name</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" 
    type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" 
    aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" 
    aria-label="Search">
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" href="signout.php">Sign out</a>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="dashboard.php">
                <span data-feather="home"></span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="orderadmin.php">
                <span data-feather="file"></span>
                Orders
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="shopping-cart"></span>
                Products
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="bar-chart-2"></span>
                Reports
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="layers"></span>
                Integrations
              </a>
            </li>
          </ul>

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Saved reports</span>
            <a class="link-secondary" href="#" aria-label="Add a new report">
              <span data-feather="plus-circle"></span>
            </a>
          </h6>
          <ul class="nav flex-column mb-2">
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Current month
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Last quarter
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Social engagement
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Year-end sale
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center
         pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Products</h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
              <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
              <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
              <span data-feather="calendar"></span>
              This week
            </button>
          </div>
        </div>

        <form class="row row-cols-lg-auto g-3 align-items-center" action="" method="POST">
          <div class="col-12">
            <label class="visually-hidden" for="inlineFormInputGroupUsername">Search</label>
            <div class="input-group">
              <input type="text" class="form-control" name="value" 
              id="inlineFormInputGroupUsername" placeholder="Enter id,name...">
            </div>
          </div>



          <div class="col-12">
            <button type="submit" class="btn btn-primary" name="search">Search</button>
          </div>
          </form>


        <br>
        <div class="table-responsive">
        <?php
          $html = "";
          $html .= '<table class="table table-striped table-sm">     
        <tr>
          <th scope="col">Product Id</th>
          <th scope="col">Product name</th>
          <th scope="col">Product Category Name</th>
          <th scope="col">Product Sales Price</th>
          <th scope="col">Product List Price</th>
          <th scope="col">action</th>
        </tr>
      ';
        foreach ($stmt->fetchAll() as $k => $v) {
            $html .= '<tr>
    <td>' . $v["product_id"] . '</td>
    <td>' . $v["product_name"] . '</td>
    <td>' . $v["category_name"] . '</td>
    <td>' . $v["sales_price"] . '</td>
    <td>' . $v["list_price"] . '</td>
    <td class="d-inline-flex"><form action="updateProduct.php" method="POST">
    <input type="hidden" name="edit1" value="' . $v["product_id"] . '">
    <input type="hidden" name="edit2" value="' . $v["product_name"] . '">
    <input type="hidden" name="edit3" value="' . $v["category_name"] . '">
    <input type="hidden" name="edit4" value="' . $v["sales_price"] . '">
    <input type="hidden" name="edit5" value="' . $v["list_price"] . '">
    <button type="submit" class="btn btn-primary" name="submit1"> Edit</button></form>
    <form action="" method="POST">
    <input type="hidden" name="del" value="' . $v["product_id"] . '">
    <button type="submit" class="btn btn-danger" name="submit"> Delete</button></form>
    </td>
    </tr>';
        }
          $html .= '</table>';
          echo $html;

            ?>

          </tbody>
          </table>
          <div class="col-12">
            <a class="btn btn-success" href="add-product.php">Add Product</a>
          </div>
        </div>
      </main>
    </div>
  </div>


  <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js" 
  integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
  crossorigin="anonymous"></script>
</body>

</html>