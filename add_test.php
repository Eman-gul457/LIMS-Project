<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sampleId = (int)($_POST['sample_id'] ?? 0);
    $testName = trim($_POST['test_name'] ?? '');
    $technicianName = trim($_POST['technician_name'] ?? '');
    $status = trim($_POST['status'] ?? 'Pending');

    if ($sampleId <= 0 || $testName === '' || $technicianName === '') {
        redirect_with_message('add_test.php', 'All fields are required.', 'error');
    }

    $stmt = $mysqli->prepare('INSERT INTO tests (sample_id, test_name, technician_name, status) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('isss', $sampleId, $testName, $technicianName, $status);

    if (!$stmt->execute()) {
        redirect_with_message('add_test.php', 'Failed to assign test: ' . $stmt->error, 'error');
    }

    $newSampleStatus = $status === 'Completed' ? 'Completed' : 'In Progress';
    $update = $mysqli->prepare('UPDATE samples SET status = ? WHERE sample_id = ?');
    $update->bind_param('si', $newSampleStatus, $sampleId);
    $update->execute();

    redirect_with_message('view_samples.php', 'Test assigned successfully.');
}

$samples = $mysqli->query('SELECT sample_id, patient_name, test_name, status FROM samples ORDER BY sample_id DESC');

require __DIR__ . '/includes/header.php';
?>
<h2>Add Test</h2>
<form method="post" class="card form-grid">
    <label>Sample
        <select name="sample_id" required>
            <option value="">Select sample</option>
            <?php while ($sample = $samples->fetch_assoc()): ?>
                <option value="<?= h($sample['sample_id']) ?>">
                    #<?= h($sample['sample_id']) ?> - <?= h($sample['patient_name']) ?> (<?= h($sample['test_name']) ?>)
                </option>
            <?php endwhile; ?>
        </select>
    </label>
    <label>Test Name
        <input type="text" name="test_name" required>
    </label>
    <label>Technician Name
        <input type="text" name="technician_name" required>
    </label>
    <label>Status
        <select name="status" required>
            <option>Pending</option>
            <option>In Progress</option>
            <option>Completed</option>
        </select>
    </label>
    <button class="btn" type="submit">Assign Test</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
