
<form method="POST" action="index.php/deliveryAdress/add">
 <div class="card">             
   <div class="card-header">
     new adress interance
   </div>

   <div class="card-body"> 

    <?php if($hasErrors): ?>
      <ul class="alert alert-danger">
       <?php foreach($errors as $errorMassage):?>
          <li><?=  $errorMassage ?></li>
          <?php endforeach ?>
        </ul>
        <?php endif; ?>
    <div class="form-group">    
        <label for="recipient">empfanger</label>
        <input name="recipient" value="<?= escape($recipient) ?>"class="form-control <?= $recipientIsValid ?'':'is-invalid'?>" id="recipient">
    </div>

    <div class="form-group">
        <label for="city">stadt</label>
        <input name="city" value="<?= escape($city) ?>" class="form-control <?= $cityIsValid ?'':'is-invalid'?>" id="city">
     </div>
     <div class="form-group">
        <label for="zipCode">PLZ</label>
        <input name="zipCode" value="<?= escape($zipCode) ?>" class="form-control <?= $zipCodeIsValid ?'':'is invalid'?>" id="zipCode">
     </div>

     <div class="form-group">
        <label for="street">sreet</label>
        <input name="street" value="<?= escape($street) ?>" class="form-control <?= $streetIsValid ?'':'is invalid'?>" id="street">
     </div>
     <div class="form-group">
        <label for="streetNumber">streetNumber</label>
        <input name="streetNumber" value="<?= escape($streetNumber)?>" class="form-control <?= $streetNumberIsValid ?'':'is invalid'?>" id="streetNumber">
     </div>

   </div>
   <div class="card-footer">
        <button class="btn btn-success" type="submit">Speichern</button>
        
      </div>
   <div class="card-footer">
     
   </div>
 </div> 
</form>

 