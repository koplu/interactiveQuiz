<?php 
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="header">
    <a href="home.page.php">Home</a>
    <div class="header-right">
        <span>
            <?php 
                echo isset($_SESSION['user']) ? $_SESSION['user'] : "Anonymous";
            ?>
        </span>
        <span>/</span>
        <span>
            <?php 
                if(isset($_SESSION['user'])){
                    echo "<a href='../includes/logout.inc.php'>logout</a>";
                }
                else {
                    echo "<a href='../index.php'>Login</a>";
                }
            ?>
        </span>
    </div>
</div>