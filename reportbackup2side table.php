<!DOCTYPE html>
<html>
<head>
    <title>Report View</title>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        .checkbox-group {
            display: inline-block;
            margin-right: 10px;
        }
        .table-container {
            display: inline-block;
            vertical-align: top;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <?php
    // Check if the form is submitted
    if (isset($_POST['search'])) {
        // Assuming you have established a database connection
        include "db_conn.php";

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category = $_POST['category'];
        $laptop_checked = isset($_POST['laptop']);
        $mobile_checked = isset($_POST['mobile']);
        $pc_checked = isset($_POST['pc']);

        // Prepare the SQL query based on selected category, date range, and checkbox options
        $sql = "SELECT * FROM records WHERE recordDate BETWEEN '$start_date' AND '$end_date'";

        if ($category !== 'All') {
            $sql .= " AND AsUrg = '$category'";
        }

        $productTypes = [];
        if ($laptop_checked) {
            $productTypes[] = "Laptop";
        }
        if ($mobile_checked) {
            $productTypes[] = "Mobile";
        }
        if ($pc_checked) {
            $productTypes[] = "PC";
        }
        if (!empty($productTypes)) {
            $productTypesString = implode("', '", $productTypes);
            $sql .= " AND ProductType IN ('$productTypesString')";
        }

        // Execute the SQL query
        $result = $conn->query($sql);

        if ($result) {
            $reportData = $result->fetch_all(MYSQLI_ASSOC);

            // Count query to display the count results below the table
            $countQuery = "SELECT TechName, COUNT(*) AS count FROM records WHERE recordDate BETWEEN '$start_date' AND '$end_date'";

            if ($category !== 'All') {
                $countQuery .= " AND AsUrg = '$category'";
            }

            if (!empty($productTypes)) {
                $countQuery .= " AND ProductType IN ('$productTypesString')";
            }

            $countQuery .= " GROUP BY TechName";
            $countResult = $conn->query($countQuery);

            // Count query for ProductType
            $productCountQuery = "SELECT ProductType, COUNT(*) AS count FROM records WHERE recordDate BETWEEN '$start_date' AND '$end_date'";

            if ($category !== 'All') {
                $productCountQuery .= " AND AsUrg = '$category'";
            }

            if (!empty($productTypes)) {
                $productCountQuery .= " AND ProductType IN ('$productTypesString')";
            }

            $productCountQuery .= " GROUP BY ProductType";
            $productCountResult = $conn->query($productCountQuery);

            // Total count query
            $totalCountQuery = "SELECT COUNT(*) AS total FROM records WHERE recordDate BETWEEN '$start_date' AND '$end_date'";

            if ($category !== 'All') {
                $totalCountQuery .= " AND AsUrg = '$category'";
            }

            if (!empty($productTypes)) {
                $totalCountQuery .= " AND ProductType IN ('$productTypesString')";
            }

            $totalCountResult = $conn->query($totalCountQuery);
            $totalCountRow = $totalCountResult->fetch_assoc();
            $total = $totalCountRow['total'];
        } else {
            echo "Error executing the query: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
    } else {
        // Set initial checkbox and radio button states
        $laptop_checked = true;
        $mobile_checked = true;
        $pc_checked = true;
        $category = 'Assembly';
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
    }

    // Retain form data after search
    $start_date_value = isset($_POST['start_date']) ? $_POST['start_date'] : $start_date;
    $end_date_value = isset($_POST['end_date']) ? $_POST['end_date'] : $end_date;
    $category_value = isset($_POST['category']) ? $_POST['category'] : $category;
    ?>

    <form method="post">
        <label for="urgent">Urgent:</label>
        <input type="radio" id="urgent" name="category" value="Urgent" <?php echo ($category_value === 'Urgent') ? 'checked' : ''; ?>>
        <label for="assembly">Assembly:</label>
        <input type="radio" id="assembly" name="category" value="Assembly" <?php echo ($category_value === 'Assembly') ? 'checked' : ''; ?>>
        <br><br>
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $start_date_value; ?>" required>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $end_date_value; ?>" required>
        <br><br>
        <div class="checkbox-group">
            <label for="laptop">Laptop:</label>
            <input type="checkbox" id="laptop" name="laptop" value="1" <?php echo $laptop_checked ? 'checked' : ''; ?>>
        </div>
        <div class="checkbox-group">
            <label for="pc">PC:</label>
            <input type="checkbox" id="pc" name="pc" value="1" <?php echo $pc_checked ? 'checked' : ''; ?>>
        </div>
        <div class="checkbox-group">
            <label for="mobile">Mobile:</label>
            <input type="checkbox" id="mobile" name="mobile" value="1" <?php echo $mobile_checked ? 'checked' : ''; ?>>
        </div>
        
        <button type="submit" name="search">Search</button>
    </form>

    <br>

    <?php if (isset($reportData) && !empty($reportData)) : ?>
        <table>
            <thead>
                <tr>
                    <?php foreach ($reportData[0] as $column => $value) : ?>
                        <th><?php echo $column; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData as $row) : ?>
                    <tr>
                        <?php foreach ($row as $data) : ?>
                            <td><?php echo $data; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>

        <?php if (isset($countResult) && !empty($countResult)) : ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Technician Name</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($countRow = $countResult->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $countRow['TechName']; ?></td>
                                <td><?php echo $countRow['count']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (isset($productCountResult) && !empty($productCountResult)) : ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Product Type</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($productCountRow = $productCountResult->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $productCountRow['ProductType']; ?></td>
                                <td><?php echo $productCountRow['count']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <br>

        <p>Total Count: <?php echo $total; ?></p>

    <?php elseif (isset($_POST['search'])) : ?>
        <p>No records found.</p>
    <?php endif; ?>
</body>
</html>
