<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$sampleId = (int)($_GET['sample_id'] ?? 0);
$where = '';
$params = [];
$types = '';

if ($sampleId > 0) {
    $where = 'WHERE s.sample_id = ?';
    $params[] = $sampleId;
    $types .= 'i';
}

$sql = "
SELECT
    s.sample_id,
    s.patient_name,
    s.sample_type,
    s.test_name AS requested_test_name,
    s.date_received,
    s.status AS sample_status,
    t.test_id,
    t.test_name,
    t.technician_name AS test_technician,
    t.status AS test_status,
    r.result_id,
    r.result_value,
    r.result_date,
    r.technician_name AS result_technician,
    r.approval_status
FROM samples s
LEFT JOIN tests t ON t.sample_id = s.sample_id
LEFT JOIN results r ON r.sample_id = s.sample_id
$where
ORDER BY s.sample_id DESC, t.test_id DESC, r.result_id DESC
";

$stmt = $mysqli->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$rows = $stmt->get_result();

require __DIR__ . '/includes/header.php';
?>
<h2>Reports</h2>
<form method="get" class="card filter-row">
    <input type="number" min="1" name="sample_id" placeholder="Filter by Sample ID" value="<?= $sampleId > 0 ? h($sampleId) : '' ?>">
    <button class="btn" type="submit">Generate</button>
    <a class="btn secondary" href="reports.php">Clear</a>
    <button class="btn secondary" type="button" onclick="window.print()">Print</button>
</form>
<div class="table-wrap card">
    <table>
        <thead>
            <tr>
                <th>Sample ID</th>
                <th>Patient</th>
                <th>Sample Type</th>
                <th>Requested Test</th>
                <th>Sample Status</th>
                <th>Assigned Test</th>
                <th>Test Technician</th>
                <th>Test Status</th>
                <th>Result</th>
                <th>Result Date</th>
                <th>Approval</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $rows->fetch_assoc()): ?>
            <tr>
                <td><?= h($row['sample_id']) ?></td>
                <td><?= h($row['patient_name']) ?></td>
                <td><?= h($row['sample_type']) ?></td>
                <td><?= h($row['requested_test_name']) ?></td>
                <td><span class="<?= status_class($row['sample_status']) ?>"><?= h($row['sample_status']) ?></span></td>
                <td><?= h($row['test_name'] ?? '-') ?></td>
                <td><?= h($row['test_technician'] ?? '-') ?></td>
                <td><?= h($row['test_status'] ?? '-') ?></td>
                <td><?= h($row['result_value'] ?? '-') ?></td>
                <td><?= h($row['result_date'] ?? '-') ?></td>
                <td><span class="<?= status_class($row['approval_status'] ?? '') ?>"><?= h($row['approval_status'] ?? '-') ?></span></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
