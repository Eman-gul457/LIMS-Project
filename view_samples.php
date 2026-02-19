<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');

$sql = 'SELECT sample_id, patient_name, sample_type, test_name, date_received, status, created_at FROM samples WHERE 1=1';
$params = [];
$types = '';

if ($search !== '') {
    $sql .= ' AND (CAST(sample_id AS CHAR) LIKE ? OR patient_name LIKE ? OR test_name LIKE ?)';
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'sss';
}

if ($status !== '') {
    $sql .= ' AND status = ?';
    $params[] = $status;
    $types .= 's';
}

$sql .= ' ORDER BY sample_id DESC';

$stmt = $mysqli->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

require __DIR__ . '/includes/header.php';
?>
<h2>View Samples</h2>
<form method="get" class="card filter-row">
    <input type="text" name="search" placeholder="Search by ID, patient, test" value="<?= h($search) ?>">
    <select name="status">
        <option value="">All statuses</option>
        <?php foreach (['Pending', 'In Progress', 'Completed'] as $s): ?>
            <option value="<?= h($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= h($s) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Apply</button>
</form>
<div class="table-wrap card">
    <table>
        <thead>
            <tr>
                <th>Sample ID</th>
                <th>Patient Name</th>
                <th>Sample Type</th>
                <th>Test Name</th>
                <th>Date Received</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= h($row['sample_id']) ?></td>
                <td><?= h($row['patient_name']) ?></td>
                <td><?= h($row['sample_type']) ?></td>
                <td><?= h($row['test_name']) ?></td>
                <td><?= h($row['date_received']) ?></td>
                <td><span class="<?= status_class($row['status']) ?>"><?= h($row['status']) ?></span></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
