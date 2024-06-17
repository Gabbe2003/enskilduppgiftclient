<div class="container mt-5">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <h2 class = "d-flex justify-content-center">Delete Product</h2>
    <?php
    if (isset($_GET['productId'])) {
        $productId = $_GET['productId'];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://localhost:9000/deleteProduct/" . $productId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => [
                "cache-control: no-cache"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo  $err;
        } else {
            $responseData = json_decode($response, true);
            echo "<p>" . htmlspecialchars($responseData['message']) . "</p>";
            echo '<br>';
            echo '<div class=" d-flex justify-content-center"><a href="../index.php" class="btn btn-primary">Return to Product List</a></div>'; 

        }
    } else {
        echo '<form action="deleteProduct.php" method="get" class="mt-3">
            <div class="mb-3">
                <label for="productId" class="form-label">Product ID *</label>
                <input type="text" class="form-control" id="productId" name="productId" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete Product</button>
        </form>';
    }
    ?>
</div>
