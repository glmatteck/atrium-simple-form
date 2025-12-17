<?php if (!empty($message)): ?>
    <div class="message">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<div class="actions">
    <a href="/records/create" class="btn btn-primary">Add New Record</a>
</div>

<table>
    <thead>
        <tr>
            <!-- <th>ID</th> -->
            <th>First Name</th>
            <th>M.I.</th>
            <th>Last Name</th>
            <th>Loan</th>
            <th>Value</th>
            <th>LTV</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $record): ?>
        <tr>
            <!-- <td><?= htmlspecialchars($record['id']) ?></td> -->
            <td><?= htmlspecialchars($record['first_name']) ?></td>
            <td><?= htmlspecialchars($record['middle_initial']) ?></td>
            <td><?= htmlspecialchars($record['last_name']) ?></td>
            <td><?= number_format($record['loan'], 2) ?></td>
            <td><?= number_format($record['value'], 2) ?></td>
            <td><?= htmlspecialchars($record['ltv']) ?></td>
            <td class="tbl-actions">
                <a href="/records/<?= $record['id'] ?>/edit" class="btn btn-sm">Edit</a>
                <form class="delete-form" method="POST" action="/records/<?= $record['id'] ?>/delete" style="display:inline;">
                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>