<?php
if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://localhost:9000/getOneProduct/" . $productId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "cache-control: no-cache"
        ],
    ]);
    

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $product = json_decode($response, true);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        exit;
    }

    if (!$product) {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'] ?? 'No description provided',
        'price' => floatval($_POST['price']),
        'imageUrl' => $_POST['imageUrl']
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://localhost:9000/updateProduct/" . $productId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json", 
            "cache-control: no-cache"
        ],
    ]);
    
    $response = curl_exec($curl);
    
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #: " . $err;
    } else {
        echo $response;
        echo '<div class=" d-flex justify-content-center"><a href="../index.php" class="btn btn-primary">Return to Product List</a></div>'; 
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../style/update.css">
    <meta charset="UTF-8">
    <title>Update Product</title>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price'] ?? '0'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="imageUrl">Image URL</label>
                    <input type="url" id="imageUrl" name="imageUrl" value="<?php echo htmlspecialchars($product['imageUrl'] ?? ''); ?>">
                </div>
                <button type="submit" class="btn custom-btn">Update Product</button>
            </form>
        </div>
    </div>
</body>
</html>
</html>
