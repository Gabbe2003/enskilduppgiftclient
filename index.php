<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/style/index.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <?php
        $url = "http://localhost:9000/getProducts";  

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => ["cache-control: no-cache"],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $products = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "JSON decode error: " . json_last_error_msg();
                exit;
            }


            if (is_array($products)) {
                foreach ($products as $product) {
                    // Check if all expected keys exist
                    if (!isset($product['name'], $product['price'], $product['imageUrl'], $product['_id'])) {
                        echo 'Product data incomplete: '; print_r($product); continue;
                    }
                    $productId = urlencode($product['_id']); 

                    $name = htmlspecialchars($product['name']);
                    $description = isset($product['description']) ? htmlspecialchars($product['description']) : 'No description available';
                    $price = htmlspecialchars($product['price']);
                    $imageUrl = htmlspecialchars($product['imageUrl']);

                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card product-card">';
                    echo '<img src="' . $imageUrl . '" class="card-img-top product-img" alt="Image of ' . $name . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $name . '</h5>';
                    echo '<p class="card-text">' . $description . '</p>';
                    echo '<a href="export/exportHandler.php?format=xml&productId=' . $productId . '" class="btn btn-success">Export to XML</a>';
                    echo '<a href="export/exportHandler.php?format=csv&productId=' . $productId . '" class="btn btn-success">Export to CSV</a>';
                    echo '<p class="price">$' . $price . '</p>';
                    echo '<a href="crud-client/updateProduct.php?productId=' . urlencode($product['_id']) . '" class="btn btn-info">Update</a>';
                    echo '<a href="crud-client/deleteProduct.php?productId=' . urlencode($product['_id']) . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>';
                    echo '</div></div></div>';
                }
            } else {
                echo "No products found.";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
