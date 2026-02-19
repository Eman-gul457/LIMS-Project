<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientName = trim($_POST['patient_name'] ?? '');
    $sampleType = trim($_POST['sample_type'] ?? '');
    $testName = trim($_POST['test_name'] ?? '');
    $dateReceived = trim($_POST['date_received'] ?? '');

    if ($patientName === '' || $sampleType === '' || $testName === '' || $dateReceived === '') {
        redirect_with_message('add_sample.php', 'All fields are required.', 'error');
    }

    $stmt = $mysqli->prepare('INSERT INTO samples (patient_name, sample_type, test_name, date_received, status) VALUES (?, ?, ?, ?, ?)');
    $status = 'Pending';
    $stmt->bind_param('sssss', $patientName, $sampleType, $testName, $dateReceived, $status);

    if ($stmt->execute()) {
        redirect_with_message('view_samples.php', 'Sample added successfully.');
    }

    redirect_with_message('add_sample.php', 'Failed to add sample: ' . $stmt->error, 'error');
}

require __DIR__ . '/includes/header.php';
?>
<h2>Add Sample</h2>
<form method="post" class="card form-grid">
    <label>Patient Name
        <input type="text" name="patient_name" required>
    </label>
    <label>Sample Type
        <select name="sample_type" required>
            <option value="">Select sample type</option>
            <option>Blood</option>
            <option>Water</option>
            <option>Soil</option>
            <option>Urine</option>
        </select>
    </label>
    <label>Test Name
        <input type="text" name="test_name" required>
    </label>
    <label>Date Received
        <input type="date" name="date_received" required>
    </label>
    <button class="btn" type="submit">Save Sample</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
