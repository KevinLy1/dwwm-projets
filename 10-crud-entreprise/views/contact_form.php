<form method="POST">
    <?php foreach($fields as $field) : ?>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="<?php echo $field["Field"] ?>" id="<?php echo $field["Field"] ?>" value="<?php echo ($op == "update") ? $values[$field["Field"]] : "" ?>">
            <label for="<?php echo $field["Field"] ?>" class="form-label">💬 <?php echo $field["Field"] ?></label>
        </div>
    <?php endforeach; ?>
    <div class="text-center m-5">
        <button type="submit" class="btn btn-primary">✅ Valider</button>
    </div>
</form>