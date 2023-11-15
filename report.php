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
        <label for="laptop">Laptop</label>
        <input type="checkbox" id="laptop" name="laptop" <?php echo ($laptop_checked) ? 'checked' : ''; ?>>
        <label for="mobile">Mobile</label>
        <input type="checkbox" id="mobile" name="mobile" <?php echo ($mobile_checked) ? 'checked' : ''; ?>>
        <label for="pc">PC</label>
        <input type="checkbox" id="pc" name="pc" <?php echo ($pc_checked) ? 'checked' : ''; ?>>
        <br><br>
        <input type="submit" name="search" value="Search">
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

        <table>
            <thead>
                <tr>
                    <th>ProductType</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $productTypeCount = array();

                foreach ($reportData as $row) {
                    $productType = $row['ProductType'];
                    $techName = $row['TechName'];

                    if (!isset($productTypeCount[$productType])) {
                        $productTypeCount[$productType] = array();
                    }

                    if (!isset($productTypeCount[$productType][$techName])) {
                        $productTypeCount[$productType][$techName] = 0;
                    }

                    $productTypeCount[$productType][$techName]++;
                }

                foreach ($productTypeCount as $productType => $counts) {
                    echo "<tr>";
                    echo "<td>$productType</td>";
                    echo "<td>";
                    foreach ($counts as $techName => $count) {
                        echo "$techName = $count<br>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php elseif (isset($reportData) && empty($reportData)) : ?>
        <p>No data available.</p>
    <?php endif; ?>
</body>
</html>
