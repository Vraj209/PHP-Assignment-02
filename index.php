<?php
include("dbinit.php"); // Include the database connection file

// Function to fetch all bag products from the database
function fetchBagProducts($conn)
{
    $sql = "SELECT * FROM bags";
    $result = $conn->query($sql);
    return $result;
}

// Function to insert a new Bag product into the database
function insertBagProduct($conn, $BagName, $BagDescription, $QuantityAvaliable, $Price, $BagCategory, $BagGender)
{
    $sql = "INSERT INTO bags (BagName, BagDescription, QuantityAvaliable, Price, BagCategory,BagGender) 
            VALUES (?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssidss', $BagName, $BagDescription, $QuantityAvaliable, $Price, $BagCategory, $BagGender);
        if ($stmt->execute()) {
            return true; // Inserted successfully
        } else {
            return false; // Error inserting
        }
    } else {
        return false; // Error preparing statement
    }
}

// Function to update a Bag product in the database
function updateBagProduct($conn, $BagID, $BagName, $BagDescription, $QuantityAvaliable, $Price, $BagCategory, $BagGender)
{
    $sql = "UPDATE bags SET BagName=?, BagDescription=?, QuantityAvaliable=?, Price=?, BagCategory=?, BagGender=? WHERE BagID=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssidssi', $BagName, $BagDescription, $QuantityAvaliable, $Price, $BagCategory, $BagGender, $BagID);
        if ($stmt->execute()) {
            return true; // Updated successfully
        } else {
            return false; // Error updating
        }
    } else {
        return false; // Error preparing statement
    }
}

// Function to delete a Bag product from the database
function deleteBagProduct($conn, $BagID)
{
    $sql = "DELETE FROM bags WHERE BagID=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $BagID);
        if ($stmt->execute()) {
            return true; // Deleted successfully
        } else {
            return false; // Error deleting
        }
    } else {
        return false; // Error preparing statement
    }
}

// Define a flag to determine whether to show the form or data
$showForm = true;

// Check if a form for creating, updating, or deleting has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["create"])) {
        // Create a new Bag product
        $BagName = $_POST["BagName"];
        $BagDescription = $_POST["BagDescription"];
        $QuantityAvaliable = $_POST["QuantityAvaliable"];
        $Price = $_POST["Price"];
        $BagCategory = $_POST["BagCategory"];
        $BagGender = $_POST["BagGender"];

        if (insertBagProduct($conn, $BagName, $BagDescription, $QuantityAvaliable, $Price, $BagCategory, $BagGender)) {
            echo "Bag product created successfully!";
        } else {
            echo "Error creating Bag product.";
        }
    } elseif (isset($_POST["update"])) {
        // Update an existing Bag product
        $BagID = $_POST["BagID"];
        $BagName = $_POST["BagName"];
        $BagDescription = $_POST["BagDescription"];
        $QuantityAvaliable = $_POST["QuantityAvaliable"];
        $Price = $_POST["Price"];
        $BagCategory = $_POST["BagCategory"];
        $BagGender = $_POST["BagGender"];

        if (updateBagProduct($conn, $BagID, $BagName, $BagDescription, $QuantityAvaliable, $Price, $BagCategory, $BagGender)) {
            echo "Bag product updated successfully!";
        } else {
            echo "Error updating Bag product.";
        }
    } elseif (isset($_POST["delete"])) {
        // Delete an existing Bag product
        $BagID = $_POST["BagID2"];
        if (deleteBagProduct($conn, $BagID)) {
            echo "Bag product deleted successfully!";
        } else {
            echo "Error deleting Bag product.";
        }
    }

    // After form submission, don't show the form again
    $showForm = false;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bag Products</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Bag Products</h1>

    <?php
    // Display the form for creating, updating, or deleting items only if $showForm is true
    if ($showForm) {
    ?>
        <form action="index.php" method="POST">
            <label for="BagID">Bag ID(for updates):</label>
            <input type="text" id="BagID" name="BagID"><br><br>

            <label for="BagID">Bag ID(for delete):</label>
            <input type="text" id="BagID" name="BagID2"><br><br>

            <label for="BagName">Bag Name:</label>
            <input type="text" id="BagName" name="BagName"><br><br>

            <label for="BagDescription">Bag Description:</label>
            <textarea id="BagDescription" name="BagDescription"></textarea><br><br>

            <label for="QuantityAvaliable">Quantity Available:</label>
            <input type="number" id="QuantityAvaliable" name="QuantityAvaliable"><br><br>

            <label for="Price">Price ($):</label>
            <input type="number" id="Price" name="Price" step="0.01"><br><br>

            <label for="BagAddedBy">Added By:</label>
            <input type="text" id="BagAddedBy" name="BagAddedBy" value="Vraj Panchal"><br><br>

            <label for="BagCategory">Bag Category:</label>
            <input type="text" id="BagCategory" name="BagCategory"><br><br>

            <label for="BagGender">Bag Gender:</label>
            <input type="text" id="BagGender" name="BagGender"><br><br>

            <input type="submit" name="create" value="Insert Bag">
            <input type="submit" name="update" value="Update Bag ">
            <input type="submit" name="delete" value="Delete Bag">
            <input type="reset" value="Clear All">
        </form>
    <?php
    }

    // Display all Bag products only after form submission
    if (!$showForm) {
        $BagProducts = fetchBagProducts($conn);
    ?>
        <!-- Display the table of Bag products -->

        <table border="1">
            <tr>
                <th>BagID</th>
                <th>Bag Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Added By</th>
                <th>Bag Category</th>
                <th>Bag Gender</th>
            </tr>
            <?php while ($row = $BagProducts->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row["BagID"]; ?></td>
                    <td><?php echo $row["BagName"]; ?></td>
                    <td><?php echo $row["BagDescription"]; ?></td>
                    <td><?php echo $row["QuantityAvaliable"]; ?></td>
                    <td><?php echo $row["Price"]; ?></td>
                    <td><?php echo $row["BagAddedBy"]; ?></td>
                    <td><?php echo $row["BagCategory"]; ?></td>
                    <td><?php echo $row["BagGender"]; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>


    <?php
    }
    ?>
</body>

</html>