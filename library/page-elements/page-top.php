<div class="container">
    <div class="row"> <!-- Header row -->
        <div class="col-12"> <!-- Branding and top navbar region -->
            <?php
            require_once 'branding-navbar.php';
            ?>
        </div>
    </div> <!-- End header row -->
    <div class="row"> <!-- Row for optional sidebar and content regions -->
        <?php
        global $currentUser;
        // Sidebar only for logged in users.
        if ($currentUser != null) {
            // User is logged in.
            ?>
            <div class="col-2 mt-3 "> <!-- Sidebar region -->
                <?php
                require_once 'sidebar-nav.php';
                ?>
            </div>
            <!-- Start the content region -->
            <div class="col-10">
        <?php
        }
        else {
            // User is not logged in. Content region takes all 12 cols.
            ?>
            <div class="col-12">
        <?php
        }
        ?>
