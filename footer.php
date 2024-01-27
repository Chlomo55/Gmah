<?php require_once('connection.php');?>

</body>
<div class="container">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <div class="col-md-4 d-flex align-items-center">
      <span class="mb-3 mb-md-0 text-muted">© 2023 Gma'h du Raincy</span>
    </div>
   <?php 
   $contact = $pdo->query("SELECT * FROM contact");
   while($contacts = $contact->fetch()){
    ?>
    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
      <li class="ms-3"><a class="text-muted" href="<?= $contacts['twitter']?>"><i class="bi bi-twitter" style="font-size: 24px;"></i></a></li>
      <li class="ms-3"><a class="text-muted" href="<?= $contacts['insta']?>"><i class="bi bi-instagram" style="font-size: 24px;"></i></a></li>
      <li class="ms-3"><a class="text-muted" href="<?= $contacts['facebook']?>"><i class="bi bi-facebook" style="font-size: 24px;"></i></a></li>
    </ul>
    <?php } ?>
  </footer>
</div>

</body>
</html>
