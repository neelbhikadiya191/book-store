<?php

require('fpdf184/fpdf.php');

class InvoicePDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(256, 256, 256);
        $this->Cell(0, 10, 'Invoice', 0, 1, 'C', true);
        $this->SetFillColor(255, 255, 255);
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function ChapterBody($content)
    {
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(0, 10, $content);
        $this->SetTextColor(0, 0, 0);
        $this->Ln();
    }
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'syntax_squard_bookstore';

// Create a database connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Check if the user is logged in
if (isset($_SESSION['id'])) {
    $username = $_SESSION['id'];

    // Fetch data from the database for the invoice
    $query = "SELECT Customer.CustomerName, Book.BookTitle, `Order`.OrderID, `Order`.TotalPrice, Book.Image
          FROM `Order`
          JOIN Customer ON `Order`.CustomerID = Customer.CustomerID
          JOIN Book ON `Order`.BookID = Book.BookID
          WHERE Customer.CustomerID = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pdf = new InvoicePDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        while ($row = $result->fetch_assoc()) {
            $content = "Customer Name: " . $row['CustomerName'] . "\n";
            $content .= "Order ID: " . $row['OrderID'] . "\n";
            $content .= "Book Title: " . $row['BookTitle'] . "\n";
            $content .= "Total Price: $" . number_format($row['TotalPrice'], 2) . "\n\n";
            $pdf->ChapterBody($content);

            // Add image if needed
            // $pdf->Image($row['Image'], 10, $pdf->GetY(), 50);
        }

        // Output PDF to the browser
        $pdf->Output('invoice.pdf', 'D');
    } else {
        echo "No orders found";
    }
} else {
    // Handle case where username is not set in the session
    echo "Username not set in the session.";
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
