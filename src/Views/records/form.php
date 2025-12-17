<?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?= $record ? "/records/{$record['id']}" : '/records' ?>">
    <div>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" 
               value="<?= htmlspecialchars($record['first_name'] ?? '') ?>" 
               maxlength="30" required>
        <small>*Required (30 character max)</small>
    </div>
    
    <div>
        <label for="middle_initial">M.I.:</label>
        <input type="text" id="middle_initial" name="middle_initial" 
               value="<?= htmlspecialchars($record['middle_initial'] ?? '') ?>" 
               maxlength="1" pattern="[A-Z]?">
        <small>*Optional (single capital letter)</small>
    </div>
    
    <div>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" 
               value="<?= htmlspecialchars($record['last_name'] ?? '') ?>" 
               maxlength="30" required>
        <small>*Required (30 character max)</small>
    </div>
    
    <div>
        <label for="loan">Loan:</label>
        <input type="number" id="loan" name="loan" step="0.01" 
               value="<?= $record['loan'] ?? '' ?>" required>
        <small>*Required</small>
    </div>
    
    <div>
        <label for="value">Value:</label>
        <input type="number" id="value" name="value" step="0.01" min="0.01" 
               value="<?= $record['value'] ?? '' ?>" required>
        <small>*Required (minimum 0.01)</small>
    </div>
    
    <div>
        <button type="submit"><?= $record ? 'Update' : 'Create' ?> Record</button>
        <a href="/records" class="btn">Cancel</a>
    </div>
</form>