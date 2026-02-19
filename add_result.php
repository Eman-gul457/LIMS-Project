<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sampleId = (int)($_POST['sample_id'] ?? 0);
    $resultValue = trim($_POST['result_value'] ?? '');
    $resultDate = trim($_POST['result_date'] ?? '');
    $technicianName = trim($_POST['technician_name'] ?? '');
    $approvalStatus = trim($_POST['approval_status'] ?? 'Pending');

    if ($sampleId <= 0 || $resultValue === '' || $resultDate === '' || $technicianName === '' || $approvalStatus === '') {
        redirect_with_message('add_result.php', 'All fields are required.', 'error');
    }

    $stmt = $mysqli->prepare('INSERT INTO results (sample_id, result_value, result_date, technician_name, approval_status) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('issss', $sampleId, $resultValue, $resultDate, $technicianName, $approvalStatus);

    if (!$stmt->execute()) {
        redirect_with_message('add_result.php', 'Failed to save result: ' . $stmt->error, 'error');
    }

    $status = 'Completed';
    $updateSample = $mysqli->prepare('UPDATE samples SET status = ? WHERE sample_id = ?');
    $updateSample->bind_param('si', $status, $sampleId);
    $updateSample->execute();

    $updateTest = $mysqli->prepare("UPDATE tests SET status = 'Completed' WHERE sample_id = ? AND status != 'Completed'");
    $updateTest->bind_param('i', $sampleId);
    $updateTest->execute();

    redirect_with_message('reports.php?sample_id=' . $sampleId, 'Result recorded and sample marked Completed.');
}

$samples = $mysqli->query("SELECT sample_id, patient_name FROM samples WHERE status IN ('Pending', 'In Progress', 'Completed') ORDER BY sample_id DESC");

require __DIR__ . '/includes/header.php';
?>
<h2>Add Test Result</h2>
<form method="post" class="card form-grid">
    <label>Sample
        <select name="sample_id" required>
            <option value="">Select sample</option>
            <?php while ($sample = $samples->fetch_assoc()): ?>
                <option value="<?= h($sample['sample_id']) ?>">#<?= h($sample['sample_id']) ?> - <?= h($sample['patient_name']) ?></option>
            <?php endwhile; ?>
        </select>
    </label>
    <label>Result Value
        <textarea name="result_value" rows="3" required></textarea>
    </label>
    <label>Result Date
        <input type="date" name="result_date" required>
    </label>
    <label>Technician Name
        <input type="text" name="technician_name" required>
    </label>
    <label>Approval Status
        <select name="approval_status" required>
            <option>Pending</option>
            <option>Approved</option>
            <option>Rejected</option>
        </select>
    </label>
    <button class="btn" type="submit">Save Result</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
