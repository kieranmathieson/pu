<?php /** @noinspection PhpUndefinedVariableInspection */
/**
 * These are fields for editing basic person data, used in editing own account, and when
 * admins edit other people's accounts.
 *
 * Note that vars $firstName, $lastName, etc., must have values.
 */
?>
<div class="form-group">
    <label for="first-name">First name</label>
    <input type="text" class="form-control" id="first-name" name="first-name"
           value="<?php print $firstName; ?>">
</div>
<div class="form-group">
    <label for="last-name">Last name</label>
    <input type="text" class="form-control" id="last-name" name="last-name"
           value="<?php print $lastName; ?>">
</div>
<div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" id="email" name="email"
           value="<?php print $email; ?>">
</div>
<div class="form-group">
    <label for="telephone">Telephone</label>
    <input type="tel" class="form-control" id="telephone" name="telephone"
           value="<?php print $telephone; ?>">
</div>
<div class="form-group">
    <label for="about">About</label>
    <textarea class="form-control" id="about" name="about" rows="4"><?php print $about; ?></textarea>
</div>

